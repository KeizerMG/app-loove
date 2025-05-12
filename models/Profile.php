<?php
require_once 'models/Model.php';

class Profile extends Model {
    protected $table = 'profiles';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getByUserId($userId) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE user_id = :userId", 
            ['userId' => $userId]
        );
    }
    
    public function updateByUserId($userId, $data) {
        return $this->db->update(
            $this->table, 
            $data, 
            "user_id = :userId", 
            ['userId' => $userId]
        );
    }
    
    public function getUserWithProfile($userId) {
        return $this->db->fetch(
            "SELECT u.*, p.* FROM users u 
            LEFT JOIN {$this->table} p ON u.id = p.user_id 
            WHERE u.id = :userId", 
            ['userId' => $userId]
        );
    }
}
?>
