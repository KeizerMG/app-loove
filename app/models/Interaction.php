<?php
class Interaction {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Ajouter une interaction (like, dislike, superlike)
    public function addInteraction($userId, $targetUserId, $type) {
        // D'abord, vérifions si une interaction existe déjà et la mettons à jour si c'est le cas
        $this->db->query('SELECT * FROM user_interactions WHERE user_id = :user_id AND target_user_id = :target_user_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':target_user_id', $targetUserId);
        $existingInteraction = $this->db->single();

        if ($existingInteraction) {
            // Mettre à jour l'interaction existante
            $this->db->query('UPDATE user_interactions SET interaction_type = :type, created_at = NOW() WHERE id = :id');
            $this->db->bind(':type', $type);
            $this->db->bind(':id', $existingInteraction->id);
        } else {
            // Créer une nouvelle interaction
            $this->db->query('INSERT INTO user_interactions (user_id, target_user_id, interaction_type) VALUES (:user_id, :target_user_id, :type)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':target_user_id', $targetUserId);
            $this->db->bind(':type', $type);
        }

        // Exécuter la requête
        if($this->db->execute()) {
            // Si c'est un like ou un superlike, vérifier s'il y a un match
            if($type == 'like' || $type == 'superlike') {
                return $this->checkForMatch($userId, $targetUserId);
            }
            return true;
        } else {
            return false;
        }
    }

    // Vérifier s'il y a un match (l'autre utilisateur a déjà liké cet utilisateur)
    private function checkForMatch($userId, $targetUserId) {
        $this->db->query('SELECT * FROM user_interactions WHERE user_id = :target_user_id AND target_user_id = :user_id AND (interaction_type = "like" OR interaction_type = "superlike")');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':target_user_id', $targetUserId);
        $otherUserLike = $this->db->single();

        // Si l'autre utilisateur a déjà liké cet utilisateur, créer un match
        if($otherUserLike) {
            $this->db->query('INSERT INTO matches (user_id_1, user_id_2) VALUES (:user_id_1, :user_id_2) ON DUPLICATE KEY UPDATE created_at = NOW()');
            $this->db->bind(':user_id_1', min($userId, $targetUserId)); // On s'assure que user_id_1 < user_id_2 pour éviter les doublons
            $this->db->bind(':user_id_2', max($userId, $targetUserId));
            $this->db->execute();
            return ['status' => 'match', 'message' => 'Vous avez un nouveau match!'];
        }

        return ['status' => 'ok', 'message' => 'Interaction ajoutée avec succès'];
    }

    // Obtenir les utilisateurs que l'utilisateur actuel n'a pas encore vus (pour découvrir)
    public function getUndiscoveredUsers($userId, $limit = 20) {
        $this->db->query('
            SELECT u.*, 
                   TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) as age, 
                   p.bio, p.location, p.relationship_type 
            FROM users u
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE u.id != :user_id 
            AND u.id NOT IN (
                SELECT target_user_id 
                FROM user_interactions 
                WHERE user_id = :user_id
            )
            LIMIT :limit
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Obtenir les matchs d'un utilisateur
    public function getMatches($userId) {
        $this->db->query('
            SELECT u.*, 
                   TIMESTAMPDIFF(YEAR, u.birth_date, CURDATE()) as age, 
                   p.bio, p.location, p.relationship_type,
                   m.created_at as match_date
            FROM matches m
            JOIN users u ON (m.user_id_1 = u.id AND m.user_id_1 != :user_id) OR (m.user_id_2 = u.id AND m.user_id_2 != :user_id)
            LEFT JOIN profiles p ON u.id = p.user_id
            WHERE m.user_id_1 = :user_id OR m.user_id_2 = :user_id
            ORDER BY m.created_at DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
}
