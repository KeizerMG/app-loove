<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
        
        // Ajouter les colonnes manquantes si elles n'existent pas
        $this->addMissingColumns();
    }

    // Register user
    public function register($data) {
        $this->db->query('INSERT INTO users (first_name, last_name, email, password, gender, birth_date) VALUES(:first_name, :last_name, :email, :password, :gender, :birth_date)');
        
        // Bind values
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':birth_date', $data['birth_date']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if($row) {
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)) {
                return $row;
            }
        }
        
        return false;
    }

    // Mettre à jour la dernière connexion
    public function updateLastLogin($userId) {
        $this->db->query('UPDATE users SET last_login = NOW() WHERE id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Update core user info (first_name, last_name, profile_pic)
    public function updateUserCoreInfo($data){
        $sql = 'UPDATE users SET first_name = :first_name, last_name = :last_name';
        if (!empty($data['profile_pic']) && $data['profile_pic'] != $data['profile_pic_current']) {
            $sql .= ', profile_pic = :profile_pic';
        }
        $sql .= ' WHERE id = :user_id';
        
        $this->db->query($sql);
        
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':user_id', $data['user_id']);
        if (!empty($data['profile_pic']) && $data['profile_pic'] != $data['profile_pic_current']) {
            $this->db->bind(':profile_pic', $data['profile_pic']);
        }

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Mettre à jour le mot de passe
    public function updatePassword($userId, $newPassword) {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        
        $this->db->bind(':password', $newPassword);
        $this->db->bind(':id', $userId);
        
        return $this->db->execute();
    }
    
    // Supprimer un utilisateur
    public function deleteUser($userId) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        
        $this->db->bind(':id', $userId);
        
        return $this->db->execute();
    }

    // Générer un jeton de réinitialisation de mot de passe
    public function generatePasswordResetToken($email) {
        // Vérifier que l'utilisateur existe
        $user = $this->findUserByEmail($email);
        if(!$user) {
            return false;
        }
        
        // Générer un jeton aléatoire
        $token = bin2hex(random_bytes(32));
        
        // Expiration dans 1 heure
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);
        
        // Supprimer tout jeton existant pour cet utilisateur
        $this->db->query('DELETE FROM password_reset_tokens WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user->id);
        $this->db->execute();
        
        // Insérer le nouveau jeton
        $this->db->query('INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
        $this->db->bind(':user_id', $user->id);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires_at', $expiresAt);
        
        if($this->db->execute()) {
            return $token;
        } else {
            return false;
        }
    }
    
    // Vérifier si un jeton est valide
    public function verifyPasswordResetToken($token) {
        $this->db->query('SELECT prt.*, u.email 
                         FROM password_reset_tokens prt 
                         JOIN users u ON prt.user_id = u.id 
                         WHERE prt.token = :token AND prt.expires_at > NOW()');
        $this->db->bind(':token', $token);
        
        $result = $this->db->single();
        
        return $result ? $result : false;
    }
    
    // Réinitialiser le mot de passe
    public function resetPassword($userId, $newPassword) {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        
        $this->db->bind(':password', $newPassword);
        $this->db->bind(':id', $userId);
        
        // Exécuter
        if($this->db->execute()) {
            // Supprimer tous les jetons de réinitialisation pour cet utilisateur
            $this->db->query('DELETE FROM password_reset_tokens WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            return true;
        } else {
            return false;
        }
    }

    // Récupérer tous les utilisateurs pour l'admin
    public function getAllUsersForAdmin($limit = 20, $offset = 0) {
        $this->db->query('
            SELECT u.*, 
                   COUNT(DISTINCT us.id) as subscription_count
            FROM users u
            LEFT JOIN user_subscriptions us ON u.id = us.user_id AND us.status = "active"
            GROUP BY u.id
            ORDER BY u.created_at DESC
            LIMIT :limit OFFSET :offset
        ');
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    // Nouveaux utilisateurs aujourd'hui
    public function getNewUsersToday() {
        $this->db->query('SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()');
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    // Nouveaux utilisateurs ce mois
    public function getNewUsersThisMonth() {
        $this->db->query('
            SELECT COUNT(*) as count 
            FROM users 
            WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
        ');
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    // Utilisateurs des 30 derniers jours
    public function getUsersLast30Days() {
        $this->db->query('
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ');
        
        return $this->db->resultSet();
    }

    // Utilisateurs récents
    public function getRecentUsers($limit = 5) {
        $this->db->query('SELECT * FROM users ORDER BY created_at DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Compter les utilisateurs bannis
    public function getBannedUsersCount() {
        $this->db->query('SELECT COUNT(*) as count FROM users WHERE is_banned = 1');
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    // Bannir un utilisateur avec raison
    public function banUser($userId, $reason = '') {
        $this->db->query('
            UPDATE users 
            SET is_banned = 1, banned_at = NOW(), ban_reason = :reason 
            WHERE id = :id AND is_admin = 0
        ');
        $this->db->bind(':id', $userId);
        $this->db->bind(':reason', $reason);
        return $this->db->execute();
    }

    // Débannir un utilisateur
    public function unbanUser($userId) {
        $this->db->query('
            UPDATE users 
            SET is_banned = 0, banned_at = NULL, ban_reason = NULL 
            WHERE id = :id
        ');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    // Activer/Suspendre un utilisateur
    public function toggleUserStatus($userId) {
        // D'abord récupérer le statut actuel
        $this->db->query('SELECT is_suspended FROM users WHERE id = :id AND is_admin = 0');
        $this->db->bind(':id', $userId);
        $user = $this->db->single();
        
        if($user) {
            $newStatus = $user->is_suspended ? 0 : 1;
            $this->db->query('UPDATE users SET is_suspended = :status WHERE id = :id');
            $this->db->bind(':status', $newStatus);
            $this->db->bind(':id', $userId);
            return $this->db->execute();
        }
        
        return false;
    }

    // Statistiques des utilisateurs
    public function getUserStatistics() {
        $this->db->query('
            SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN is_admin = 1 THEN 1 ELSE 0 END) as admin_users,
                SUM(CASE WHEN is_suspended = 1 THEN 1 ELSE 0 END) as suspended_users,
                SUM(CASE WHEN is_banned = 1 THEN 1 ELSE 0 END) as banned_users,
                SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as new_users_30_days,
                SUM(CASE WHEN last_login >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as active_users_7_days
            FROM users
        ');
        
        return $this->db->single();
    }

    // Compter le total des utilisateurs
    public function getTotalUsers() {
        $this->db->query('SELECT COUNT(*) as count FROM users');
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    // Ajouter les colonnes manquantes
    private function addMissingColumns() {
        try {
            // Vérifier et ajouter is_suspended
            $this->db->query("SHOW COLUMNS FROM users LIKE 'is_suspended'");
            if(!$this->db->single()) {
                $this->db->query('ALTER TABLE users ADD COLUMN is_suspended BOOLEAN DEFAULT 0');
                $this->db->execute();
            }
            
            // Vérifier et ajouter is_banned
            $this->db->query("SHOW COLUMNS FROM users LIKE 'is_banned'");
            if(!$this->db->single()) {
                $this->db->query('ALTER TABLE users ADD COLUMN is_banned BOOLEAN DEFAULT 0');
                $this->db->execute();
            }
            
            // Vérifier et ajouter banned_at
            $this->db->query("SHOW COLUMNS FROM users LIKE 'banned_at'");
            if(!$this->db->single()) {
                $this->db->query('ALTER TABLE users ADD COLUMN banned_at DATETIME NULL');
                $this->db->execute();
            }
            
            // Vérifier et ajouter ban_reason
            $this->db->query("SHOW COLUMNS FROM users LIKE 'ban_reason'");
            if(!$this->db->single()) {
                $this->db->query('ALTER TABLE users ADD COLUMN ban_reason TEXT NULL');
                $this->db->execute();
            }
            
            // Vérifier et ajouter last_login
            $this->db->query("SHOW COLUMNS FROM users LIKE 'last_login'");
            if(!$this->db->single()) {
                $this->db->query('ALTER TABLE users ADD COLUMN last_login DATETIME NULL');
                $this->db->execute();
            }
        } catch (Exception $e) {
            error_log('Error adding missing columns: ' . $e->getMessage());
        }
    }
}
?>
