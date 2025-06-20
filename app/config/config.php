<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'loove_db');


define('BASEURL', 'http://localhost/Loove4');
define('SITENAME', 'Loove - Find Your Match');
define('APPROOT', dirname(dirname(__FILE__)));


define('LOG_DIR', dirname(dirname(dirname(__FILE__))) . '/logs');
if (!file_exists(LOG_DIR)) {
    mkdir(LOG_DIR, 0777, true);
}
ini_set('log_errors', 1);
ini_set('error_log', LOG_DIR . '/error.log');
?>
