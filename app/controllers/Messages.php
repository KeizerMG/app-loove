<?php
class Messages extends Controller {
    private $messageModel;
    private $userModel;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        
        $this->messageModel = $this->model('Message');
        $this->userModel = $this->model('User');
        
        $_SESSION['unread_messages_count'] = rand(1, 5);
    }

    public function index() {
        $realConversations = $this->messageModel->getConversations($_SESSION['user_id']);
        
        $fakeConversations = $this->generateFakeConversations();
        
        $conversations = array_merge($realConversations, $fakeConversations);
        
        usort($conversations, function($a, $b) {
            $timeA = isset($a->last_message_time) ? strtotime($a->last_message_time) : 0;
            $timeB = isset($b->last_message_time) ? strtotime($b->last_message_time) : 0;
            return $timeB - $timeA;
        });
        
        $data = [
            'title' => 'Mes Messages',
            'conversations' => $conversations
        ];

        $this->view('messages/index', $data);
    }

    public function with($otherUserId = null) {
        if(!$otherUserId) {
            redirect('messages');
        }

        $isFakeUser = $otherUserId >= 1000;        
        if($isFakeUser) {
            $otherUser = $this->generateFakeUser($otherUserId);
            $messages = $this->generateFakeMessages($otherUserId);
        } else {
            $otherUser = $this->userModel->getUserById($otherUserId);
            if(!$otherUser) {
                flash('messages_error', 'Utilisateur introuvable', 'alert-loove-danger');
                redirect('messages');
            }
            
            $messages = $this->messageModel->getMessagesBetween($_SESSION['user_id'], $otherUserId);
            
            $this->messageModel->markAsRead($_SESSION['user_id'], $otherUserId);
        }

        $data = [
            'title' => 'Conversation avec ' . ($isFakeUser ? $otherUser->first_name : $otherUser->first_name),
            'other_user' => $otherUser,
            'messages' => $messages,
            'is_fake_user' => $isFakeUser,
            'message' => '',
            'message_err' => ''
        ];

        $this->view('messages/conversation', $data);
    }    public function send() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $receiverId = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
            $message = isset($_POST['message']) ? trim($_POST['message']) : '';
            
            $response = [
                'success' => false,
                'message' => ''
            ];

            if(!$receiverId) {
                $response['message'] = 'ID du destinataire manquant';
                
                if($isAjax) {
                    $this->jsonResponse($response);
                } else {
                    flash('message_error', $response['message'], 'alert-loove-danger');
                    redirect('messages');
                }
                return;
            }

            if(empty($message)) {
                $response['message'] = 'Veuillez entrer un message';
                
                if($isAjax) {
                    $this->jsonResponse($response);
                } else {
                    flash('message_error', $response['message'], 'alert-loove-danger');
                    redirect('messages/with/' . $receiverId);
                }
                return;
            }

            $isFakeUser = $receiverId >= 1000;
            
            if($isFakeUser) {
                $response['success'] = true;
                
                $user = $this->userModel->getUserById($_SESSION['user_id']);
                
                if(!$user) {
                    $response['message'] = 'Erreur lors de la récupération des informations utilisateur';
                    
                    if($isAjax) {
                        $this->jsonResponse($response);
                    } else {
                        flash('message_error', $response['message'], 'alert-loove-danger');
                        redirect('messages/with/' . $receiverId);
                    }
                    return;
                }
                
                $response['message_data'] = [
                    'id' => rand(10000, 99999),
                    'sender_id' => $_SESSION['user_id'],
                    'sender_first_name' => $user->first_name,
                    'sender_profile_pic' => $user->profile_pic ?? 'default.jpg',
                    'message' => $message,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $response['auto_reply'] = [
                    'delay' => rand(2, 5),
                    'message_data' => $this->generateAutoReply($receiverId, $message)
                ];
            } else {
                if($this->messageModel->sendMessage($_SESSION['user_id'], $receiverId, $message)) {
                    $response['success'] = true;
                    
                    $user = $this->userModel->getUserById($_SESSION['user_id']);
                    
                    $response['message_data'] = [
                        'id' => rand(10000, 99999),
                        'sender_id' => $_SESSION['user_id'],
                        'sender_first_name' => $user->first_name,
                        'sender_profile_pic' => $user->profile_pic ?? 'default.jpg',
                        'message' => $message,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                } else {
                    $response['message'] = 'Une erreur est survenue lors de l\'envoi du message';
                }
            }

            if($isAjax) {
                $this->jsonResponse($response);
            } else {
                if($response['success']) {
                    redirect('messages/with/' . $receiverId);
                } else {
                    flash('message_error', $response['message'], 'alert-loove-danger');
                    redirect('messages/with/' . $receiverId);
                }
            }
        } else {
            redirect('messages');
        }
    }    
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    
    private function generateFakeConversations() {
        $fakeConversations = [];
        $firstNames = ['Emma', 'Léa', 'Manon', 'Julie', 'Camille', 'Lucas', 'Hugo', 'Thomas', 'Nicolas', 'Antoine'];
        $lastNames = ['Martin', 'Bernard', 'Dubois', 'Thomas', 'Petit', 'Robert', 'Richard', 'Durand'];
        $cities = ['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille', 'Toulouse', 'Nice', 'Nantes'];
        $lastMessages = [
            'Salut, comment vas-tu ?',
            'Tu fais quoi ce soir ?',
            'J\'aimerais bien te rencontrer',
            'On pourrait aller boire un verre ?',
            'Tu as passé une bonne journée ?',
            'C\'était sympa de discuter avec toi',
            'Tu as des projets pour le weekend ?',
            'Je t\'envoie la photo dont je t\'ai parlé',
            'Merci pour ton message',
            'À bientôt j\'espère'
        ];
        
        $messageTimes = [
            date('Y-m-d H:i:s', strtotime('-5 minutes')),
            date('Y-m-d H:i:s', strtotime('-30 minutes')),
            date('Y-m-d H:i:s', strtotime('-2 hours')),
            date('Y-m-d H:i:s', strtotime('-5 hours')),
            date('Y-m-d H:i:s', strtotime('-1 day')),
            date('Y-m-d H:i:s', strtotime('-2 days')),
            date('Y-m-d H:i:s', strtotime('-4 days'))
        ];        
        for($i = 0; $i < $count; $i++) {
            $id = 1000 + $i;
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $age = rand(21, 40);
            $city = $cities[array_rand($cities)];
            $lastMessage = $lastMessages[array_rand($lastMessages)];
            $lastMessageTime = $messageTimes[array_rand($messageTimes)];
            $lastMessageSender = (rand(0, 1) == 0) ? $_SESSION['user_id'] : $id;
            $unreadCount = ($lastMessageSender != $_SESSION['user_id']) ? rand(0, 3) : 0;
            
            $gender = (in_array($firstName, ['Emma', 'Léa', 'Manon', 'Julie', 'Camille'])) ? 'women' : 'men';
            $profilePic = "https://source.unsplash.com/featured/300x400?face,$gender&sig=" . uniqid();
            
            $conversation = (object)[
                'id' => $id,
                'first_name' => $firstName,
                'last_name' => substr($lastName, 0, 1) . '.',
                'age' => $age,
                'location' => $city,
                'profile_pic' => $profilePic,
                'last_message' => $lastMessage,
                'last_message_time' => $lastMessageTime,
                'last_message_sender' => $lastMessageSender,
                'unread_count' => $unreadCount,
                'is_fake' => true
            ];
            
            $fakeConversations[] = $conversation;
        }
        
        return $fakeConversations;
    }
    
    private function generateFakeUser($userId) {
        $firstNames = ['Emma', 'Léa', 'Manon', 'Julie', 'Camille', 'Lucas', 'Hugo', 'Thomas', 'Nicolas', 'Antoine'];
        $lastNames = ['Martin', 'Bernard', 'Dubois', 'Thomas', 'Petit', 'Robert', 'Richard', 'Durand'];
        $cities = ['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille', 'Toulouse', 'Nice', 'Nantes'];
        
        $firstName = $firstNames[($userId - 1000) % count($firstNames)];
        $lastName = $lastNames[($userId - 1000) % count($lastNames)];
        $age = rand(21, 40);
        $city = $cities[($userId - 1000) % count($cities)];
          $gender = (in_array($firstName, ['Emma', 'Léa', 'Manon', 'Julie', 'Camille'])) ? 'women' : 'men';
        $profilePic = "https://source.unsplash.com/featured/300x400?face,$gender&sig=$userId";
        
        return (object)[
            'id' => $userId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'age' => $age,
            'location' => $city,
            'profile_pic' => $profilePic,
            'is_fake' => true
        ];
    }
    
    private function generateFakeMessages($userId) {
        $messages = [];
        $messageContents = [
            'Salut ! Comment vas-tu ?',
            'Qu\'est-ce que tu fais dans la vie ?',
            'Tu habites dans quelle partie de la ville ?',
            'J\'adore ton profil, on a plein de points communs !',
            'Tu as des hobbies particuliers ?',
            'Tu aimes sortir le weekend ?',
            'J\'adore le cinéma, et toi ?',
            'Tu préfères les chats ou les chiens ?',
            'Tu as voyagé récemment ?',
            'C\'est cool qu\'on ait matché !',
            'Tu as une cuisine préférée ?',
            'Quel genre de musique écoutes-tu ?',
            'Tu fais du sport régulièrement ?',
            'J\'aimerais bien te rencontrer un de ces jours',
            'Tu connais des endroits sympa dans le coin ?',
            'Tu as vu le dernier film Marvel ?',
            'Tu préfères Netflix ou les sorties ciné ?',
            'Tu es plutôt jour ou nuit ?',
            'Quelle est ta saison préférée ?',
            'Tu es déjà allé(e) à l\'étranger ?'
        ];
        
        $userResponses = [
            'Ça va bien et toi ?',
            'Je suis développeur web, et toi ?',
            'J\'habite au centre-ville, c\'est pratique',
            'Merci ! J\'ai aussi remarqué qu\'on a des intérêts similaires',
            'J\'aime beaucoup la photographie et la randonnée',
            'Oui, j\'aime bien sortir avec des amis',
            'J\'adore le cinéma aussi ! Quel genre de films tu préfères ?',
            'Je suis plutôt chien, mais les chats sont cool aussi',
            'Je suis allé en Espagne le mois dernier, c\'était génial',
            'Oui, je suis content(e) qu\'on ait matché aussi !',
            'J\'adore la cuisine italienne',
            'J\'écoute principalement du rock et de l\'électro',
            'Je vais à la salle 3 fois par semaine',
            'Pourquoi pas, ça pourrait être sympa',
            'Il y a un nouveau bar à cocktails qui vient d\'ouvrir',
            'Pas encore, mais il est sur ma liste',
            'J\'aime les deux, mais Netflix est plus pratique',
            'Définitivement nuit, je suis un oiseau de nuit',
            'J\'adore l\'automne, les couleurs sont magnifiques',
            'Oui, j\'ai visité plusieurs pays européens'
        ];        
        $count = rand(3, 15);
        
        $startDate = strtotime('-' . rand(1, 7) . ' days');
        
        for($i = 0; $i < $count; $i++) {
            $senderId = ($i % 2 == 0) ? $userId : $_SESSION['user_id'];
            $senderFirstName = ($senderId == $userId) ? $this->generateFakeUser($userId)->first_name : 'Vous';
            $senderProfilePic = ($senderId == $userId) ? $this->generateFakeUser($userId)->profile_pic : (isset($_SESSION['user_profile_pic']) ? $_SESSION['user_profile_pic'] : 'default.jpg');
            
            $messageContent = ($senderId == $userId) ? $messageContents[$i % count($messageContents)] : $userResponses[$i % count($userResponses)];
            
            $messageTime = date('Y-m-d H:i:s', $startDate + ($i * rand(5, 60) * 60));
            
            $messages[] = (object)[
                'id' => 10000 + $i,
                'sender_id' => $senderId,
                'receiver_id' => ($senderId == $userId) ? $_SESSION['user_id'] : $userId,
                'message' => $messageContent,
                'is_read' => 1,
                'created_at' => $messageTime,
                'sender_first_name' => $senderFirstName,
                'sender_profile_pic' => $senderProfilePic
            ];        }
        
        usort($messages, function($a, $b) {
            return strtotime($a->created_at) - strtotime($b->created_at);
        });
        
        return $messages;
    }
    
    private function generateAutoReply($userId, $userMessage) {
        $replies = [
            'Merci pour ton message !',
            'C\'est intéressant ce que tu dis',
            'Je comprends tout à fait',
            'Super, merci de partager ça',
            'Ah d\'accord, je vois',
            'Ça me fait plaisir de discuter avec toi',
            'Tu as l\'air vraiment sympa',
            'On devrait se rencontrer un de ces jours',
            'Je suis curieux(se) d\'en savoir plus sur toi',
            'Qu\'est-ce que tu aimes faire comme activités ?',
            'Tu habites dans quel quartier ?',
            'Tu fais quoi dans la vie ?',
            'Ça fait longtemps que tu es sur cette appli ?',
            'Tu as prévu quelque chose ce weekend ?',
            'J\'aimerais bien continuer cette conversation en personne'
        ];
        
        $reply = $replies[array_rand($replies)];
        
      
        $lowerUserMessage = strtolower($userMessage);
        if(strpos($lowerUserMessage, 'bonjour') !== false || strpos($lowerUserMessage, 'salut') !== false || strpos($lowerUserMessage, 'coucou') !== false) {
            $reply = 'Salut ! Comment vas-tu aujourd\'hui ?';
        } elseif(strpos($lowerUserMessage, 'ça va') !== false || strpos($lowerUserMessage, 'comment vas') !== false) {
            $reply = 'Je vais très bien merci ! Et toi ?';
        } elseif(strpos($lowerUserMessage, 'rencontr') !== false || strpos($lowerUserMessage, 'voir') !== false || strpos($lowerUserMessage, 'boire un verre') !== false) {
            $reply = 'J\'adorerais te rencontrer ! Tu es libre quand ?';
        } elseif(strpos($lowerUserMessage, 'téléphone') !== false || strpos($lowerUserMessage, 'numéro') !== false || strpos($lowerUserMessage, 'appel') !== false) {
            $reply = 'Mon numéro est le 06 XX XX XX XX, n\'hésite pas à m\'appeler !';
        }
        
        $fakeUser = $this->generateFakeUser($userId);
        
        return [
            'id' => rand(10000, 99999),
            'sender_id' => $userId,
            'sender_first_name' => $fakeUser->first_name,
            'sender_profile_pic' => $fakeUser->profile_pic,
            'message' => $reply,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
}
?>
