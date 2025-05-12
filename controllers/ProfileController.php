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
        
  
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login?error=auth_required');
            exit;
        }
    }
    
    public function viewProfile() {
        $userId = $_SESSION['user_id'];
        
       
        $userData = $this->userModel->getById($userId);
        $profileData = $this->profileModel->getByUserId($userId);
        
        if (!$userData) {
            $this->redirect('/?error=user_not_found');
            return;
        }
        
     
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
        
       
        $userData = $this->userModel->getById($userId);
        $profileData = $this->profileModel->getByUserId($userId);
        
        if (!$userData) {
            $this->redirect('/?error=user_not_found');
            return;
        }
        
        
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
        

        $bio = htmlspecialchars($_POST['bio'] ?? '');
        $location = htmlspecialchars($_POST['location'] ?? '');
        $relationshipType = $_POST['relationship_type'] ?? null;
        
       
        $profileData = [
            'bio' => $bio,
            'location' => $location,
            'relationship_type' => $relationshipType
        ];
        
        
        $profile = $this->profileModel->getByUserId($userId);
        
        if ($profile) {
            $this->profileModel->updateByUserId($userId, $profileData);
        } else {
            $profileData['user_id'] = $userId;
            $this->profileModel->create($profileData);
        }
        
      
        
        $_SESSION['success_message'] = "Profile updated successfully!";
        $this->redirect('/profile');
    }
    
    public function uploadProfilePhoto() {
        $userId = $_SESSION['user_id'];
        
       
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            $this->redirect('/profile/edit?error=upload_failed');
            return;
        }
        
        $file = $_FILES['profile_picture'];
        
       
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->redirect('/profile/edit?error=invalid_filetype');
            return;
        }
        
     
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $uploadDir = 'assets/uploads/profiles/';
        
       
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $destination = $uploadDir . $filename;
        
     
        if (move_uploaded_file($file['tmp_name'], $destination)) {
        
            $this->profileModel->updateByUserId($userId, ['profile_picture' => $destination]);
            $_SESSION['success_message'] = "Profile picture updated successfully!";
            $this->redirect('/profile');
        } else {
            $this->redirect('/profile/edit?error=upload_failed');
        }
    }
}
?>
