<?php

require_once "../db.php"; // adjust path if needed

// Add service_fee column
echo "<h3>Running setup to add 'service_fee' column...</h3>";

$sql = "ALTER TABLE cleaner_profiles ADD COLUMN service_fee DECIMAL(8,2) DEFAULT NULL";

if ($conn->query($sql) === TRUE) {
    echo "<p style='color:green;'>Success: 'service_fee' column added to cleaner_profiles table.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'service_fee' column already exists. No action taken.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Add preferred_location column
echo "<h3>Running setup to add 'preferred_location' column...</h3>";

$sql = "ALTER TABLE cleaner_profiles ADD COLUMN preferred_locations TEXT DEFAULT NULL";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color:green;'>Success: 'preferred_locations' column added to cleaner_profiles table.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'preferred_locations' column already exists. No action taken.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Add emergency_contact column
echo "<h3>Running setup to add 'emergency_contact' column...</h3>";

$sql = "ALTER TABLE cleaner_profiles ADD COLUMN emergency_contact TEXT DEFAULT NULL";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color:green;'>Success: 'emergency_contact' column added to cleaner_profiles table.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'emergency_contact' column already exists. No action taken.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Disable availability mode

echo "<h3>Running setup to add 'unavailable_mode' column...</h3>";

$sql = "ALTER TABLE cleaner_profiles ADD COLUMN unavailable_mode BOOLEAN DEFAULT 0";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color:green;'>Success: 'unavailable_mode' column added.</p>";
} else {
    if (str_contains($conn->error, "Duplicate column name")) {
        echo "<p style='color:orange;'>Notice: 'unavailable_mode' column already exists.</p>";
    } else {
        echo "<p style='color:red;'>Error (unavailable_mode): " . $conn->error . "</p>";
    }
}

$conn->close();
?>