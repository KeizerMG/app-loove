<?php
require_once 'models/Model.php';

class UserMatch extends Model {
    protected $table = 'matches';
    
    public function __construct() {
        parent::__construct();
        
        // Create matches table if it doesn't exist
        $this->createMatchesTableIfNotExists();
    }
    
    private function createMatchesTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS matches (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            matched_user_id INT NOT NULL,
            status ENUM('like', 'pass', 'match') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (matched_user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_match (user_id, matched_user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->db->query($sql);
    }
    
    public function createLike($userId, $likedUserId) {
        return $this->db->insert($this->table, [
            'user_id' => $userId,
            'matched_user_id' => $likedUserId,
            'status' => 'like'
        ]);
    }
    
    public function createPass($userId, $passedUserId) {
        return $this->db->insert($this->table, [
            'user_id' => $userId,
            'matched_user_id' => $passedUserId,
            'status' => 'pass'
        ]);
    }
    
    public function checkMutualMatch($user1Id, $user2Id) {
        // Check if user2 has already liked user1
        $mutualLike = $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE user_id = :user2Id AND matched_user_id = :user1Id AND status = 'like'",
            ['user1Id' => $user1Id, 'user2Id' => $user2Id]
        );
        
        // If there's a mutual like, update both records to "match" status
        if ($mutualLike) {
            // Update user1's like to match
            $this->db->update(
                $this->table,
                ['status' => 'match'],
                "user_id = :user1Id AND matched_user_id = :user2Id",
                ['user1Id' => $user1Id, 'user2Id' => $user2Id]
            );
            
            // Update user2's like to match
            $this->db->update(
                $this->table,
                ['status' => 'match'],
                "user_id = :user2Id AND matched_user_id = :user1Id",
                ['user1Id' => $user1Id, 'user2Id' => $user2Id]
            );
            
            return true;
        }
        
        return false;
    }
    
    public function getInteractedUserIds($userId) {
        $result = $this->db->fetchAll(
            "SELECT matched_user_id FROM {$this->table} WHERE user_id = :userId",
            ['userId' => $userId]
        );
        
        return array_map(function($row) {
            return $row['matched_user_id'];
        }, $result);
    }
    
    public function getMatches($userId) {
        $sql = "SELECT m.*, 
                u.first_name, u.last_name, u.gender, u.date_of_birth,
                p.bio, p.location, p.profile_picture
            FROM {$this->table} m
            JOIN users u ON m.matched_user_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE m.user_id = :userId AND m.status = 'match'
            ORDER BY m.created_at DESC";
            
        return $this->db->fetchAll($sql, ['userId' => $userId]);
    }
    
    public function checkIsMatched($user1Id, $user2Id) {
        // Check if users have a mutual match
        $match = $this->db->fetch(
            "SELECT * FROM {$this->table} 
            WHERE user_id = :user1Id AND matched_user_id = :user2Id AND status = 'match'",
            ['user1Id' => $user1Id, 'user2Id' => $user2Id]
        );
        
        return !empty($match);
    }
}
?>
