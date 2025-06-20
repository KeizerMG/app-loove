<?php
class MatchModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Récupérer tous les matchs d'un utilisateur
    public function getUserMatches($userId) {
        $this->db->query('
            SELECT * FROM matches 
            WHERE user_id_1 = :user_id OR user_id_2 = :user_id
            ORDER BY created_at DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }

    // Vérifier si deux utilisateurs ont un match
    public function checkMatch($userId1, $userId2) {
        $this->db->query('
            SELECT * FROM matches 
            WHERE (user_id_1 = :user_id_1 AND user_id_2 = :user_id_2)
               OR (user_id_1 = :user_id_2 AND user_id_2 = :user_id_1)
            LIMIT 1
        ');
        
        $this->db->bind(':user_id_1', $userId1);
        $this->db->bind(':user_id_2', $userId2);
        
        $match = $this->db->single();
        
        return $match ? true : false;
    }

    // Créer un nouveau match
    public function createMatch($userId1, $userId2) {
        // S'assurer que user_id_1 est toujours le plus petit ID pour éviter les doublons
        $smallerId = min($userId1, $userId2);
        $largerId = max($userId1, $userId2);
        
        $this->db->query('
            INSERT INTO matches (user_id_1, user_id_2) 
            VALUES (:user_id_1, :user_id_2)
            ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP
        ');
        
        $this->db->bind(':user_id_1', $smallerId);
        $this->db->bind(':user_id_2', $largerId);
        
        return $this->db->execute();
    }
}
