<?php
class CleanerBookingManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // for manage_bookings.php
    public function updateBookingStatus($bookingId, $cleanerId, $action) {
        if (!in_array($action, ['accept', 'decline'])) return false;

        $status = $action === 'accept' ? 'confirmed' : 'cancelled';

        $stmt = $this->conn->prepare("
            UPDATE cleaner_bookings 
            SET status = ?, is_seen = 0 
            WHERE id_booking = ? AND cleaner_id = ?
        ");
        $stmt->bind_param("sii", $status, $bookingId, $cleanerId);
        return $stmt->execute();
    }

    public function getPendingBookings($cleanerId) {
        $stmt = $this->conn->prepare("
            SELECT cb.id_booking, cb.booking_date, cb.booking_time, cb.service_required, cb.address, hp.name
            FROM cleaner_bookings cb
            JOIN homeowner_profiles hp ON cb.homeowner_id = hp.user_id
            WHERE cb.cleaner_id = ? AND cb.status = 'pending'
            ORDER BY cb.booking_date ASC
        ");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // for completed_jobs.php
    public function markBookingCompleted($bookingId, $cleanerId, $bookingDate, $bookingTime) {
        $endTime = explode("-", $bookingTime)[1]; // e.g. "8AM-11AM" → "11AM"
        $datetimeString = $bookingDate . " " . $endTime;
        $endDateTime = DateTime::createFromFormat("Y-m-d gA", $datetimeString, new DateTimeZone("Asia/Singapore"));
        $now = new DateTime("now", new DateTimeZone("Asia/Singapore"));

        if ($endDateTime && $now >= $endDateTime) {
            $stmt = $this->conn->prepare("
                UPDATE cleaner_bookings
                SET status = 'completed', status_updated_at = NOW()
                WHERE id_booking = ? AND cleaner_id = ?
            ");
            $stmt->bind_param("ii", $bookingId, $cleanerId);
            $stmt->execute();

            // Get homeowner name
            $homeStmt = $this->conn->prepare("
                SELECT name FROM homeowner_profiles
                WHERE user_id = (SELECT homeowner_id FROM cleaner_bookings WHERE id_booking = ?)
            ");
            $homeStmt->bind_param("i", $bookingId);
            $homeStmt->execute();
            $homeStmt->bind_result($homeownerName);
            $homeStmt->fetch();
            $homeStmt->close();

            return $homeownerName;
        }

        return false;
    }

    public function getConfirmedBookings($cleanerId) {
        $stmt = $this->conn->prepare("
            SELECT cb.id_booking, cb.booking_date, cb.booking_time, cb.service_required, cb.address, hp.name
            FROM cleaner_bookings cb
            JOIN homeowner_profiles hp ON cb.homeowner_id = hp.user_id
            WHERE cb.cleaner_id = ? AND cb.status = 'confirmed'
            ORDER BY cb.booking_date ASC
        ");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // for service_match_history.php
    public function filterBookingHistory($cleanerId, $status, $service, $startDate, $endDate) {
        $query = "
            SELECT cb.*, hp.name AS homeowner_name
            FROM cleaner_bookings cb
            JOIN homeowner_profiles hp ON cb.homeowner_id = hp.user_id
            WHERE cb.cleaner_id = ?
        ";

        $params = [];
        $types = "i";

        if ($status !== "all") {
            $query .= " AND cb.status = ?";
            $params[] = $status;
            $types .= "s";
        }

        if (!empty($service)) {
            $query .= " AND cb.service_required LIKE ?";
            $params[] = "%$service%";
            $types .= "s";
        }

        if (!empty($startDate)) {
            $query .= " AND cb.booking_date >= ?";
            $params[] = $startDate;
            $types .= "s";
        }

        if (!empty($endDate)) {
            $query .= " AND cb.booking_date <= ?";
            $params[] = $endDate;
            $types .= "s";
        }

        $query .= "
            ORDER BY cb.booking_date DESC,
            FIELD(cb.booking_time, '8AM-11AM', '11AM-2PM', '2PM-5PM', '5PM-8PM')
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, $cleanerId, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }



/*
    public function getBookingHistory($cleanerId, $status = null) {
        $validFilters = ["pending", "confirmed", "completed", "cancelled"];
        $statusFilter = "";

        if (in_array($status, $validFilters)) {
            $statusFilter = "AND cb.status = ?";
        }

        $sql = "SELECT cb.*, u.username AS homeowner_name
                FROM cleaner_bookings cb
                JOIN users u ON cb.homeowner_id = u.id
                WHERE cb.cleaner_id = ? $statusFilter
                ORDER BY cb.booking_date DESC, cb.booking_time DESC";

        if ($statusFilter) {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $cleanerId, $status);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $cleanerId);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    */
}
?>