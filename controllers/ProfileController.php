<?php
require_once 'controllers/Controller.php';
require_once 'models/Profile.php';
require_once 'models/User.php';

class ProfileController extends Controller {
    private $profileModel;
    private $userModel;
    
    public function __construct() {
        $this->profileModel = new Profile();
        $this->userModel = new User();
        
        // Check if user is logged in for all profile actions
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login?error=auth_required');
            exit;
        }
    }
    
    public function viewProfile() {
        $userId = $_SESSION['user_id'];
        
        // Get user data with profile
        $userData = $this->userModel->getById($userId);
        $profileData = $this->profileModel->getByUserId($userId);
        
        if (!$userData) {
            $this->redirect('/?error=user_not_found');
            return;
        }
        
        // Calculate age from date of birth
        $dob = new DateTime($userData['date_of_birth']);
        $now = new DateTime();
        $age = $now->diff($dob)->y;
        
        $this->render('profile/view', [
            'title' => 'My Profile - Loove',
            'user' => $userData,
            'profile' => $profileData,
            'age' => $age
        ]);
    }
    
    public function showEditForm() {
        $userId = $_SESSION['user_id'];
        
        // Get user data with profile
        $userData = $this->userModel->getById($userId);
        $profileData = $this->profileModel->getByUserId($userId);
        
        if (!$userData) {
            $this->redirect('/?error=user_not_found');
            return;
        }
        
        // Check for welcome parameter
        $welcome = isset($_GET['welcome']) && $_GET['welcome'] == '1';
        
        $this->render('profile/edit', [
            'title' => 'Edit Profile - Loove',
            'user' => $userData,
            'profile' => $profileData,
            'welcome' => $welcome
        ]);
    }
    
    public function updateProfile() {
        $userId = $_SESSION['user_id'];
        
        // Validate CSRF token if implemented
        // if (!$this->validateCSRF()) {
        //    $this->redirect('/profile/edit?error=invalid_token');
        //    return;
        // }
        
        // Get form data
        $bio = htmlspecialchars($_POST['bio'] ?? '');
        $location = htmlspecialchars($_POST['location'] ?? '');
        $relationshipType = $_POST['relationship_type'] ?? null;
        
        // Update profile data
        $profileData = [
            'bio' => $bio,
            'location' => $location,
            'relationship_type' => $relationshipType
        ];
        
        // Update or create profile
        $profile = $this->profileModel->getByUserId($userId);
        
        if ($profile) {
            $this->profileModel->updateByUserId($userId, $profileData);
        } else {
            $profileData['user_id'] = $userId;
            $this->profileModel->create($profileData);
        }
        
        // Update user data if needed
        // (add any user data updates here if you want to allow updating name, etc.)
        
        $_SESSION['success_message'] = "Profile updated successfully!";
        $this->redirect('/profile');
    }
    
    public function uploadProfilePhoto() {
        $userId = $_SESSION['user_id'];
        
        // Check if file was uploaded
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            $this->redirect('/profile/edit?error=upload_failed');
            return;
        }
        
        $file = $_FILES['profile_picture'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->redirect('/profile/edit?error=invalid_filetype');
            return;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $uploadDir = 'assets/uploads/profiles/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $destination = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Update profile with new image
            $this->profileModel->updateByUserId($userId, ['profile_picture' => $destination]);
            $_SESSION['success_message'] = "Profile picture updated successfully!";
            $this->redirect('/profile');
        } else {
            $this->redirect('/profile/edit?error=upload_failed');
        }
    }
}
?>
