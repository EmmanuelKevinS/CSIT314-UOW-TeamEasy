<?php
class ContactAdmin {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // for contact_admin.php
    public function sendMessage($homeowner_id, $subject, $message) {
        $stmt = $this->conn->prepare("
            INSERT INTO admin_messages (user_id, subject, message, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iss", $homeowner_id, $subject, $message);
        return $stmt->execute();
    }
}
?>