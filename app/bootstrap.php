<?php
// Load Config
require_once 'config/config.php';

// Load Helpers
require_once 'helpers/url_helper.php';  // Load url_helper first
require_once 'helpers/session_helper.php';
require_once 'helpers/subscription_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    require_once 'libs/' . $className . '.php';
});

// Create necessary directories if they don't exist
$directories = [
    LOG_DIR,
    'c:/wampp/tmp', // Session directory
    dirname(__FILE__) . '/../public/img/profiles' // Profile pictures directory
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Set custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $error = date("Y-m-d H:i:s") . " [ERROR] $errstr in $errfile on line $errline\n";
    error_log($error, 3, LOG_DIR . '/error.log');
    
    if (ini_get('display_errors')) {
        echo "<div style='color:red; border:1px solid red; padding:10px;'>";
        echo "<h3>Une erreur s'est produite</h3>";
        echo "<p>$errstr</p>";
        echo "<p>Fichier: $errfile</p>";
        echo "<p>Ligne: $errline</p>";
        echo "</div>";
    }
    
    return true;
}

set_error_handler("customErrorHandler");

// Initialiser les contrôleurs de base
require_once 'controllers/Controller.php';
?>
