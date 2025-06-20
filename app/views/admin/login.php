<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Loove - Connexion</title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo BASEURL; ?>/img/favicon.png" type="image/png">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --admin-primary: #6366F1;
            --admin-primary-dark: #4F46E5;
            --admin-secondary: #10B981;
            --admin-accent: #F59E0B;
            --admin-error: #EF4444;
            --admin-success: #22C55E;
            --admin-surface: #FFFFFF;
            --admin-background: #F8FAFC;
            --admin-text-primary: #1F2937;
            --admin-text-secondary: #6B7280;
            --admin-border: #E5E7EB;
            --admin-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --admin-shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 226, 0.3) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .admin-login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
        }

        .admin-login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: var(--admin-shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .admin-login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--admin-primary), var(--admin-secondary), var(--admin-accent));
        }

        .admin-login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .admin-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3); }
            50% { box-shadow: 0 8px 32px rgba(99, 102, 241, 0.6); }
            100% { box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3); }
        }

        .admin-login-header h1 {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .admin-login-header p {
            color: var(--admin-text-secondary);
            font-size: 1rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--admin-text-primary);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            border: 2px solid var(--admin-border);
            border-radius: 12px;
            background: var(--admin-surface);
            color: var(--admin-text-primary);
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }

        .form-control.is-invalid {
            border-color: var(--admin-error);
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .password-input-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--admin-text-secondary);
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: var(--admin-primary);
        }

        .invalid-feedback {
            color: var(--admin-error);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .btn-admin-primary {
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-admin-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-admin-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.4);
        }

        .btn-admin-primary:hover::before {
            left: 100%;
        }

        .btn-admin-primary:active {
            transform: translateY(0);
        }

        .admin-login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--admin-border);
        }

        .admin-login-footer a {
            color: var(--admin-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .admin-login-footer a:hover {
            color: var(--admin-primary-dark);
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            border: none;
            position: relative;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--admin-error);
            border-left: 4px solid var(--admin-error);
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--admin-success);
            border-left: 4px solid var(--admin-success);
        }

        .features-grid {
            margin-top: 2rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            text-align: center;
        }

        .feature-item {
            padding: 1rem;
            background: rgba(99, 102, 241, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .feature-item i {
            font-size: 1.5rem;
            color: var(--admin-primary);
            margin-bottom: 0.5rem;
        }

        .feature-item span {
            font-size: 0.8rem;
            color: var(--admin-text-secondary);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .admin-login-card {
                padding: 2rem 1.5rem;
            }

            .admin-login-header h1 {
                font-size: 1.75rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }

        /* Loading animation */
        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="admin-login-header">
                <div class="admin-logo">
                    <i class="fas fa-crown"></i>
                </div>
                <h1>Administration Loove</h1>
                <p>Connectez-vous pour accéder au panneau d'administration</p>
            </div>

            <?php if(isset($_SESSION['admin_message'])) : ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASEURL; ?>/adminAuth" method="POST" class="admin-login-form" id="loginForm">
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email administrateur
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $data['email']; ?>"
                        placeholder="admin@loove.com"
                        autocomplete="email"
                    >
                    <?php if(!empty($data['email_err'])) : ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-times-circle"></i> <?php echo $data['email_err']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Mot de passe
                    </label>
                    <div class="password-input-group">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                            value="<?php echo $data['password']; ?>"
                            placeholder="••••••••"
                            autocomplete="current-password"
                        >
                        <i class="fas fa-eye toggle-password" toggle="#password"></i>
                    </div>
                    <?php if(!empty($data['password_err'])) : ?>
                        <div class="invalid-feedback">
                            <i class="fas fa-times-circle"></i> <?php echo $data['password_err']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-admin-primary" id="submitBtn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Connexion sécurisée</span>
                    </button>
                </div>
            </form>

            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <span>Sécurisé</span>
                    </div>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <span>Analytics</span>
                    </div>
                </div>
                <div class="feature-item">
                    <i class="fas fa-users-cog"></i>
                    <div>
                        <span>Gestion</span>
                    </div>
                </div>
            </div>

            <div class="admin-login-footer">
                <a href="<?php echo BASEURL; ?>">
                    <i class="fas fa-arrow-left"></i> Retour au site principal
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.querySelector('.toggle-password');
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const passwordInput = document.querySelector(this.getAttribute('toggle'));
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    }
                });
            }

            // Form submission with loading state
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // Re-enable after 3 seconds in case of error
                setTimeout(function() {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 3000);
            });

            // Auto-focus on first input
            const emailInput = document.getElementById('email');
            if (emailInput && !emailInput.value) {
                emailInput.focus();
            }

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                    const inputs = Array.from(form.querySelectorAll('input'));
                    const currentIndex = inputs.indexOf(e.target);
                    const nextInput = inputs[currentIndex + 1];
                    
                    if (nextInput) {
                        nextInput.focus();
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
</body>
</html>
