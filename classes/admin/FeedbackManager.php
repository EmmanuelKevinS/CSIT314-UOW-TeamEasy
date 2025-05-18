<?php
class FeedbackManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // for view_feedback_.php
    public function getAllMessages() {
        $stmt = $this->conn->prepare("
            SELECT am.id_message, am.subject, am.message, am.created_at, u.username, u.role
            FROM admin_messages am
            JOIN users u ON am.user_id = u.id
            ORDER BY am.created_at DESC
        ");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getMessagesSortedByCustomRole(array $roleOrder) {
    $order = implode("','", array_map([$this->conn, 'real_escape_string'], $roleOrder));
    $query = "
        SELECT f.*, u.username, u.role 
        FROM admin_messages f 
        JOIN users u ON f.user_id = u.id 
        ORDER BY FIELD(u.role, '$order'), f.created_at DESC
    ";
    return $this->conn->query($query);
}
    
}
?>