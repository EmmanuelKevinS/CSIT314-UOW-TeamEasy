<?php
class ServiceCategoryManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }


    // for manage_service_categories.php
    public function getAllCategories() {
        
        $stmt = $this->conn->prepare("SELECT id AS category_id, name FROM service_categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addCategory($name) {
        $stmt = $this->conn->prepare("INSERT INTO service_categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function deleteCategory($id) {
        // Check if category is in use
        $check = $this->conn->prepare("SELECT 1 FROM cleaner_services WHERE category_id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            return false; // Category in use, block deletion
        }

        $stmt = $this->conn->prepare("DELETE FROM service_categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>