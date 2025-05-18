<?php
class AdminDashboardManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function getTotalUsers() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users");
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function getSuspendedUsers() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE status = 'suspended'");
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function getTotalCleaners() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'cleaner'");
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function getTotalHomeowners() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'homeowner'");
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function getTotalBookings() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM cleaner_bookings");
        return $result->fetch_assoc()['total'] ?? 0;
    }
}
?>