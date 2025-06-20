<?php
class Database {
    // ...existing code...

    public function getUserStats() {
        $this->query('SELECT 
            COUNT(*) as total_users,
            SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as new_users_30_days,
            SUM(CASE WHEN last_login >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as active_users_7_days 
            FROM users');
        return $this->single();
    }

    // ...existing code...
}
?>