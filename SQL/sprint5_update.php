<?php

require_once "../db.php"; // adjust path if needed

// Add is_seen column
echo "<h3>Running setup to add 'is_seen' column in cleaner_bookings table...</h3>";

$sql1 = "ALTER TABLE cleaner_bookings ADD COLUMN is_seen TINYINT(1) DEFAULT 0;";

if ($conn->query($sql1) === TRUE) {
    echo "<p style='color:green;'>Success: 'is_seen' column added to cleaner_bookings table.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'is_seen' column already exists. No action taken.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Add homeowner_seen column
echo "<h3>Running setup to add 'homeowner_seen' column in cleaner_bookings table...</h3>";

$sqlhomeownerseen = "ALTER TABLE cleaner_bookings ADD COLUMN homeowner_seen TINYINT(1) DEFAULT 0;";

if ($conn->query($sqlhomeownerseen) === TRUE) {
    echo "<p style='color:green;'>Success: 'homeowner_seen' column added to cleaner_bookings table.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'homeowner_seen' column already exists. No action taken.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Add completed variable into cleaner_bookings.status
echo "<h3>Running setup to add 'completed' variable in cleaner_bookings.status table...</h3>";

$sqlcompleted = "ALTER TABLE cleaner_bookings 
MODIFY status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending';";

if ($conn->query($sqlcompleted) === TRUE) {
    echo "<p style='color:green;'>Success: 'completed' variable added to cleaner_bookings.status table.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'completed' variable already exists. No action taken.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}


// Add rating column
echo "<h3>Running setup to add 'rating' column in cleaner_bookings table...</h3>";

$sqlrating = "ALTER TABLE cleaner_bookings ADD COLUMN rating INT DEFAULT NULL;";

if ($conn->query($sqlrating) === TRUE) {
    echo "<p style='color:green;'>Success: 'rating' column added to cleaner_bookings table.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'rating' column already exists. No action taken.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Add status_updated_at column
echo "<h3>Running setup to add 'status_updated_at' column in cleaner_bookings table...</h3>";

$sqlstatusupdatedate = "ALTER TABLE cleaner_bookings ADD COLUMN status_updated_at DATETIME DEFAULT NULL;";

if ($conn->query($sqlstatusupdatedate) === TRUE) {
    echo "<p style='color:green;'>Success: 'status_updated_at' column added to cleaner_bookings table.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'status_updated_at' column already exists. No action taken.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}



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