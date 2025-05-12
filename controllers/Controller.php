<?php
class Controller {
    protected function render($view, $data = []) {
        extract($data);

        ob_start();
        

        include APP_PATH . '/views/' . $view . '.php';
        

        $content = ob_get_clean();
        

        if (file_exists(APP_PATH . '/views/layouts/main.php')) {
            include APP_PATH . '/views/layouts/main.php';
        } else {
            echo $content;
        }
    }
    
    protected function redirect($url) {
        header('Location: ' . APP_URL . $url);
        exit;
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    protected function validateCSRF() {
        if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            return false;
        }
        return true;
    }
}
?>
