<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set application path constant
define('APP_PATH', dirname(__FILE__));

// Load configuration
require_once APP_PATH . '/config/config.php';

// Load utilities
require_once APP_PATH . '/utils/Router.php';
require_once APP_PATH . '/utils/Database.php';

// Track loaded model classes to prevent duplicates
$loadedClasses = [];

// Autoload models - prevent duplicate class declarations
foreach (glob(APP_PATH . "/models/*.php") as $filename) {
    // Extract class name from filename
    $className = basename($filename, '.php');
    
    // Skip if we encounter Match.php (deprecated) or if class is already loaded
    if ($className === 'Match' || in_array(strtolower($className), $loadedClasses)) {
        continue;
    }
    
    // Add to loaded classes
    $loadedClasses[] = strtolower($className);
    
    // Load the file
    require_once $filename;
}

// Autoload controllers
foreach (glob(APP_PATH . "/controllers/*.php") as $filename) {
    require_once $filename;
}

// Initialize router
$router = new Router();

// Define routes
$router->addRoute('GET', '/', 'HomeController', 'index');

// Authentication routes
$router->addRoute('GET', '/register', 'AuthController', 'showRegister');
$router->addRoute('POST', '/register', 'AuthController', 'register');
$router->addRoute('GET', '/login', 'AuthController', 'showLogin');
$router->addRoute('POST', '/login', 'AuthController', 'login');
$router->addRoute('GET', '/logout', 'AuthController', 'logout');

// Profile routes
$router->addRoute('GET', '/profile', 'ProfileController', 'viewProfile');
$router->addRoute('GET', '/profile/edit', 'ProfileController', 'showEditForm');
$router->addRoute('POST', '/profile/edit', 'ProfileController', 'updateProfile');
$router->addRoute('POST', '/profile/upload-photo', 'ProfileController', 'uploadProfilePhoto');

// Match and discovery routes
$router->addRoute('GET', '/discover', 'MatchController', 'discoverProfiles');
$router->addRoute('POST', '/match/like', 'MatchController', 'likeProfile');
$router->addRoute('POST', '/match/pass', 'MatchController', 'passProfile');
$router->addRoute('GET', '/matches', 'MatchController', 'viewMatches');

// Message routes
$router->addRoute('GET', '/messages', 'MessageController', 'viewConversations');
$router->addRoute('GET', '/messages/conversation', 'MessageController', 'viewConversation');
$router->addRoute('POST', '/messages/send', 'MessageController', 'sendMessage');

// Handle the request
$router->handleRequest();
?>
