<?php
class CleanerDashboardManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // to make sure cleaner has set up their profile before entering dashboard
    public function getProfileByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM cleaner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // for cleaner.php
    public function getCleanerName($user_id) {
        $stmt = $this->conn->prepare("SELECT name FROM cleaner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row["name"];
        }
        return "Cleaner";
    }

    public function getUnseenBookings($cleaner_id) {
        $stmt = $this->conn->prepare("
            SELECT cb.booking_date, cb.booking_time, hp.name 
            FROM cleaner_bookings cb
            JOIN homeowner_profiles hp ON cb.homeowner_id = hp.user_id
            WHERE cb.cleaner_id = ? AND cb.is_seen = 0
            ORDER BY cb.booking_date ASC
        ");
        $stmt->bind_param("i", $cleaner_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function markBookingsAsSeen($cleaner_id) {
        $stmt = $this->conn->prepare("UPDATE cleaner_bookings SET is_seen = 1 WHERE cleaner_id = ?");
        $stmt->bind_param("i", $cleaner_id);
        return $stmt->execute();
    }
}
?>