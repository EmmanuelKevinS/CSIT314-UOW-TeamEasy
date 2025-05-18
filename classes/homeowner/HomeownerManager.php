<?php
require_once "../classes/homeowner/Homeowner.php";

class HomeownerManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // for profile_homeowner.php
    public function getHomeownerByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM homeowner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new Homeowner($row['user_id'], $row['name'], $row['phone'], $row['address']);
        }

        return null;
    }

    // for edit_profile_homeowner.php
    public function updateHomeownerProfile($user_id, $name, $phone, $address) {
        $stmt = $this->conn->prepare("UPDATE homeowner_profiles SET name = ?, phone = ?, address = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $name, $phone, $address, $user_id);
        return $stmt->execute();
    }

    
}
?>