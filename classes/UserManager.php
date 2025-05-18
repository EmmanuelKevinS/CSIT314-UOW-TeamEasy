<?php
class UserManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function isUsernameTaken($username) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function registerUser($username, $password, $role) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed, $role);
        return $stmt->execute();
    }

    public function loginUser($username, $password, $role) {
        $stmt = $this->conn->prepare("SELECT id, password, role, status FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) return [false, "Username not registered."];

        $stmt->bind_result($user_id, $hashed_password, $db_role, $status);
        $stmt->fetch();

        if ($role !== $db_role) return [false, "Incorrect role selected."];
        if (!password_verify($password, $hashed_password)) return [false, "Incorrect password."];
        if ($status === 'suspended') return [false, "Your account has been suspended."];

        return [true, ['id' => $user_id, 'username' => $username, 'role' => $role]];
    }
}
?>