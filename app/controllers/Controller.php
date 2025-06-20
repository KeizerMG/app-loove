<?php

class Controller {
 
    public function model($model) {
        
        require_once '../app/models/' . $model . '.php';

        return new $model();
    }

  
    public function view($view, $data = []) {
       
        if(file_exists('../app/views/' . $view . '.php')){
            require_once '../app/views/' . $view . '.php';
        } else {
           
            die('View does not exist');
        }
    }
    
    
    public function init() {
        
        if(isLoggedIn()) {
    
            if(file_exists('../app/models/Message.php')) {
                require_once '../app/models/Message.php';
                $messageModel = new Message();
                $unreadCount = $messageModel->countUnreadMessages($_SESSION['user_id']);
                $_SESSION['unread_messages_count'] = $unreadCount;
            } else {
                $_SESSION['unread_messages_count'] = 0;
            }
        }
    }
}
