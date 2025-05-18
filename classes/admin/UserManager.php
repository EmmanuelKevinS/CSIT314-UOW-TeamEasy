<?php
class UserManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // for suspend_user.php
    public function suspendUser($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function getActiveUsers($sortField = 'id') {
        $validSortFields = ['id', 'username', 'role'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'id';
        }
        return $this->conn->query("SELECT id, username, role, status FROM users WHERE status = 'active' ORDER BY $sortField");
    }

    // for unsuspend_user.php
    public function unsuspendUser($userId) {
        $stmt = $this->conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function getSuspendedUsers() {
        return $this->conn->query("SELECT id, username, role, created_at FROM users WHERE status = 'suspended'");
    }

    // for view_users.php
    public function getAllUsers($sortField = 'id') {
        $validSortFields = ['id', 'username', 'role'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'id';
        }
        return $this->conn->query("SELECT id, username, role, status, created_at FROM users ORDER BY $sortField");
    }

    public function countUsersByRole() {
        $result = $this->conn->query("SELECT role, COUNT(*) as total FROM users GROUP BY role");
        $roleCounts = [
            "cleaner" => 0,
            "homeowner" => 0,
            "admin" => 0
        ];

        while ($row = $result->fetch_assoc()) {
            $role = $row["role"];
            $roleCounts[$role] = $row["total"];
        }

        return $roleCounts;
    }
}
?>