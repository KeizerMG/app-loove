<?php
class Message {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Récupérer toutes les conversations d'un utilisateur
    public function getConversations($userId) {
        // Version simplifiée qui évite les erreurs SQL
        $this->db->query('
            SELECT 
                u.id, 
                u.first_name, 
                u.last_name, 
                u.profile_pic,
                TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) as age,
                MAX(m.message) as last_message,
                MAX(m.created_at) as last_message_time,
                MAX(m.sender_id) as last_message_sender,
                SUM(CASE WHEN m.sender_id = u.id AND m.receiver_id = :user_id AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count
            FROM matches match_table
            JOIN users u ON (match_table.user_id_1 = u.id AND match_table.user_id_1 != :user_id) 
                         OR (match_table.user_id_2 = u.id AND match_table.user_id_2 != :user_id)
            LEFT JOIN messages m ON (m.sender_id = :user_id AND m.receiver_id = u.id) 
                                OR (m.sender_id = u.id AND m.receiver_id = :user_id)
            WHERE match_table.user_id_1 = :user_id OR match_table.user_id_2 = :user_id
            GROUP BY u.id
            ORDER BY last_message_time DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        
        try {
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Erreur dans getConversations: ' . $e->getMessage());
            return [];
        }
    }

    // Récupérer les messages entre deux utilisateurs
    public function getMessagesBetween($userId, $otherUserId) {
        $this->db->query('
            SELECT 
                m.*,
                u_sender.first_name as sender_first_name,
                u_sender.profile_pic as sender_profile_pic
            FROM messages m
            JOIN users u_sender ON m.sender_id = u_sender.id
            WHERE (m.sender_id = :user_id AND m.receiver_id = :other_user_id)
               OR (m.sender_id = :other_user_id AND m.receiver_id = :user_id)
            ORDER BY m.created_at ASC
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':other_user_id', $otherUserId);
        
        try {
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Erreur dans getMessagesBetween: ' . $e->getMessage());
            return [];
        }
    }

    // Envoyer un message
    public function sendMessage($senderId, $receiverId, $message) {
        try {
            // Vérifier d'abord si un match existe entre ces utilisateurs
            $this->db->query('
                SELECT id FROM matches 
                WHERE (user_id_1 = :sender_id AND user_id_2 = :receiver_id)
                   OR (user_id_1 = :receiver_id AND user_id_2 = :sender_id)
            ');
            
            $this->db->bind(':sender_id', $senderId);
            $this->db->bind(':receiver_id', $receiverId);
            
            $match = $this->db->single();
            
            // Si aucun match n'existe, créer un match automatiquement
            if (!$match) {
                $this->db->query('
                    INSERT INTO matches (user_id_1, user_id_2) 
                    VALUES (:user_id_1, :user_id_2)
                ');
                
                $this->db->bind(':user_id_1', min($senderId, $receiverId)); // Pour assurer la cohérence
                $this->db->bind(':user_id_2', max($senderId, $receiverId));
                
                if (!$this->db->execute()) {
                    error_log('Erreur lors de la création du match');
                    return false;
                }
            }
            
            // Maintenant insérer le message
            $this->db->query('INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)');
            
            $this->db->bind(':sender_id', $senderId);
            $this->db->bind(':receiver_id', $receiverId);
            $this->db->bind(':message', $message);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Erreur dans sendMessage: ' . $e->getMessage());
            return false;
        }
    }

    // Marquer les messages comme lus
    public function markAsRead($userId, $otherUserId) {
        try {
            $this->db->query('UPDATE messages SET is_read = 1 WHERE sender_id = :other_user_id AND receiver_id = :user_id AND is_read = 0');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':other_user_id', $otherUserId);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Erreur dans markAsRead: ' . $e->getMessage());
            return false;
        }
    }

    // Compter les messages non lus
    public function countUnreadMessages($userId) {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM messages WHERE receiver_id = :user_id AND is_read = 0');
            
            $this->db->bind(':user_id', $userId);
            
            $result = $this->db->single();
            return $result->count ?? 0;
        } catch (Exception $e) {
            error_log('Erreur dans countUnreadMessages: ' . $e->getMessage());
            return 0;
        }
    }

    // Récupérer le dernier message entre deux utilisateurs
    public function getLastMessageBetween($userId1, $userId2) {
        try {
            $this->db->query('
                SELECT * FROM messages
                WHERE (sender_id = :user_id_1 AND receiver_id = :user_id_2)
                   OR (sender_id = :user_id_2 AND receiver_id = :user_id_1)
                ORDER BY created_at DESC
                LIMIT 1
            ');
            
            $this->db->bind(':user_id_1', $userId1);
            $this->db->bind(':user_id_2', $userId2);
            
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Erreur dans getLastMessageBetween: ' . $e->getMessage());
            return null;
        }
    }

    // Compter les messages non lus d'un utilisateur spécifique
    public function countUnreadMessagesFrom($senderId, $receiverId) {
        try {
            $this->db->query('
                SELECT COUNT(*) as count 
                FROM messages 
                WHERE sender_id = :sender_id 
                  AND receiver_id = :receiver_id 
                  AND is_read = 0
            ');
            
            $this->db->bind(':sender_id', $senderId);
            $this->db->bind(':receiver_id', $receiverId);
            
            $result = $this->db->single();
            return $result->count ?? 0;
        } catch (Exception $e) {
            error_log('Erreur dans countUnreadMessagesFrom: ' . $e->getMessage());
            return 0;
        }
    }
}
