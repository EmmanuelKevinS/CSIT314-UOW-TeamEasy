<?php
class CleanerSearch {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // for search_cleaners.php
    public function searchCleaners($category = '', $sortOrder = '') {
        $query = "SELECT * FROM cleaner_profiles";
        $conditions = [];
        $params = [];
        $types = '';

        // Only add condition if it's a real filter (not "all")
        if (!empty($category) && $category !== 'all') {
            $conditions[] = "service_type LIKE ?";
            $params[] = "%$category%";
            $types .= 's';
        }

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Sorting
        if ($sortOrder === 'price_asc') {
            $query .= " ORDER BY service_fee ASC";
        } elseif ($sortOrder === 'price_desc') {
            $query .= " ORDER BY service_fee DESC";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }
}
?>