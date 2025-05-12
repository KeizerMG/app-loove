<?php

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getPotentialMatches($userId, $excludeIds = [], $genderPreference = []) {
        $placeholders = [];
        $params = [];
        
    
        $params['userId'] = $userId;
        
    
        $excludePlaceholders = [];
        if (!empty($excludeIds)) {
            foreach ($excludeIds as $index => $id) {
                $paramName = "exclude{$index}";
                $excludePlaceholders[] = ":{$paramName}";
                $params[$paramName] = $id;
            }
        }
        
    
        $genderPlaceholders = [];
        if (!empty($genderPreference)) {
            foreach ($genderPreference as $index => $gender) {
                $paramName = "gender{$index}";
                $genderPlaceholders[] = ":{$paramName}";
                $params[$paramName] = $gender;
            }
        }
        
       
        $sql = "SELECT u.*, p.bio, p.location, p.profile_picture, p.relationship_type,
                    TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) AS age
                FROM users u
                LEFT JOIN profiles p ON u.id = p.user_id
                WHERE u.account_status = 'active'";
        
       
        if (!empty($excludePlaceholders)) {
            $sql .= " AND u.id NOT IN (" . implode(", ", $excludePlaceholders) . ")";
        }
        
        
        if (!empty($genderPlaceholders)) {
            $sql .= " AND u.gender IN (" . implode(", ", $genderPlaceholders) . ")";
        }
        
        $sql .= " ORDER BY u.created_at DESC LIMIT 50";
        
        return $this->db->fetchAll($sql, $params);
    }
}
