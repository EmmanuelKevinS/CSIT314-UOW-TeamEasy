<?php
class ShortlistManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }
    
    // for shortlist_cleaner.php
    public function addToShortlist($homeowner_id, $cleaner_id) {
        $check = $this->conn->prepare("SELECT 1 FROM cleaner_shortlists WHERE homeowner_id = ? AND cleaner_id = ?");
        $check->bind_param("ii", $homeowner_id, $cleaner_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $stmt = $this->conn->prepare("INSERT INTO cleaner_shortlists (homeowner_id, cleaner_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $homeowner_id, $cleaner_id);
            return $stmt->execute() ? "success" : "error";
        }

        return "exists";
    }

    public function isShortlisted($homeowner_id, $cleaner_id) {
        $stmt = $this->conn->prepare("SELECT 1 FROM cleaner_shortlists WHERE homeowner_id = ? AND cleaner_id = ?");
        $stmt->bind_param("ii", $homeowner_id, $cleaner_id);
        $stmt->execute();
        $stmt->store_result();

        $isShortlisted = $stmt->num_rows > 0;

        $stmt->close();
        return $isShortlisted;
    }


    // for remove_shortlist.php
    public function removeFromShortlist($homeowner_id, $cleaner_id) {
        $stmt = $this->conn->prepare("DELETE FROM cleaner_shortlists WHERE homeowner_id = ? AND cleaner_id = ?");
        $stmt->bind_param("ii", $homeowner_id, $cleaner_id);
        return $stmt->execute();
    }

    // for view_shortlisted_cleaners.php
    public function getShortlistedCleaners($homeowner_id) {
        // Fetch each shortlisted cleaner’s name, service type, service fee, and user_id
        $stmt = $this->conn->prepare("
            SELECT c.user_id, c.name, c.service_type, c.service_fee
            FROM cleaner_profiles c
            JOIN cleaner_shortlists cs ON cs.cleaner_id = c.user_id
            WHERE cs.homeowner_id = ?
        ");
        $stmt->bind_param("i", $homeowner_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    
}
?>