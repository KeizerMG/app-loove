<?php
class Users extends Controller {
    private $userModel;
    private $profileModel;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->profileModel = $this->model('Profile');
    }
    
    // Méthode d'enregistrement
    public function register() {
        // Vérifier si l'utilisateur est déjà connecté
        if(isLoggedIn()) {
            redirect('pages/index');
        }
        
        // Vérifier si le formulaire est soumis
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traiter le formulaire
            
            // Nettoyer les données POST - Remplacer FILTER_SANITIZE_STRING
            $cleanPost = [];
            foreach($_POST as $key => $value) {
                if(is_string($value)) {
                    $cleanPost[$key] = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
                } else {
                    $cleanPost[$key] = $value;
                }
            }
            $_POST = $cleanPost;
            
            // Init data
            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'gender' => $_POST['gender'],
                'birth_date' => $_POST['birth_date'],
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'gender_err' => '',
                'birth_date_err' => ''
            ];
            
            // Validation
            if(empty($data['email'])) {
                $data['email_err'] = 'Veuillez entrer votre email';
            } else {
                // Check email
                if($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Cet email est déjà utilisé';
                }
            }
            
            if(empty($data['first_name'])) {
                $data['first_name_err'] = 'Veuillez entrer votre prénom';
            }
            
            if(empty($data['last_name'])) {
                $data['last_name_err'] = 'Veuillez entrer votre nom';
            }
            
            if(empty($data['password'])) {
                $data['password_err'] = 'Veuillez entrer un mot de passe';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Le mot de passe doit contenir au moins 6 caractères';
            }
            
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Veuillez confirmer votre mot de passe';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Les mots de passe ne correspondent pas';
                }
            }
            
            if(empty($data['gender'])) {
                $data['gender_err'] = 'Veuillez sélectionner votre genre';
            }
            
            if(empty($data['birth_date'])) {
                $data['birth_date_err'] = 'Veuillez entrer votre date de naissance';
            } else {
                // Vérifier que l'utilisateur a au moins 18 ans
                $birthDate = new DateTime($data['birth_date']);
                $today = new DateTime('today');
                $age = $birthDate->diff($today)->y;
                
                if($age < 18) {
                    $data['birth_date_err'] = 'Vous devez avoir au moins 18 ans pour vous inscrire';
                }
            }
            
            // Make sure errors are empty
            if(empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['email_err']) && 
               empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['gender_err']) && 
               empty($data['birth_date_err'])) {
                
                // Valider
                
                // Hasher le mot de passe
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Enregistrer l'utilisateur
                if($this->userModel->register($data)) {
                    // Set a special flag for registration success modal
                    $_SESSION['registration_success'] = true;
                    
                    flash('register_success', 'Vous êtes maintenant inscrit(e) et pouvez vous connecter');
                    redirect('users/login');
                } else {
                    die('Une erreur est survenue');
                }
                
            } else {
                // Charger la vue avec les erreurs
                $this->view('users/register', $data);
            }
            
        } else {
            // Init data
            $data = [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'gender' => '',
                'birth_date' => '',
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'gender_err' => '',
                'birth_date_err' => ''
            ];
            
            // Load view
            $this->view('users/register', $data);
        }
    }
    
    // Méthode de connexion
    public function login() {
        // Vérifier si l'utilisateur est déjà connecté
        if(isLoggedIn()) {
            redirect('pages/index');
        }
        
        // Vérifier si le formulaire est soumis
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traiter le formulaire
            
            // Nettoyer les données POST - Remplacer FILTER_SANITIZE_STRING
            $cleanPost = [];
            foreach($_POST as $key => $value) {
                if(is_string($value)) {
                    $cleanPost[$key] = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
                } else {
                    $cleanPost[$key] = $value;
                }
            }
            $_POST = $cleanPost;

            // Initialiser les données
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
                'title' => 'Connexion'
            ];
            
            // Valider l'email
            if(empty($data['email'])) {
                $data['email_err'] = 'Veuillez entrer votre email';
            }
            
            // Valider le mot de passe
            if(empty($data['password'])) {
                $data['password_err'] = 'Veuillez entrer votre mot de passe';
            }
            
            // Vérifier si l'utilisateur existe
            if($this->userModel->findUserByEmail($data['email'])) {
                // L'utilisateur existe
            } else {
                // L'utilisateur n'existe pas
                $data['email_err'] = 'Aucun utilisateur trouvé avec cet email';
            }
            
            // S'assurer qu'il n'y a pas d'erreurs
            if(empty($data['email_err']) && empty($data['password_err'])) {
                // Tout est bon, procéder à la connexion
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if($loggedInUser) {
                    // Vérifier si l'utilisateur est banni
                    $isBanned = isset($loggedInUser->is_banned) ? $loggedInUser->is_banned : 0;
                    if($isBanned) {
                        $banReason = isset($loggedInUser->ban_reason) ? $loggedInUser->ban_reason : 'Aucune raison spécifiée';
                        $data['email_err'] = 'Votre compte a été suspendu. Raison: ' . $banReason;
                        $this->view('users/login', $data);
                        return;
                    }
                    
                    // Vérifier si l'utilisateur est suspendu
                    $isSuspended = isset($loggedInUser->is_suspended) ? $loggedInUser->is_suspended : 0;
                    if($isSuspended) {
                        $data['email_err'] = 'Votre compte est temporairement suspendu. Contactez l\'administration.';
                        $this->view('users/login', $data);
                        return;
                    }
                    
                    // Mettre à jour la dernière connexion
                    $this->userModel->updateLastLogin($loggedInUser->id);
                    
                    // Créer la session
                    $this->createUserSession($loggedInUser);
                    
                    // Après la connexion, vérifier et activer un plan d'abonnement gratuit si l'utilisateur n'en a pas
                    $this->activateFreeSubscriptionIfNeeded($loggedInUser->id);
                    
                    redirect('discover');
                } else {
                    $data['password_err'] = 'Mot de passe incorrect';
                    $this->view('users/login', $data);
                }
            } else {
                // Charger la vue avec les erreurs
                $this->view('users/login', $data);
            }
        } else {
            // Initialiser le formulaire
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
                'title' => 'Connexion'
            ];
            
            // Charger la vue
            $this->view('users/login', $data);
        }
    }
    
    // Connexion automatique en tant qu'administrateur
    public function adminLogin() {
        // Vérifier si l'utilisateur est déjà connecté
        if(isLoggedIn()) {
            redirect('admin');
        }
        
        // Chercher l'utilisateur admin par défaut (email: admin@loove.com)
        $adminUser = $this->userModel->findUserByEmail('admin@loove.com');
        
        if($adminUser && $adminUser->is_admin) {
            // Créer la session pour l'administrateur
            $this->createUserSession($adminUser);
            
            // Rediriger vers le tableau de bord admin
            redirect('admin');
        } else {
            flash('login_error', 'Impossible de se connecter en tant qu\'administrateur. Veuillez contacter le support.', 'alert-loove-danger');
            redirect('users/login');
        }
    }

    // Méthode pour activer un plan d'abonnement gratuit si nécessaire
    private function activateFreeSubscriptionIfNeeded($userId) {
        try {
            // Vérifier si le modèle Subscription existe
            if(file_exists(APPROOT . '/models/Subscription.php')) {
                // Initialiser la connexion à la base de données si pas déjà fait
                if(!isset($this->db)) {
                    $this->db = new Database;
                }
                
                // Récupérer le plan gratuit directement avec la base de données
                $this->db->query('SELECT id FROM subscription_plans WHERE price = 0 AND is_active = 1 LIMIT 1');
                $freePlan = $this->db->single();
                
                if($freePlan && isset($freePlan->id)) {
                    // Charger le modèle Subscription
                    $subscriptionModel = $this->model('Subscription');
                    
                    // Vérifier si l'utilisateur a déjà un abonnement actif
                    if(!$subscriptionModel->hasActiveSubscription($userId)) {
                        // Activer le plan gratuit automatiquement
                        $subscriptionModel->createSubscription($userId, $freePlan->id);
                    }
                }
            }
        } catch (Exception $e) {
            // En cas d'erreur, journaliser mais ne pas interrompre le flux
            error_log('Erreur lors de l\'activation du plan gratuit: ' . $e->getMessage());
        }
    }

    // Créer la session utilisateur
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_profile_pic'] = $user->profile_pic;
    }

    // Déconnexion
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_profile_pic']);
        session_destroy();
        redirect('users/login');
    }
    
    // Méthode pour afficher le formulaire de demande de réinitialisation
    public function forgotPassword() {
        // Vérifier si l'utilisateur est déjà connecté
        if(isLoggedIn()) {
            redirect('pages/index');
        }
        
        // Vérifier si le formulaire est soumis
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traiter le formulaire
            
            // Nettoyer les données POST - Remplacer FILTER_SANITIZE_STRING
            $cleanPost = [];
            foreach($_POST as $key => $value) {
                if(is_string($value)) {
                    $cleanPost[$key] = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
                } else {
                    $cleanPost[$key] = $value;
                }
            }
            $_POST = $cleanPost;
            
            // Initialiser les données
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => '',
                'title' => 'Mot de passe oublié'
            ];
            
            // Valider l'email
            if(empty($data['email'])) {
                $data['email_err'] = 'Veuillez entrer votre email';
            } else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Format d\'email invalide';
            } else if(!$this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Aucun compte trouvé avec cet email';
            }
            
            // S'assurer qu'il n'y a pas d'erreurs
            if(empty($data['email_err'])) {
                // Générer un jeton de réinitialisation
                $token = $this->userModel->generatePasswordResetToken($data['email']);
                
                if($token) {
                    // Dans un environnement de production, on enverrait un email avec le lien
                    // Pour simplifier, on va juste afficher le lien
                    $resetLink = BASEURL . '/users/resetPassword/' . $token;
                    
                    // Stocker le lien dans une variable de session pour l'afficher dans la vue
                    $_SESSION['reset_link'] = $resetLink;
                    
                    flash('forgot_password_success', 'Un lien de réinitialisation a été créé');
                    redirect('users/forgotPasswordSuccess');
                } else {
                    flash('forgot_password_error', 'Une erreur est survenue lors de la génération du lien de réinitialisation', 'alert-loove-danger');
                    $this->view('users/forgotPassword', $data);
                }
            } else {
                // Charger la vue avec les erreurs
                $this->view('users/forgotPassword', $data);
            }
            
        } else {
            // Initialiser le formulaire
            $data = [
                'email' => '',
                'email_err' => '',
                'title' => 'Mot de passe oublié'
            ];
            
            // Charger la vue
            $this->view('users/forgotPassword', $data);
        }
    }
    
    // Méthode pour afficher la page de succès après demande de réinitialisation
    public function forgotPasswordSuccess() {
        if(!isset($_SESSION['reset_link'])) {
            redirect('users/forgotPassword');
        }
        
        $data = [
            'title' => 'Lien de réinitialisation envoyé',
            'reset_link' => $_SESSION['reset_link']
        ];
        
        // Supprimer le lien de la session après l'avoir affiché
        unset($_SESSION['reset_link']);
        
        $this->view('users/forgotPasswordSuccess', $data);
    }
    
    // Méthode pour afficher le formulaire de réinitialisation
    public function resetPassword($token = '') {
        // Vérifier si l'utilisateur est déjà connecté
        if(isLoggedIn()) {
            redirect('pages/index');
        }
        
        // Vérifier le jeton
        if(empty($token)) {
            flash('reset_error', 'Jeton de réinitialisation invalide', 'alert-loove-danger');
            redirect('users/login');
        }
        
        $tokenData = $this->userModel->verifyPasswordResetToken($token);
        
        if(!$tokenData) {
            flash('reset_error', 'Jeton de réinitialisation invalide ou expiré', 'alert-loove-danger');
            redirect('users/login');
        }
        
        // Vérifier si le formulaire est soumis
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traiter le formulaire
            
            // Nettoyer les données POST - Remplacer FILTER_SANITIZE_STRING
            $cleanPost = [];
            foreach($_POST as $key => $value) {
                if(is_string($value)) {
                    $cleanPost[$key] = htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
                } else {
                    $cleanPost[$key] = $value;
                }
            }
            $_POST = $cleanPost;
            
            // Initialiser les données
            $data = [
                'token' => $token,
                'token_data' => $tokenData,
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'password_err' => '',
                'confirm_password_err' => '',
                'title' => 'Réinitialiser votre mot de passe'
            ];
            
            // Valider le mot de passe
            if(empty($data['password'])) {
                $data['password_err'] = 'Veuillez entrer un mot de passe';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Le mot de passe doit contenir au moins 6 caractères';
            }
            
            // Valider la confirmation du mot de passe
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Veuillez confirmer votre mot de passe';
            } elseif($data['password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Les mots de passe ne correspondent pas';
            }
            
            // S'assurer qu'il n'y a pas d'erreurs
            if(empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hasher le mot de passe
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Réinitialiser le mot de passe
                if($this->userModel->resetPassword($tokenData->user_id, $data['password'])) {
                    flash('reset_success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
                    redirect('users/login');
                } else {
                    flash('reset_error', 'Une erreur est survenue lors de la réinitialisation de votre mot de passe', 'alert-loove-danger');
                    $this->view('users/resetPassword', $data);
                }
            } else {
                // Charger la vue avec les erreurs
                $this->view('users/resetPassword', $data);
            }
            
        } else {
            // Initialiser le formulaire
            $data = [
                'token' => $token,
                'token_data' => $tokenData,
                'password' => '',
                'confirm_password' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'title' => 'Réinitialiser votre mot de passe'
            ];
            
            // Charger la vue
            $this->view('users/resetPassword', $data);
        }
    }
}
?>
