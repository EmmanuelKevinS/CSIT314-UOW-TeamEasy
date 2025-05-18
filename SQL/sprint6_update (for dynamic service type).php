<?php

require_once "../db.php"; 

// Create table for service_categories
echo "<h3>Running setup to add table 'service_categories' ...</h3>";

$sql = "CREATE TABLE IF NOT EXISTS service_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'service_categories' created successfully.<br>";
} else {
    die("Error creating service_categories table: " . $conn->error);
}

// Create table for cleaner_services
echo "<h3>Running setup to add table 'cleaner_services' ...</h3>";

$sql = "CREATE TABLE IF NOT EXISTS cleaner_services (
    cleaner_id INT(10) UNSIGNED NOT NULL,
    category_id INT(11) NOT NULL,
    PRIMARY KEY (cleaner_id, category_id),
    FOREIGN KEY (cleaner_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE RESTRICT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'cleaner_services' created successfully.<br>";
} else {
    die("Error creating cleaner_services table: " . $conn->error);
}



$conn->close();
?>


