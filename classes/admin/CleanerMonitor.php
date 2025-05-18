<?php
class CleanerMonitor {
    private $conn;
    private $timeSlots;

    public function __construct($db) {
        $this->conn = $db;
        $this->timeSlots = [
            "8AM-11AM" => ["08:00", "11:00"],
            "11AM-2PM" => ["11:00", "14:00"],
            "2PM-5PM" => ["14:00", "17:00"],
            "5PM-8PM" => ["17:00", "    20:00"]
        ];
    }

    // for admin_monitor_cleaners.php
    public function getCleanersStatus($date, $currentTime) {
        $statuses = [];
        $cleaners = $this->conn->query("SELECT id, username FROM users WHERE role = 'cleaner'");

        while ($cleaner = $cleaners->fetch_assoc()) {
            $status = "Free";
            $cleanerId = $cleaner["id"];
            $username = $cleaner["username"];

            if ($this->isOnJob($cleanerId, $date, $currentTime)) {
                $status = "On Job";
            } elseif ($this->hasFutureBooking($cleanerId, $date)) {
                $status = "Has Bookings";
            }

            $statuses[] = [
                "id" => $cleanerId,
                "username" => $username,
                "status" => $status
            ];
        }

        return $statuses;
    }

    private function isOnJob($cleanerId, $date, $currentTime) {
        $stmt = $this->conn->prepare("SELECT booking_time FROM cleaner_bookings WHERE cleaner_id = ? AND booking_date = ?");
        $stmt->bind_param("is", $cleanerId, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $slot = $row["booking_time"];
            if (isset($this->timeSlots[$slot])) {
                [$start, $end] = $this->timeSlots[$slot];
                if ($currentTime >= $start && $currentTime <= $end) {
                    return true;
                }
            }
        }

        return false;
    }

    private function hasFutureBooking($cleanerId, $date) {
        $stmt = $this->conn->prepare("SELECT 1 FROM cleaner_bookings WHERE cleaner_id = ? AND booking_date > ? LIMIT 1");
        $stmt->bind_param("is", $cleanerId, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function getFutureBookings($cleanerId, $date) {
        $stmt = $this->conn->prepare("SELECT booking_date, booking_time FROM cleaner_bookings WHERE cleaner_id = ? AND booking_date > ? ORDER BY booking_date ASC");
        $stmt->bind_param("is", $cleanerId, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row["booking_date"] . " (" . $row["booking_time"] . ")";
        }
        return $bookings;
    }
}
?>