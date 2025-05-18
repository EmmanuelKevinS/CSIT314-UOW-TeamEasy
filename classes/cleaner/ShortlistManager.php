<?php
class ShortlistManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // for view_shortlist.php
    public function getShortlistCount($cleanerId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM cleaner_shortlists WHERE cleaner_id = ?");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }

    public function getShortlistDetails($cleanerId) {
        $stmt = $this->conn->prepare("
            SELECT hp.name, hp.address
            FROM cleaner_shortlists cs
            JOIN homeowner_profiles hp ON cs.homeowner_id = hp.user_id
            WHERE cs.cleaner_id = ?
        ");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>