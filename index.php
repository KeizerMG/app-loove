<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


define('APP_PATH', dirname(__FILE__));

require_once APP_PATH . '/config/config.php';


require_once APP_PATH . '/utils/Router.php';
require_once APP_PATH . '/utils/Database.php';


$loadedClasses = [];


foreach (glob(APP_PATH . "/models/*.php") as $filename) {

    $className = basename($filename, '.php');
    
    if ($className === 'Match' || in_array(strtolower($className), $loadedClasses)) {
        continue;
    }
    
 
    $loadedClasses[] = strtolower($className);
 
    require_once $filename;
}


foreach (glob(APP_PATH . "/controllers/*.php") as $filename) {
    require_once $filename;
}


$router = new Router();


$router->addRoute('GET', '/', 'HomeController', 'index');


$router->addRoute('GET', '/register', 'AuthController', 'showRegister');
$router->addRoute('POST', '/register', 'AuthController', 'register');
$router->addRoute('GET', '/login', 'AuthController', 'showLogin');
$router->addRoute('POST', '/login', 'AuthController', 'login');
$router->addRoute('GET', '/logout', 'AuthController', 'logout');


$router->addRoute('GET', '/profile', 'ProfileController', 'viewProfile');
$router->addRoute('GET', '/profile/edit', 'ProfileController', 'showEditForm');
$router->addRoute('POST', '/profile/edit', 'ProfileController', 'updateProfile');
$router->addRoute('POST', '/profile/upload-photo', 'ProfileController', 'uploadProfilePhoto');


$router->addRoute('GET', '/discover', 'MatchController', 'discoverProfiles');
$router->addRoute('POST', '/match/like', 'MatchController', 'likeProfile');
$router->addRoute('POST', '/match/pass', 'MatchController', 'passProfile');
$router->addRoute('GET', '/matches', 'MatchController', 'viewMatches');


$router->addRoute('GET', '/messages', 'MessageController', 'viewConversations');
$router->addRoute('GET', '/messages/conversation', 'MessageController', 'viewConversation');
$router->addRoute('POST', '/messages/send', 'MessageController', 'sendMessage');


$router->handleRequest();
?>
