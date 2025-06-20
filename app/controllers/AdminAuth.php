<?php
class AdminAuth extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    
    public function index() {
        
        if(isset($_SESSION['admin_id'])) {
            redirect('adminDashboard');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
           
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
                'title' => 'Administration - Connexion'
            ];

            
            if(empty($data['email'])) {
                $data['email_err'] = 'Veuillez entrer votre email';
            }

            
            if(empty($data['password'])) {
                $data['password_err'] = 'Veuillez entrer votre mot de passe';
            }

            
            $user = $this->userModel->findUserByEmail($data['email']);
            if(!$user) {
                $data['email_err'] = 'Utilisateur non trouvé';
            } else if(!$user->is_admin) {
                $data['email_err'] = 'Vous n\'avez pas les droits d\'administration';
            }

            if(empty($data['email_err']) && empty($data['password_err'])) {
              
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                if($loggedInUser && $loggedInUser->is_admin) {
                 
                    $this->createAdminSession($loggedInUser);
                    redirect('adminDashboard'); 
                } else {
                    $data['password_err'] = 'Mot de passe incorrect';
                    $this->view('admin/login', $data);
                }
            } else {
                $this->view('admin/login', $data);
            }
        } else {
            
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
                'title' => 'Administration - Connexion'
            ];

           
            $this->view('admin/login', $data);
        }
    }

    
    private function createAdminSession($user) {
        $_SESSION['admin_id'] = $user->id;
        $_SESSION['admin_name'] = $user->first_name . ' ' . $user->last_name;
        $_SESSION['admin_email'] = $user->email;
        $_SESSION['is_admin'] = true;
        
    }

    
    public function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['is_admin']);
        
        session_destroy();
        redirect('adminAuth');
    }
}
?>
       
