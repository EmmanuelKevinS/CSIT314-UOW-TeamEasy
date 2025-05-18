<?php
class AdminMessageManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // for contact_admin.php
    public function sendMessage($userId, $role, $subject, $message) {
        if (empty($subject) || empty($message)) {
            return ["success" => false, "error" => "Please fill in all fields."];
        }

        $stmt = $this->conn->prepare("INSERT INTO admin_messages (user_id, role, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $role, $subject, $message);
        $success = $stmt->execute();
        $stmt->close();

        return $success
            ? ["success" => true]
            : ["success" => false, "error" => "Failed to send message."];
    }
}
?>