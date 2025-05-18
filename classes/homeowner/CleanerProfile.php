<?php
class CleanerProfile {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // for view_cleaner_profile.php
    public function getCleanerById($cleaner_id) {
        $stmt = $this->conn->prepare("
            SELECT * 
            FROM cleaner_profiles 
            WHERE user_id = ?
        ");
        $stmt->bind_param("i", $cleaner_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAverageRating($cleaner_id) {
        $stmt = $this->conn->prepare("
            SELECT AVG(rating) AS avg_rating 
            FROM cleaner_bookings 
            WHERE cleaner_id = ? AND rating IS NOT NULL
        ");
        $stmt->bind_param("i", $cleaner_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['avg_rating'] ?? null;
    }
}
?>