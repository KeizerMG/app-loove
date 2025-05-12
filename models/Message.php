<?php
require_once 'models/Model.php';

class Message extends Model {
    protected $table = 'messages';
    
    public function __construct() {
        parent::__construct();
        
        
        $this->createMessagesTableIfNotExists();
    }
    
    private function createMessagesTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->db->query($sql);
    }
    
    public function sendMessage($senderId, $receiverId, $message) {
        return $this->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message
        ]);
    }
    
    public function getMessages($userId, $otherUserId) {
        return $this->db->fetchAll(
            "SELECT m.*, 
                CASE WHEN m.sender_id = :userId THEN 'sent' ELSE 'received' END AS message_type
            FROM {$this->table} m
            WHERE (m.sender_id = :userId AND m.receiver_id = :otherUserId)
               OR (m.sender_id = :otherUserId AND m.receiver_id = :userId)
            ORDER BY m.created_at ASC",
            ['userId' => $userId, 'otherUserId' => $otherUserId]
        );
    }
    
    public function markAsRead($senderId, $receiverId) {
        return $this->db->update(
            $this->table,
            ['is_read' => true],
            "sender_id = :senderId AND receiver_id = :receiverId AND is_read = FALSE",
            ['senderId' => $senderId, 'receiverId' => $receiverId]
        );
    }
    
    public function getConversations($userId) {
        $sql = "SELECT 
                u.id, 
                u.first_name, 
                u.last_name,
                p.profile_picture,
                m.message,
                m.created_at,
                m.is_read,
                CASE WHEN m.sender_id = :userId THEN true ELSE false END as sent_by_me,
                (SELECT COUNT(*) FROM {$this->table} WHERE sender_id = u.id AND receiver_id = :userId AND is_read = FALSE) as unread_count
            FROM (
                SELECT 
                    CASE 
                        WHEN sender_id = :userId THEN receiver_id
                        ELSE sender_id
                    END as other_user_id,
                    MAX(created_at) as latest_msg_time
                FROM {$this->table}
                WHERE sender_id = :userId OR receiver_id = :userId
                GROUP BY other_user_id
            ) as latest_msgs
            JOIN users u ON latest_msgs.other_user_id = u.id
            LEFT JOIN profiles p ON u.id = p.user_id
            JOIN {$this->table} m ON (
                (m.sender_id = :userId AND m.receiver_id = u.id) OR 
                (m.sender_id = u.id AND m.receiver_id = :userId)
            ) AND m.created_at = latest_msgs.latest_msg_time
            ORDER BY latest_msgs.latest_msg_time DESC";
        
        return $this->db->fetchAll($sql, ['userId' => $userId]);
    }
    
    public function getUnreadCount($userId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as count FROM {$this->table} WHERE receiver_id = :userId AND is_read = FALSE",
            ['userId' => $userId]
        );
        
        return $result ? $result['count'] : 0;
    }
}
?>
