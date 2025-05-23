<?php
require_once 'controllers/Controller.php';
require_once 'models/User.php';
require_once 'models/UserMatch.php';
require_once 'models/Message.php';

class MessageController extends Controller {
    private $userModel;
    private $matchModel;
    private $messageModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->matchModel = new UserMatch();
        $this->messageModel = new Message();
    
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login?error=auth_required');
            exit;
        }
    }
    
    public function viewConversations() {
        $userId = $_SESSION['user_id'];
        
       
        $conversations = $this->messageModel->getConversations($userId);
        
        $this->render('messages/conversations', [
            'title' => 'Messages - Loove',
            'conversations' => $conversations
        ]);
    }
    
    public function viewConversation() {
        $userId = $_SESSION['user_id'];
        $otherUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        
        if ($otherUserId <= 0) {
            $this->redirect('/messages?error=invalid_conversation');
            return;
        }
        
   
        $isMatched = $this->matchModel->checkIsMatched($userId, $otherUserId);
        
        if (!$isMatched) {
            $this->redirect('/messages?error=not_matched');
            return;
        }
        
     
        $otherUser = $this->userModel->getUserWithProfile($otherUserId);
        
        if (!$otherUser) {
            $this->redirect('/messages?error=user_not_found');
            return;
        }
        
      
        $messages = $this->messageModel->getMessages($userId, $otherUserId);
        
 
        $this->messageModel->markAsRead($otherUserId, $userId);
        
        $this->render('messages/conversation', [
            'title' => 'Conversation with ' . $otherUser['first_name'] . ' - Loove',
            'otherUser' => $otherUser,
            'messages' => $messages
        ]);
    }
    
    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/messages');
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $receiverId = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
        $messageText = isset($_POST['message']) ? trim($_POST['message']) : '';
        
        if ($receiverId <= 0 || empty($messageText)) {
            $this->redirect('/messages/conversation?user_id=' . $receiverId . '&error=invalid_message');
            return;
        }
        
    
        $isMatched = $this->matchModel->checkIsMatched($userId, $receiverId);
        
        if (!$isMatched) {
            $this->redirect('/messages?error=not_matched');
            return;
        }
        
       
        $sent = $this->messageModel->sendMessage($userId, $receiverId, $messageText);
        
        if (!$sent) {
            $this->redirect('/messages/conversation?user_id=' . $receiverId . '&error=send_failed');
            return;
        }
        
        
        $this->redirect('/messages/conversation?user_id=' . $receiverId);
    }
}
?>
