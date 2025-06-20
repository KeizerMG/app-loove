<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background-size: 400% 400%;
        }
        
        .admin-login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .admin-login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .admin-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .admin-logo {
            background: linear-gradient(135deg, #667eea, #764ba2);
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2.25rem;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            position: relative;
        }
        
        .admin-logo::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.3;
            filter: blur(10px);
        }
        
        .admin-title {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .admin-subtitle {
            color: #6b7280;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 2rem;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            font-family: inherit;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            background: white;
            transform: translateY(-2px);
        }
        
        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }
        
        .form-error {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 12px;
            border-left: 4px solid #ef4444;
        }
        
        .btn-admin {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 1.25rem;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .btn-admin::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-admin:hover::before {
            left: 100%;
        }
        
        .btn-admin:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .btn-admin:active {
            transform: translateY(-1px);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .feature-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateY(-4px);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .feature-title {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .feature-desc {
            color: #6b7280;
            font-size: 0.8rem;
            line-height: 1.5;
        }
        
        .security-note {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .security-note i {
            color: #667eea;
            font-size: 1.5rem;
        }
        
        .security-text {
            font-size: 0.9rem;
            color: #4b5563;
            line-height: 1.5;
        }
        
        @media (max-width: 768px) {
            .admin-login-container {
                padding: 2rem;
                margin: 1rem;
                border-radius: 20px;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-title {
                font-size: 1.75rem;
            }
            
            .admin-logo {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .admin-login-container {
            animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        
        .feature-item {
            animation: fadeIn 1s ease forwards;
        }
        
        .feature-item:nth-child(1) { animation-delay: 0.2s; }
        .feature-item:nth-child(2) { animation-delay: 0.4s; }
        .feature-item:nth-child(3) { animation-delay: 0.6s; }
        .feature-item:nth-child(4) { animation-delay: 0.8s; }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-header">
            <div class="admin-logo">
                <i class="fas fa-crown"></i>
            </div>
            <h1 class="admin-title">Administration Loove</h1>
            <p class="admin-subtitle">Accès sécurisé au panneau d'administration</p>
        </div>

        <form action="<?php echo BASEURL; ?>/adminAuth" method="post">
            <div class="form-group">
                <label class="form-label">Adresse Email</label>
                <input type="email" 
                       name="email" 
                       class="form-control" 
                       placeholder="admin@loove.com"
                       value="<?php echo $data['email']; ?>"
                       required>
                <?php if(!empty($data['email_err'])): ?>
                    <div class="form-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo $data['email_err']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Mot de Passe</label>
                <input type="password" 
                       name="password" 
                       class="form-control" 
                       placeholder="••••••••••••"
                       required>
                <?php if(!empty($data['password_err'])): ?>
                    <div class="form-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo $data['password_err']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-admin">
                <i class="fas fa-sign-in-alt"></i>
                Se Connecter
            </button>
        </form>

        <div class="security-note">
            <i class="fas fa-shield-alt"></i>
            <div class="security-text">
                <strong>Zone Sécurisée</strong><br>
                Accès réservé exclusivement aux administrateurs autorisés. Toutes les connexions sont surveillées et enregistrées.
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="feature-title">Gestion Utilisateurs</div>
                <div class="feature-desc">Administrez les comptes, bannissements et modération</div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="feature-title">Analytics</div>
                <div class="feature-desc">Suivez les performances et statistiques détaillées</div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="feature-title">Abonnements</div>
                <div class="feature-desc">Gérez les plans premium et les paiements</div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-check"></i>
                </div>
                <div class="feature-title">Sécurité</div>
                <div class="feature-desc">Surveillance avancée et protection des données</div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        const btn = document.querySelector('.btn-admin');
        const form = btn.closest('form');
        
        form.addEventListener('submit', function(e) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion en cours...';
            btn.style.pointerEvents = 'none';
        });

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.feature-item').forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        item.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, index * 150);
                });
            }, 500);
        });
    </script>
</body>
</html>
