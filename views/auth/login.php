<div class="auth-container flex justify-center items-center py-16">
    <div class="auth-box bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Welcome Back</h1>
            <p class="text-gray">Sign in to continue to Loove</p>
        </div>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-danger bg-opacity-10 text-danger p-4 rounded-md mb-6">
                <?php
                    $error = $_GET['error'];
                    switch ($error) {
                        case 'missing_fields':
                            echo 'Please fill in all required fields.';
                            break;
                        case 'invalid_credentials':
                            echo 'Invalid email or password.';
                            break;
                        case 'invalid_token':
                            echo 'Invalid security token. Please try again.';
                            break;
                        default:
                            echo 'An error occurred. Please try again.';
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['message'])): ?>
            <div class="bg-success bg-opacity-10 text-success p-4 rounded-md mb-6">
                <?php
                    $message = $_GET['message'];
                    switch ($message) {
                        case 'registration_success':
                            echo 'Registration successful! Please login with your credentials.';
                            break;
                        case 'logged_out':
                            echo 'You have successfully logged out.';
                            break;
                        default:
                            echo $message;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <form action="<?= APP_URL ?>/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="form-group mb-4">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus>
            </div>
            
            <div class="form-group mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="form-label mb-0">Password</label>
                    <a href="<?= APP_URL ?>/forgot-password" class="text-sm text-secondary hover:underline">Forgot Password?</a>
                </div>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group mb-6">
                <label class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me" class="mr-2">
                    <span class="text-sm">Remember me for 30 days</span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary w-full">Sign In</button>
        </form>
        
        <div class="text-center mt-6">
            <p>Don't have an account? <a href="<?= APP_URL ?>/register" class="text-secondary hover:underline">Create account</a></p>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: calc(100vh - 200px);
}

.auth-box {
    transition: all 0.3s ease;
}

.auth-box:hover {
    transform: translateY(-5px);
}
</style>
