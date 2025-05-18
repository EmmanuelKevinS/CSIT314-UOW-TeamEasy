<?php
class BookingManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    //for book_cleaner.php
    public function getHomeownerAddress($homeowner_id) {
        $stmt = $this->conn->prepare("SELECT address FROM homeowner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $homeowner_id);
        $stmt->execute();
        $stmt->bind_result($address);
        $stmt->fetch();
        $stmt->close();
        return $address;
    }

    public function createBooking($homeowner_id, $cleaner_id, $service_required, $date, $time, $address) {
        $stmt = $this->conn->prepare("INSERT INTO cleaner_bookings (homeowner_id, cleaner_id, service_required, booking_date, booking_time, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $homeowner_id, $cleaner_id, $service_required, $date, $time, $address);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // for track_request.php
    public function getBookingsByHomeowner($homeowner_id) {
        $stmt = $this->conn->prepare("
            SELECT b.*, c.name AS cleaner_name
            FROM cleaner_bookings b
            JOIN cleaner_profiles c ON b.cleaner_id = c.user_id
            WHERE b.homeowner_id = ?
            ORDER BY b.booking_date DESC, b.booking_time DESC
        ");
        $stmt->bind_param("i", $homeowner_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // for service_usage_history.php
    public function filterBookings($homeowner_id, $status, $service, $start_date, $end_date) {
        $sql = "
            SELECT cb.*, u.username AS cleaner_name
            FROM cleaner_bookings cb
            JOIN users u ON cb.cleaner_id = u.id
            WHERE cb.homeowner_id = ?
        ";

        $params = [$homeowner_id];
        $types = "i";

        // Add filters dynamically
        if ($status !== 'all') {
            $sql .= " AND cb.status = ?";
            $params[] = $status;
            $types .= "s";
        }

        if (!empty($service)) {
            $sql .= " AND cb.service_required = ?";
            $params[] = $service;
            $types .= "s";
        }

        if (!empty($start_date)) {
            $sql .= " AND cb.booking_date >= ?";
            $params[] = $start_date;
            $types .= "s";
        }

        if (!empty($end_date)) {
            $sql .= " AND cb.booking_date <= ?";
            $params[] = $end_date;
            $types .= "s";
        }

        $sql .= " ORDER BY cb.booking_date DESC, 
                FIELD(cb.booking_time, '8AM-11AM', '11AM-2PM', '2PM-5PM', '5PM-8PM')";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }
    
}
?>