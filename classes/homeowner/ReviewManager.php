<?php
class ReviewManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // for leave_review.php
    public function getPendingReviewsForHomeowner($homeowner_id) {
        $stmt = $this->conn->prepare("
            SELECT cb.id_booking, cb.cleaner_id, cb.service_required, cb.booking_date, cb.booking_time, cb.address, u.username AS cleaner_name
            FROM cleaner_bookings cb
            JOIN users u ON cb.cleaner_id = u.id
            WHERE cb.homeowner_id = ?
            AND cb.status = 'completed'
            AND cb.rating IS NULL
            ORDER BY cb.booking_date DESC
        ");
        $stmt->bind_param("i", $homeowner_id);
        $stmt->execute();
        return $stmt->get_result();
    }


    public function getPendingReviews($homeowner_id) {
        $stmt = $this->conn->prepare("
            SELECT cb.*, cp.name AS cleaner_name
            FROM cleaner_bookings cb
            JOIN cleaner_profiles cp ON cb.cleaner_id = cp.user_id
            WHERE cb.homeowner_id = ?
            AND cb.status = 'completed'
            AND cb.rating IS NULL
            ORDER BY cb.booking_date DESC, 
                    FIELD(cb.booking_time, '8AM-11AM', '11AM-2PM', '2PM-5PM', '5PM-8PM')
        ");
        $stmt->bind_param("i", $homeowner_id);
        $stmt->execute();
        return $stmt->get_result();
    }


    public function submitReview($id_booking, $rating) {
        $stmt = $this->conn->prepare("
            UPDATE cleaner_bookings 
            SET rating = ?, status_updated_at = NOW()
            WHERE id_booking = ?
        ");

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $rating, $id_booking);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return true;
    }

    
}
?>