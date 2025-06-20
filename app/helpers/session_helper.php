<?php
// Vérifier et créer le répertoire de sessions si nécessaire
$sessionPath = 'c:/wampp/tmp';
if (!file_exists($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}

// Définir le chemin de sessions si nécessaire
if (!is_writable($sessionPath)) {
    // Utiliser un répertoire temporaire alternatif
    $sessionPath = sys_get_temp_dir();
}

// Configurer le répertoire de sessions
ini_set('session.save_path', $sessionPath);

// Démarrer la session seulement si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Flash message helper
function flash($name = '', $message = '', $class = 'alert-loove-info') {
    if(!empty($name)) {
        if(!empty($message) && empty($_SESSION[$name])) {
            if(!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            
            if(!empty($_SESSION[$name. '_class'])) {
                unset($_SESSION[$name. '_class']);
            }
            
            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
            echo '<div class="alert '. $class .' alert-dismissible" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name. '_class']);
        }
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Vérifier si l'utilisateur connecté est un admin
function isAdmin() {
    if(!isLoggedIn()) {
        return false;
    }
    
    // On peut stocker le statut admin en session ou le vérifier en base
    if(isset($_SESSION['is_admin'])) {
        return $_SESSION['is_admin'];
    }
    
    return false;
}

// Vérifier le statut de l'utilisateur (banni/suspendu)
function checkUserStatus() {
    if(!isLoggedIn()) {
        return true; // Pas connecté, pas de problème
    }
    
    // Vérifier en base de données le statut de l'utilisateur
    $db = new Database();
    $db->query('SELECT is_banned, is_suspended, ban_reason FROM users WHERE id = :id');
    $db->bind(':id', $_SESSION['user_id']);
    $user = $db->single();
    
    if($user) {
        // Si l'utilisateur est banni, le déconnecter
        if($user->is_banned) {
            // Détruire la session
            session_unset();
            session_destroy();
            
            // Rediriger avec un message
            header('Location: ' . BASEURL . '/users/login?banned=1&reason=' . urlencode($user->ban_reason ?? ''));
            exit;
        }
        
        // Si l'utilisateur est suspendu, on peut aussi le déconnecter ou afficher un avertissement
        if($user->is_suspended) {
            $_SESSION['user_suspended'] = true;
        }
    }
    
    return true;
}

// Appeler cette fonction au début de chaque page protégée
function requireLogin() {
    if(!isLoggedIn()) {
        redirect('users/login');
    }
    
    // Vérifier le statut de l'utilisateur
    checkUserStatus();
}
?>
