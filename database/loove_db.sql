-- Create database
CREATE DATABASE IF NOT EXISTS loove_db;
USE loove_db;

-- Users table - structure minimale pour l'authentification
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender ENUM('homme', 'femme', 'non-binaire', 'autre') NOT NULL,
    birth_date DATE NOT NULL,
    profile_pic VARCHAR(255) DEFAULT 'default.jpg',
    is_admin BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table de profils utilisateurs - détails supplémentaires
CREATE TABLE IF NOT EXISTS profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bio TEXT NULL,
    location VARCHAR(100) NULL,
    relationship_type ENUM('amitié', 'casual', 'sérieux', 'mariage') NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table pour les préférences utilisateur
CREATE TABLE IF NOT EXISTS user_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    min_age INT DEFAULT 18,
    max_age INT DEFAULT 99,
    preferred_gender VARCHAR(50) NULL,
    distance_max INT DEFAULT 50,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table pour les interactions entre utilisateurs (likes, dislikes, etc.)
CREATE TABLE IF NOT EXISTS user_interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    target_user_id INT NOT NULL,
    interaction_type ENUM('like', 'dislike', 'superlike') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (target_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_interaction (user_id, target_user_id)
);

-- Table pour les matchs (quand deux utilisateurs se likent mutuellement)
CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id_1 INT NOT NULL,
    user_id_2 INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id_1) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id_2) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_match (user_id_1, user_id_2)
);

-- Table pour les messages entre utilisateurs
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table pour les plans d'abonnement
CREATE TABLE IF NOT EXISTS subscription_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    duration_days INT NOT NULL,
    features TEXT NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table pour les abonnements des utilisateurs
CREATE TABLE IF NOT EXISTS user_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    start_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    end_date DATETIME NOT NULL,
    status ENUM('active', 'expired', 'cancelled') DEFAULT 'active',
    payment_id VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES subscription_plans(id) ON DELETE RESTRICT
);

-- Table pour les transactions de paiement
CREATE TABLE IF NOT EXISTS payment_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subscription_id INT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    payment_method VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(255) NULL,
    status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subscription_id) REFERENCES user_subscriptions(id) ON DELETE SET NULL
);

-- Table pour les jetons de réinitialisation de mot de passe
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(100) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_token (token(100))
);

-- Ajouter des colonnes pour la gestion admin et les revenus
ALTER TABLE users ADD COLUMN IF NOT EXISTS is_suspended BOOLEAN DEFAULT 0;
ALTER TABLE users ADD COLUMN IF NOT EXISTS is_banned BOOLEAN DEFAULT 0;
ALTER TABLE users ADD COLUMN IF NOT EXISTS banned_at DATETIME NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS ban_reason TEXT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login DATETIME NULL;

-- Table pour les signalements d'utilisateurs
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    reported_user_id INT NOT NULL,
    reason ENUM('spam', 'harassment', 'fake_profile', 'inappropriate_content', 'scam', 'other') NOT NULL,
    description TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_notes TEXT NULL,
    processed_by INT NULL,
    processed_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Table pour les logs d'activité admin
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    target_type VARCHAR(50) NOT NULL,
    target_id INT NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Ajout d'un utilisateur admin par défaut (mot de passe: admin123)
INSERT INTO users (first_name, last_name, email, password, gender, birth_date, is_admin) 
VALUES ('Admin', 'System', 'admin@loove.com', '$2y$10$jDvxYDYqQd4Y8SOOYGzsm.uRzIoVy.f92YQqQPwtI2K3.Y9nwqEu2', 'homme', '1990-01-01', 1)
ON DUPLICATE KEY UPDATE id=id;

-- Insertion des plans d'abonnement par défaut (en remplaçant les existants)
DELETE FROM subscription_plans;
INSERT INTO subscription_plans (name, description, price, duration_days, features, is_active) VALUES
('Loove Gratuit', 'Plan de base pour découvrir l\'application', 0.00, 36500, 'Profil de base;20 likes par jour;1 Super Like par semaine;Messages limités', 1),

('Loove Plus', 'Pour ceux qui veulent plus de visibilité', 9.99, 30, 'Tous les avantages du plan Gratuit;Likes illimités;3 Super Likes par jour;Voir qui vous a liké;1 Boost par mois;Pas de publicités', 1),

('Loove Gold', 'Notre option la plus populaire', 19.99, 30, 'Tous les avantages du plan Plus;5 Super Likes par jour;1 Boost par semaine;Priorité dans les résultats de recherche;Messages prioritaires;Fonctionnalité "Retour en arrière"', 1),

('Loove Platinum', 'L\'expérience de rencontre ultime', 29.99, 30, 'Tous les avantages du plan Gold;10 Super Likes par jour;Messages illimités;Mode incognito;Filtres de recherche avancés;Accès VIP aux événements;Support prioritaire 24/7', 1);
