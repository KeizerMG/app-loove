<div class="container py-8">
    <?php if($welcome): ?>
    <div class="bg-success bg-opacity-10 text-success p-6 rounded-lg mb-8 text-center">
        <h2 class="text-2xl font-bold mb-2">Welcome to Loove!</h2>
        <p>Your account has been created successfully. Complete your profile to start connecting with others.</p>
    </div>
    <?php endif; ?>
    
    <!-- Error messages -->
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
                <form action="<?= APP_URL ?>/profile/upload-photo" method="POST" enctype="multipart/form-data" class="mb-4">
                    <div class="profile-image-container mb-4">
                        <?php if(isset($profile['profile_picture']) && !empty($profile['profile_picture'])): ?>
                            <img src="<?= APP_URL . '/' . $profile['profile_picture'] ?>" alt="Profile picture" class="rounded-full w-full max-w-[250px] h-auto mx-auto">
                        <?php else: ?>
                            <div class="default-profile-image mx-auto">
                                <i class="fas fa-user-circle text-8xl text-gray"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="profile_picture" class="btn btn-outline w-full">
                            <i class="fas fa-camera mr-2"></i> Change Photo
                        </label>
                        <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" onchange="this.form.submit()">
                    </div>
                </form>
                
                <div class="text-gray text-sm">
                    <p class="mb-2">Visible to everyone:</p>
                    <ul class="text-left pl-6 list-disc">
                        <li>Your first name</li>
                        <li>Your age</li>
                        <li>Your profile picture</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Main content area with edit forms -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Edit Your Profile</h2>
                
                <form action="<?= APP_URL ?>/profile/edit" method="POST">
                    <!-- Basic info section -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-3">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" disabled>
                                <small class="text-gray">To change your name, contact support</small>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" disabled>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bio section -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-3">About You</h3>
                        <div class="form-group">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea id="bio" name="bio" class="form-control" rows="5" placeholder="Tell others about yourself..."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                            <small class="text-gray">Maximum 500 characters</small>
                        </div>
                    </div>
                    
                    <!-- Location and preferences -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-3">Location & Preferences</h3>
                        
                        <div class="form-group mb-4">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" id="location" name="location" class="form-control" placeholder="City, Country" value="<?= htmlspecialchars($profile['location'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Looking For</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-2">
                                <label class="relationship-option">
                                    <input type="radio" name="relationship_type" value="friendship" <?= (isset($profile['relationship_type']) && $profile['relationship_type'] === 'friendship') ? 'checked' : '' ?>>
                                    <div class="relationship-box">
                                        <i class="fas fa-user-friends"></i>
                                        <span>Friendship</span>
                                    </div>
                                </label>
                                
                                <label class="relationship-option">
                                    <input type="radio" name="relationship_type" value="casual" <?= (isset($profile['relationship_type']) && $profile['relationship_type'] === 'casual') ? 'checked' : '' ?>>
                                    <div class="relationship-box">
                                        <i class="fas fa-heart"></i>
                                        <span>Casual</span>
                                    </div>
                                </label>
                                
                                <label class="relationship-option">
                                    <input type="radio" name="relationship_type" value="serious" <?= (isset($profile['relationship_type']) && $profile['relationship_type'] === 'serious') ? 'checked' : '' ?>>
                                    <div class="relationship-box">
                                        <i class="fas fa-infinity"></i>
                                        <span>Serious</span>
                                    </div>
                                </label>
                                
                                <label class="relationship-option">
                                    <input type="radio" name="relationship_type" value="marriage" <?= (isset($profile['relationship_type']) && $profile['relationship_type'] === 'marriage') ? 'checked' : '' ?>>
                                    <div class="relationship-box">
                                        <i class="fas fa-ring"></i>
                                        <span>Marriage</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 justify-end">
                        <a href="<?= APP_URL ?>/profile" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.default-profile-image {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    background-color: #f0f2fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.relationship-option {
    position: relative;
}

.relationship-option input {
    display: none;
}

.relationship-box {
    border: 2px solid #e1e1e1;
    border-radius: var(--radius-md);
    padding: 1rem 0.5rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.relationship-box i {
    display: block;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: var(--dark);
}

.relationship-option input:checked + .relationship-box {
    border-color: var(--secondary);
    background-color: rgba(108, 99, 255, 0.1);
}

.relationship-option input:checked + .relationship-box i {
    color: var(--secondary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for bio
    const bioTextarea = document.getElementById('bio');
    if (bioTextarea) {
        bioTextarea.addEventListener('input', function() {
            const maxLength = 500;
            const currentLength = this.value.length;
            
            if (currentLength > maxLength) {
                this.value = this.value.substring(0, maxLength);
            }
            
            const remainingChars = maxLength - this.value.length;
            const smallElement = this.nextElementSibling;
            
            if (smallElement) {
                smallElement.textContent = `${remainingChars} characters remaining`;
                
                if (remainingChars < 50) {
                    smallElement.classList.add('text-warning');
                } else {
                    smallElement.classList.remove('text-warning');
                }
            }
        });
    }
});
</script>
