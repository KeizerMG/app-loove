<?php
require_once 'controllers/Controller.php';
require_once 'models/User.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function showLogin() {
        // If already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
            return;
        }
        
        $this->render('auth/login', [
            'title' => 'Login - Loove',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function login() {
        // Check if already logged in
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
            return;
        }

        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate CSRF token if implemented
            
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $rememberMe = isset($_POST['remember_me']);
            
            // Validation
            $errors = [];
            
            if (empty($email) || empty($password)) {
                $errors[] = "Email and password are required";
            }
            
            if (empty($errors)) {
                // Find user by email
                $user = $this->userModel->findByEmail($email);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Check account status
                    if ($user['account_status'] !== 'active') {
                        $errors[] = "Your account is not active. Please contact support.";
                    } else {
                        // Set session variables
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['first_name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role'];
                        
                        // Handle "remember me" functionality if checked
                        if ($rememberMe) {
                            $this->setRememberMeToken($user['id']);
                        }
                        
                        // Success message
                        $_SESSION['success_message'] = "Welcome back, {$user['first_name']}!";
                        
                        // Redirect to home page or dashboard
                        $this->redirect('/');
                        return;
                    }
                } else {
                    $errors[] = "Invalid email or password";
                }
            }
            
            // If we get here, login failed
            $this->render('auth/login', [
                'title' => 'Login - Loove',
                'errors' => $errors,
                'email' => $email
            ]);
            return;
        }
        
        // Display login form (GET request)
        $this->render('auth/login', [
            'title' => 'Login - Loove',
            'success_message' => $_SESSION['success_message'] ?? null
        ]);
        
        // Clear success message after displaying
        if (isset($_SESSION['success_message'])) {
            unset($_SESSION['success_message']);
        }
    }
    
    public function showRegister() {
        // If already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
            return;
        }
        
        $this->render('auth/register', [
            'title' => 'Register - Loove',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $dateOfBirth = trim($_POST['date_of_birth']);
            $gender = trim($_POST['gender']);
            $sexualOrientation = trim($_POST['sexual_orientation']);

            // Check if the email already exists
            $existingUser = $this->userModel->findByEmail($email); // Ensure $this->userModel is not null
            if ($existingUser) {
                $this->redirect('/register?error=email_taken');
                return;
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create the user
            $userId = $this->userModel->create([
                'email' => $email,
                'password' => $hashedPassword,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'date_of_birth' => $dateOfBirth,
                'gender' => $gender,
                'sexual_orientation' => $sexualOrientation,
                'role' => 1, // Regular user
                'account_status' => 'active'
            ]);

            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $this->redirect('/dashboard');
            } else {
                $this->redirect('/register?error=registration_failed');
            }
        } else {
            $this->render('auth/register', ['title' => 'Register']);
        }
    }
    
    public function logout() {
        // Clear session data
        session_destroy();
        $this->redirect('/login');
    }
    
    // Helper method for "remember me" functionality
    private function setRememberMeToken($userId) {
        $selector = bin2hex(random_bytes(16));
        $token = random_bytes(32);
        $hashedToken = hash('sha256', $token);
        
        // Set cookie expiration to 30 days
        $expires = new DateTime('NOW');
        $expires->modify('+30 days');
        
        // Store in database
        $this->userModel->saveRememberToken($userId, $selector, $hashedToken, $expires->format('Y-m-d H:i:s'));
        
        // Set cookie
        $cookieValue = $selector . ':' . bin2hex($token);
        setcookie('remember_me', $cookieValue, $expires->getTimestamp(), '/', '', false, true);
    }
}
?>
