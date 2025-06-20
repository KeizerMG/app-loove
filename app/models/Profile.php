<?php
class Profile {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Create initial profile after registration
    public function createProfile($user_id) {
        $this->db->query('INSERT INTO profiles (user_id) VALUES(:user_id)');
        
        // Bind values
        $this->db->bind(':user_id', $user_id);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get profile by user ID
    public function getProfileByUserId($user_id) {
        $this->db->query('SELECT * FROM profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);

        $row = $this->db->single();

        return $row;
    }

    // Update profile
    public function updateProfile($data) {
        $this->db->query('UPDATE profiles SET bio = :bio, location = :location, relationship_type = :relationship_type WHERE user_id = :user_id');
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':relationship_type', $data['relationship_type']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
