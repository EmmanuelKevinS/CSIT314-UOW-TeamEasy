<?php
class AdminReportManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // for admin_daily_report.php
    public function getNewUsers() {
        $stmt = $this->conn->query("
            SELECT id, username, role, created_at
            FROM users
            WHERE DATE(created_at) = CURDATE()
        ");

        $users = [];
        while ($row = $stmt->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function getNewBookings() {
        $stmt = $this->conn->query("
            SELECT u.username AS homeowner, cp.name AS cleaner, cb.booking_date, cb.booking_time, cb.created_at
            FROM cleaner_bookings cb
            JOIN users u ON cb.homeowner_id = u.id
            JOIN cleaner_profiles cp ON cb.cleaner_id = cp.user_id
            WHERE DATE(cb.created_at) = CURDATE()
        ");

        $bookings = [];
        while ($row = $stmt->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }

    public function getDecidedBookings() {
        $stmt = $this->conn->query("
            SELECT u.username AS homeowner, cp.name AS cleaner, cb.booking_date, cb.booking_time, cb.status, cb.status_updated_at
            FROM cleaner_bookings cb
            JOIN users u ON cb.homeowner_id = u.id
            JOIN cleaner_profiles cp ON cb.cleaner_id = cp.user_id
            WHERE DATE(cb.status_updated_at) = CURDATE()
            AND cb.status IN ('confirmed', 'cancelled')
        ");

        $bookings = [];
        while ($row = $stmt->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }

    public function getCompletedJobs() {
        $stmt = $this->conn->query("
            SELECT u.username AS homeowner, cp.name AS cleaner, cb.booking_date, cb.booking_time, cb.status_updated_at
            FROM cleaner_bookings cb
            JOIN users u ON cb.homeowner_id = u.id
            JOIN cleaner_profiles cp ON cb.cleaner_id = cp.user_id
            WHERE DATE(cb.status_updated_at) = CURDATE()
            AND cb.status = 'completed'
        ");

        $jobs = [];
        while ($row = $stmt->fetch_assoc()) {
            $jobs[] = $row;
        }
        return $jobs;
    }
}
?>