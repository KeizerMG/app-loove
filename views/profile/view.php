<div class="container py-8">
    <!-- Success message notification -->
    <?php if(isset($_SESSION['success_message'])): ?>
    <div class="bg-success bg-opacity-10 text-success p-4 rounded-md mb-6">
        <?= $_SESSION['success_message'] ?>
    </div>
    <?php unset($_SESSION['success_message']); endif; ?>
    
    <!-- Error message notification -->
    <?php if(isset($_GET['error'])): ?>
    <div class="bg-danger bg-opacity-10 text-danger p-4 rounded-md mb-6">
        <?php
            switch($_GET['error']) {
                case 'upload_failed':
                    echo 'Failed to upload profile picture.';
                    break;
                case 'invalid_filetype':
                    echo 'Invalid file type. Please upload JPG, PNG, or GIF images.';
                    break;
                default:
                    echo 'An error occurred.';
            }
        ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Left sidebar with profile picture -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="profile-image-container mb-4">
                    <?php if(isset($profile['profile_picture']) && !empty($profile['profile_picture'])): ?>
                        <img src="<?= APP_URL . '/' . $profile['profile_picture'] ?>" alt="Profile picture" class="rounded-full w-full max-w-[250px] h-auto mx-auto">
                    <?php else: ?>
                        <div class="default-profile-image mx-auto">
                            <i class="fas fa-user-circle text-8xl text-gray"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h1 class="text-2xl font-bold"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h1>
                <p class="text-lg text-gray"><?= $age ?> years old</p>
                <p class="mb-4"><?= htmlspecialchars($user['gender']) ?>, <?= htmlspecialchars($user['sexual_orientation']) ?></p>
                
                <?php if(isset($profile['location']) && !empty($profile['location'])): ?>
                    <p class="flex items-center justify-center text-gray mb-4">
                        <i class="fas fa-map-marker-alt mr-2"></i> 
                        <?= htmlspecialchars($profile['location']) ?>
                    </p>
                <?php endif; ?>
                
                <a href="<?= APP_URL ?>/profile/edit" class="btn btn-outline w-full">Edit Profile</a>
            </div>
        </div>
        
        <!-- Main content area -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">About Me</h2>
                <div class="border-t pt-4">
                    <?php if(isset($profile['bio']) && !empty($profile['bio'])): ?>
                        <p><?= nl2br(htmlspecialchars($profile['bio'])) ?></p>
                    <?php else: ?>
                        <p class="text-gray italic">No bio provided yet. <a href="<?= APP_URL ?>/profile/edit" class="text-secondary">Add one now!</a></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Looking For</h2>
                <div class="border-t pt-4">
                    <?php if(isset($profile['relationship_type']) && !empty($profile['relationship_type'])): ?>
                        <div class="badge bg-secondary text-white px-4 py-2 rounded-full">
                            <?= ucfirst(htmlspecialchars($profile['relationship_type'])) ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray italic">No preferences set. <a href="<?= APP_URL ?>/profile/edit" class="text-secondary">Set them now!</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional custom styling */
.default-profile-image {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    background-color: #f0f2fa;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
