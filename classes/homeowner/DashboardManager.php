<?php
class DashboardManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // for homeowner.php
    public function getUnseenStatusUpdates($homeowner_id) {
        $stmt = $this->conn->prepare("
            SELECT cb.id_booking, cb.status, cb.booking_date, cb.booking_time, u.username
            FROM cleaner_bookings cb
            JOIN users u ON cb.cleaner_id = u.id
            WHERE cb.homeowner_id = ? 
              AND cb.status IN ('confirmed', 'cancelled', 'completed') 
              AND cb.homeowner_seen = 0
        ");
        $stmt->bind_param("i", $homeowner_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function markAllAsSeen($homeowner_id) {
        $stmt = $this->conn->prepare("
            UPDATE cleaner_bookings 
            SET homeowner_seen = 1 
            WHERE homeowner_id = ? 
              AND status IN ('confirmed', 'cancelled', 'completed')
        ");
        $stmt->bind_param("i", $homeowner_id);
        return $stmt->execute();
    }
}
?>