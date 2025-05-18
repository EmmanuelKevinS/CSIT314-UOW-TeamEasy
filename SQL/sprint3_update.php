<?php

require_once "../db.php"; 

// Create table for cleaner_shortlists
echo "<h3>Running setup to add table 'cleaner_shortlists' ...</h3>";

$sql = "CREATE TABLE IF NOT EXISTS cleaner_shortlists (
    id_shortlist INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    homeowner_id INT UNSIGNED NOT NULL,
    cleaner_id INT UNSIGNED NOT NULL,
    UNIQUE KEY unique_shortlist (homeowner_id, cleaner_id),
    FOREIGN KEY (homeowner_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (cleaner_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;";

if ($conn->query($sql) === TRUE) {
    echo "Table 'cleaner_shortlists' created successfully.<br>";
} else {
    die("Error creating cleaner_shortlists table: " . $conn->error);
}

// Create table for cleaner_bookings
echo "<h3>Running setup to add table 'cleaner_bookings' ...</h3>";
$sqlbooking = "CREATE TABLE IF NOT EXISTS cleaner_bookings (
    id_booking INT AUTO_INCREMENT PRIMARY KEY,
    homeowner_id INT UNSIGNED NOT NULL,
    cleaner_id INT UNSIGNED NOT NULL,
    service_required VARCHAR(100),
    booking_date DATE NOT NULL,
    booking_time VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (homeowner_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (cleaner_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sqlbooking) === TRUE) {
    echo "Table 'cleaner_bookings' created successfully.<br>";
} else {
    die("Error creating cleaner_bookings table: " . $conn->error);
}


$conn->close();
?>


