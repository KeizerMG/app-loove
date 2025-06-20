<?php
class Matches extends Controller {
    private $matchModel;
    private $userModel;
    private $profileModel;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        
        $this->matchModel = $this->model('MatchModel');
        $this->userModel = $this->model('User');
        $this->profileModel = $this->model('Profile');
    }

    public function index() {
      
        $matches = $this->matchModel->getUserMatches($_SESSION['user_id']);
        
        
        $matchDetails = [];
        foreach($matches as $match) {
            
            $otherUserId = ($match->user_id_1 == $_SESSION['user_id']) ? $match->user_id_2 : $match->user_id_1;
      
            $otherUser = $this->userModel->getUserById($otherUserId);
            
            if($otherUser) {
                
                $birthDate = new DateTime($otherUser->birth_date);
                $today = new DateTime('today');
                $age = $birthDate->diff($today)->y;
                
                $profileDetails = $this->profileModel->getProfileByUserId($otherUserId);
                
                
                $unreadCount = 0;
                $lastMessage = null;
                $lastMessageTime = null;
                
              
                if(file_exists(APPROOT . '/models/Message.php')) {
                    $messageModel = $this->model('Message');
                    $lastMessageData = $messageModel->getLastMessageBetween($_SESSION['user_id'], $otherUserId);
                    
                    if($lastMessageData) {
                        $lastMessage = $lastMessageData->message;
                        $lastMessageTime = $lastMessageData->created_at;
                    
                        $unreadCount = $messageModel->countUnreadMessagesFrom($otherUserId, $_SESSION['user_id']);
                    }
                }
                
                
                $matchDetails[] = [
                    'user' => $otherUser,
                    'age' => $age,
                    'profile' => $profileDetails,
                    'match_date' => $match->created_at,
                    'unread_count' => $unreadCount,
                    'last_message' => $lastMessage,
                    'last_message_time' => $lastMessageTime
                ];
            }
        }
        
       
        usort($matchDetails, function($a, $b) {
            
            if($a['last_message_time'] && $b['last_message_time']) {
                return strtotime($b['last_message_time']) - strtotime($a['last_message_time']);
            }
      
            return strtotime($b['match_date']) - strtotime($a['match_date']);
        });
        
        $data = [
            'title' => 'Mes Matchs',
            'matches' => $matchDetails
        ];
        
        $this->view('matches/index', $data);
    }
}
?>
