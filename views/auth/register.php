<div class="container py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Create Your Account</h2>
        
        <form action="<?= APP_URL ?>/register" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="form-group mb-4">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label">Gender</label>
                <div class="flex gap-4 mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="male" class="mr-2" required> Male
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="female" class="mr-2" required> Female
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="other" class="mr-2" required> Other
                    </label>
                </div>
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label">Sexual Orientation</label>
                <div class="flex flex-wrap gap-4 mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="sexual_orientation" value="heterosexual" class="mr-2" required> Heterosexual
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="sexual_orientation" value="homosexual" class="mr-2" required> Homosexual
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="sexual_orientation" value="bisexual" class="mr-2" required> Bisexual
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="sexual_orientation" value="other" class="mr-2" required> Other
                    </label>
                </div>
            </div>
            
            <div class="form-group mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="terms" class="mr-2" required>
                    <span>I agree to the <a href="<?= APP_URL ?>/terms" class="text-secondary">Terms of Service</a></span>
                </label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-full">Create Account</button>
            </div>
        </form>
        
        <div class="mt-4 text-center">
            <p>Already have an account? <a href="<?= APP_URL ?>/login" class="text-secondary">Sign In</a></p>
        </div>
    </div>
</div>
