<?php
class CleanerProfileManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // for profile_cleaner.php
    public function hasProfile($userId) {
        $stmt = $this->conn->prepare("SELECT 1 FROM cleaner_profiles WHERE user_id = ? LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();
        $has = $stmt->num_rows > 0;
        $stmt->close();
        return $has;
    }

    public function createProfile($userId, $data, $selectedCategoryIds) {
        // 1. Insert into cleaner_profiles
        $stmt = $this->conn->prepare("INSERT INTO cleaner_profiles (user_id, name, phone, service_type, working_hours, working_days) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isssss",
            $userId,
            $data["name"],
            $data["phone"],
            $data["service_type"],
            $data["working_hours"],
            $data["working_days"]
        );

        if (!$stmt->execute()) {
            return false;
        }

        // 2. Insert into cleaner_services
        $insertService = $this->conn->prepare("INSERT INTO cleaner_services (cleaner_id, category_id) VALUES (?, ?)");
        foreach ($selectedCategoryIds as $catId) {
            $insertService->bind_param("ii", $userId, $catId);
            $insertService->execute();
        }

        return true;
    }
    /*
    public function createProfile($userId, $data) {
        $stmt = $this->conn->prepare("INSERT INTO cleaner_profiles (user_id, name, phone, service_type, working_hours, working_days) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isssss",
            $userId,
            $data["name"],
            $data["phone"],
            $data["service_type"],
            $data["working_hours"],
            $data["working_days"]
        );
        return $stmt->execute();
    }*/
/*
    public function createProfile($userId, $data) {
        $stmt = $this->conn->prepare("
            INSERT INTO cleaner_profiles (user_id, name, phone, service_type, working_hours, working_days)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isssss",
            $userId,
            $data["name"],
            $data["phone"],
            $data["service_type"],
            $data["working_hours"],
            $data["working_days"]
        );
        return $stmt->execute();
    }*/

    // to see rating
    public function getAverageRating($cleanerId) {
        $stmt = $this->conn->prepare("
            SELECT AVG(rating) AS avg_rating 
            FROM cleaner_reviews 
            WHERE cleaner_id = ?
        ");
        $stmt->bind_param("i", $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['avg_rating'] ?? null;
    }


    // for edit_profile_cleaner.php
    public function getProfileByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM cleaner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
/*
    public function updateProfile($userId, $data) {
        $stmt = $this->conn->prepare("UPDATE cleaner_profiles SET name = ?, phone = ?, working_hours = ?, working_days = ? WHERE user_id = ?");
        $stmt->bind_param(
            "ssssi",
            $data["name"],
            $data["phone"],
            $data["working_hours"],
            $data["working_days"],
            $userId
        );
        return $stmt->execute();
    }*/

    public function updateProfile($userId, $data) {
        $stmt = $this->conn->prepare("
            UPDATE cleaner_profiles 
            SET name = ?, phone = ?, service_type = ?, working_hours = ?, working_days = ? 
            WHERE user_id = ?
        ");
        $stmt->bind_param(
            "sssssi",
            $data["name"],
            $data["phone"],
            $data["service_type"],
            $data["working_hours"],
            $data["working_days"],
            $userId
        );
        return $stmt->execute();
    }


    // to connect service_categories table
    public function getCleanerServiceCategories($userId) {
        $stmt = $this->conn->prepare("SELECT category_id FROM cleaner_services WHERE cleaner_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category_id'];
        }
        return $categories;
    }

    public function updateCleanerCategories($userId, $categoryIds) {
        $this->conn->begin_transaction();

        $stmtDelete = $this->conn->prepare("DELETE FROM cleaner_services WHERE cleaner_id = ?");
        $stmtDelete->bind_param("i", $userId);
        $stmtDelete->execute();
        $stmtDelete->close();

        $stmtInsert = $this->conn->prepare("INSERT INTO cleaner_services (cleaner_id, category_id) VALUES (?, ?)");
        foreach ($categoryIds as $catId) {
            $stmtInsert->bind_param("ii", $userId, $catId);
            $stmtInsert->execute();
        }
        $stmtInsert->close();
        
        $this->conn->commit();
        return true;
    }


    // for set_service_fee.php
    public function getServiceFee($userId) {
        $stmt = $this->conn->prepare("SELECT service_fee FROM cleaner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($fee);
        $stmt->fetch();
        $stmt->close();
        return $fee;
    }

    public function updateServiceFee($userId, $fee) {
        $stmt = $this->conn->prepare("UPDATE cleaner_profiles SET service_fee = ? WHERE user_id = ?");
        $stmt->bind_param("di", $fee, $userId);
        return $stmt->execute();
    }

    // for set_working_locations.php
    public function getPreferredLocations($userId) {
        $stmt = $this->conn->prepare("SELECT preferred_locations FROM cleaner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($locations);
        $stmt->fetch();
        $stmt->close();
        return $locations;
    }

    public function updatePreferredLocations($userId, $locations) {
        $stmt = $this->conn->prepare("UPDATE cleaner_profiles SET preferred_locations = ? WHERE user_id = ?");
        $stmt->bind_param("si", $locations, $userId);
        return $stmt->execute();
    }

    // for set_emergency_contact.php
    public function getEmergencyContact($userId) {
        $stmt = $this->conn->prepare("SELECT emergency_contact FROM cleaner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($contact);
        $stmt->fetch();
        $stmt->close();
        return $contact;
    }

    public function updateEmergencyContact($userId, $contact) {
        $stmt = $this->conn->prepare("UPDATE cleaner_profiles SET emergency_contact = ? WHERE user_id = ?");
        $stmt->bind_param("si", $contact, $userId);
        return $stmt->execute();
    }

    // for set_unavailable_mode.php
    public function setUnavailableMode($userId, $status) {
        $stmt = $this->conn->prepare("UPDATE cleaner_profiles SET unavailable_mode = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $status, $userId);
        return $stmt->execute();
    }

    public function getUnavailableMode($userId) {
        $stmt = $this->conn->prepare("SELECT unavailable_mode FROM cleaner_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();
        $stmt->close();
        return (bool)$status;
    }
}
?>