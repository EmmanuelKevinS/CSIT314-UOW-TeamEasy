<?php

require_once "../db.php"; // adjust path if needed

// Add admin_messages table
echo "<h3>Running setup to add table 'admin_messages' ...</h3>";

$sqlmessagetable = "CREATE TABLE IF NOT EXISTS admin_messages (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    role ENUM('cleaner', 'homeowner') NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sqlmessagetable) === TRUE) {
    echo "Table 'admin_messages' created successfully.<br>";
} else {
    die("Error creating cleaner_bookings table: " . $conn->error);
}

$conn->close();
?>