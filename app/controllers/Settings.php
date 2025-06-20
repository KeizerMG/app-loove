<?php
class Settings extends Controller {
    private $userModel;
    private $profileModel;
    private $subscriptionModel;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        
        $this->userModel = $this->model('User');
        $this->profileModel = $this->model('Profile');
        
        // Vérifier si le modèle Subscription existe
        if(file_exists(APPROOT . '/models/Subscription.php')) {
            $this->subscriptionModel = $this->model('Subscription');
        }
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $profile = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
        
        // Récupérer l'abonnement actif si le modèle existe
        $activeSubscription = null;
        if(isset($this->subscriptionModel)) {
            $activeSubscription = $this->subscriptionModel->getActiveSubscription($_SESSION['user_id']);
        }
        
        $data = [
            'title' => 'Paramètres',
            'user' => $user,
            'profile' => $profile,
            'active_subscription' => $activeSubscription,
            'current_password' => '',
            'new_password' => '',
            'confirm_password' => '',
            'current_password_err' => '',
            'new_password_err' => '',
            'confirm_password_err' => ''
        ];
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traiter le formulaire
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data['current_password'] = trim($_POST['current_password']);
            $data['new_password'] = trim($_POST['new_password']);
            $data['confirm_password'] = trim($_POST['confirm_password']);
            
            // Valider le mot de passe actuel
            if(empty($data['current_password'])) {
                $data['current_password_err'] = 'Veuillez entrer votre mot de passe actuel';
            } elseif(!password_verify($data['current_password'], $user->password)) {
                $data['current_password_err'] = 'Mot de passe incorrect';
            }
            
            // Valider le nouveau mot de passe
            if(empty($data['new_password'])) {
                $data['new_password_err'] = 'Veuillez entrer un nouveau mot de passe';
            } elseif(strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'Le mot de passe doit contenir au moins 6 caractères';
            }
            
            // Valider la confirmation du mot de passe
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Veuillez confirmer votre mot de passe';
            } elseif($data['new_password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Les mots de passe ne correspondent pas';
            }
            
            // Vérifier s'il n'y a pas d'erreurs
            if(empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                // Hasher le mot de passe
                $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                
                // Mettre à jour le mot de passe
                if($this->userModel->updatePassword($_SESSION['user_id'], $data['new_password'])) {
                    flash('settings_success', 'Votre mot de passe a été mis à jour avec succès', 'alert-loove-success');
                    redirect('settings');
                } else {
                    flash('settings_error', 'Une erreur est survenue lors de la mise à jour de votre mot de passe', 'alert-loove-danger');
                }
            }
        }
        
        $this->view('settings/index', $data);
    }

    public function notifications() {
        $data = [
            'title' => 'Paramètres de notifications',
            'email_notifications' => isset($_SESSION['email_notifications']) ? $_SESSION['email_notifications'] : true,
            'app_notifications' => isset($_SESSION['app_notifications']) ? $_SESSION['app_notifications'] : true,
            'marketing_emails' => isset($_SESSION['marketing_emails']) ? $_SESSION['marketing_emails'] : false
        ];
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traiter le formulaire
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data['email_notifications'] = isset($_POST['email_notifications']);
            $data['app_notifications'] = isset($_POST['app_notifications']);
            $data['marketing_emails'] = isset($_POST['marketing_emails']);
            
            // Enregistrer les préférences dans la session pour l'instant
            // Dans une vraie application, il faudrait les sauvegarder en base de données
            $_SESSION['email_notifications'] = $data['email_notifications'];
            $_SESSION['app_notifications'] = $data['app_notifications'];
            $_SESSION['marketing_emails'] = $data['marketing_emails'];
            
            flash('settings_success', 'Vos préférences de notifications ont été mises à jour', 'alert-loove-success');
            redirect('settings/notifications');
        }
        
        $this->view('settings/notifications', $data);
    }

    public function privacy() {
        $data = [
            'title' => 'Paramètres de confidentialité',
            'profile_visibility' => isset($_SESSION['profile_visibility']) ? $_SESSION['profile_visibility'] : 'public',
            'show_online_status' => isset($_SESSION['show_online_status']) ? $_SESSION['show_online_status'] : true,
            'show_last_active' => isset($_SESSION['show_last_active']) ? $_SESSION['show_last_active'] : true
        ];
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traiter le formulaire
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data['profile_visibility'] = $_POST['profile_visibility'];
            $data['show_online_status'] = isset($_POST['show_online_status']);
            $data['show_last_active'] = isset($_POST['show_last_active']);
            
            // Enregistrer les préférences dans la session pour l'instant
            $_SESSION['profile_visibility'] = $data['profile_visibility'];
            $_SESSION['show_online_status'] = $data['show_online_status'];
            $_SESSION['show_last_active'] = $data['show_last_active'];
            
            flash('settings_success', 'Vos paramètres de confidentialité ont été mis à jour', 'alert-loove-success');
            redirect('settings/privacy');
        }
        
        $this->view('settings/privacy', $data);
    }

    public function account() {
        $data = [
            'title' => 'Paramètres du compte',
            'email' => '',
            'email_err' => ''
        ];
        
        $this->view('settings/account', $data);
    }

    public function deleteAccount() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifier la confirmation
            if(isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'delete') {
                // Supprimer le compte
                if($this->userModel->deleteUser($_SESSION['user_id'])) {
                    // Déconnecter l'utilisateur
                    unset($_SESSION['user_id']);
                    unset($_SESSION['user_name']);
                    unset($_SESSION['user_email']);
                    session_destroy();
                    
                    flash('register_success', 'Votre compte a été supprimé avec succès', 'alert-loove-success');
                    redirect('users/login');
                } else {
                    flash('settings_error', 'Une erreur est survenue lors de la suppression de votre compte', 'alert-loove-danger');
                    redirect('settings/account');
                }
            } else {
                flash('settings_error', 'Veuillez confirmer la suppression de votre compte', 'alert-loove-danger');
                redirect('settings/account');
            }
        } else {
            redirect('settings/account');
        }
    }
}
