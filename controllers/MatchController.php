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
        $this->userModel = new User(); // Change from Utilisateur to User
        $this->profileModel = new Profile();
        $this->matchModel = new UserMatch();
        
        // Check if user is logged in for all matching actions
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
        
        // Get potential matches
        $potentialMatches = $this->userModel->getPotentialMatches($userId);
        
        // Calculate age for each potential match if not already included
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
        
        // Create the match
        $matchCreated = $this->matchModel->createLike($userId, $likedUserId);
        
        // Check if it's a mutual match
        $isMatch = $this->matchModel->checkMutualMatch($userId, $likedUserId);
        
        if ($isMatch) {
            // It's a match!
            $_SESSION['match'] = [
                'user_id' => $likedUserId
            ];
            $this->redirect('/discover?match=1');
        } else {
            // Just a like, continue discovering
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
        
        // Record the pass
        $this->matchModel->createPass($userId, $passedUserId);
        
        // Continue discovering
        $this->redirect('/discover?passed=1');
    }
    
    public function viewMatches() {
        $userId = $_SESSION['user_id'];
        
        // Get all mutual matches
        $matches = $this->matchModel->getMatches($userId);
        
        $this->render('match/matches', [
            'title' => 'Your Matches - Loove',
            'matches' => $matches
        ]);
    }
    
    private function getPotentialMatches($userId) {
        // Try to use the stored procedure if it exists
        try {
            $db = new Database();
            $result = $db->fetchAll("CALL find_potential_matches(?)", [$userId]);
            
            // If we got results, return them
            if (!empty($result)) {
                return $result;
            }
        } catch (Exception $e) {
            // If the procedure fails, fall back to the model method
        }
        
        // Fallback: Get users the current user has already interacted with
        $interactedUsers = $this->matchModel->getInteractedUserIds($userId);
        
        // Add the current user to the excluded list
        $interactedUsers[] = $userId;
        
        // Get the user's info for gender-based matching
        $user = $this->userModel->getById($userId);
        
        // Get orientation-based gender preferences
        $genderPreference = $this->getGenderPreference($user['gender'], $user['sexual_orientation']);
        
        // Get potential matches based on preferences
        $matches = $this->userModel->getPotentialMatches($userId, $interactedUsers, $genderPreference);
        
        // Calculate age for each potential match
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
        // Very basic gender preference logic
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
