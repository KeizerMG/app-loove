<?php
require_once 'models/Model.php';

class Subscription extends Model {
    protected $table = 'subscriptions';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getByUserId($userId) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE user_id = :userId ORDER BY id DESC LIMIT 1", 
            ['userId' => $userId]
        );
    }
    
    public function getActiveByUserId($userId) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} 
            WHERE user_id = :userId 
            AND plan_type != 'basic' 
            AND payment_status = 'completed' 
            AND end_date > NOW() 
            ORDER BY id DESC LIMIT 1", 
            ['userId' => $userId]
        );
    }
    
    public function getSubscriptionHistory($userId) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
            WHERE user_id = :userId 
            ORDER BY start_date DESC", 
            ['userId' => $userId]
        );
    }
    
    public function isPremium($userId) {
        $subscription = $this->getActiveByUserId($userId);
        return !empty($subscription) && in_array($subscription['plan_type'], ['premium', 'gold']);
    }
    
    public function isGold($userId) {
        $subscription = $this->getActiveByUserId($userId);
        return !empty($subscription) && $subscription['plan_type'] === 'gold';
    }
    
    public function getUserPlan($userId) {
        $subscription = $this->getActiveByUserId($userId);
        if (empty($subscription)) {
            return 'basic';
        }
        return $subscription['plan_type'];
    }
}
?>
