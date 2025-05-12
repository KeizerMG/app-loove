<?php
require_once 'controllers/Controller.php';
require_once 'models/User.php';
require_once 'models/Profile.php';
require_once 'models/UserMatch.php';

class MatchController extends Controller {
    private $userModel;
    private $profileModel;
    private $matchModel;
    
    public function __construct() {
        $this->userModel = new User(); 
        $this->profileModel = new Profile();
        $this->matchModel = new UserMatch();
        
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login?error=auth_required');
            exit;
        }
    }
    
    public function discoverProfiles() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);
        
        if (!$user) {
            $this->redirect('/logout');
            return;
        }
        
      
        $potentialMatches = $this->userModel->getPotentialMatches($userId);
        
      
        foreach ($potentialMatches as &$match) {
            if (isset($match['date_of_birth']) && !isset($match['age'])) {
                $dob = new DateTime($match['date_of_birth']);
                $now = new DateTime();
                $match['age'] = $now->diff($dob)->y;
            } elseif (!isset($match['age'])) {
                $match['age'] = 'Unknown';
            }
        }
        
        $this->render('match/discover', [
            'title' => 'Discover - Loove',
            'user' => $user,
            'potentialMatches' => $potentialMatches
        ]);
    }
    
    public function likeProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/discover');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $likedUserId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if ($likedUserId <= 0) {
            $this->redirect('/discover?error=invalid_user');
            return;
        }
        

        $matchCreated = $this->matchModel->createLike($userId, $likedUserId);
        

        $isMatch = $this->matchModel->checkMutualMatch($userId, $likedUserId);
        
        if ($isMatch) {
        
            $_SESSION['match'] = [
                'user_id' => $likedUserId
            ];
            $this->redirect('/discover?match=1');
        } else {
         
            $this->redirect('/discover?liked=1');
        }
    }
    
    public function passProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/discover');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $passedUserId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if ($passedUserId <= 0) {
            $this->redirect('/discover?error=invalid_user');
            return;
        }
        
  
        $this->matchModel->createPass($userId, $passedUserId);
        
        
        $this->redirect('/discover?passed=1');
    }
    
    public function viewMatches() {
        $userId = $_SESSION['user_id'];
        
       
        $matches = $this->matchModel->getMatches($userId);
        
        $this->render('match/matches', [
            'title' => 'Your Matches - Loove',
            'matches' => $matches
        ]);
    }
    
    private function getPotentialMatches($userId) {
    
        try {
            $db = new Database();
            $result = $db->fetchAll("CALL find_potential_matches(?)", [$userId]);
            
        
            if (!empty($result)) {
                return $result;
            }
        } catch (Exception $e) {
           
        }
        
       
        $interactedUsers = $this->matchModel->getInteractedUserIds($userId);
        
   
        $interactedUsers[] = $userId;
        

        $user = $this->userModel->getById($userId);
        
        
        $genderPreference = $this->getGenderPreference($user['gender'], $user['sexual_orientation']);
        
        $matches = $this->userModel->getPotentialMatches($userId, $interactedUsers, $genderPreference);
        
      
        foreach ($matches as &$match) {
            if (isset($match['date_of_birth'])) {
                $dob = new DateTime($match['date_of_birth']);
                $now = new DateTime();
                $match['age'] = $now->diff($dob)->y;
            } else {
                $match['age'] = 'Unknown';
            }
        }
        
        return $matches;
    }
    
    private function getGenderPreference($gender, $orientation) {
       
        switch ($orientation) {
            case 'heterosexual':
                return $gender === 'male' ? ['female'] : ['male'];
            case 'homosexual':
                return [$gender];
            case 'bisexual':
            default:
                return ['male', 'female', 'other'];
        }
    }
}
?>
