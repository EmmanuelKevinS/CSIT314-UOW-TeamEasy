
<?php
// Database configuration
$host = "localhost";
$user = "root"; // Default XAMPP username
$pass = "";     // Default XAMPP password is empty
$dbname = "cleaning_platform";

// Create connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS cleaning_platform";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db($dbname);

// Create users table
$createUsersTable = "
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('cleaner', 'homeowner', 'admin') NOT NULL,
    status ENUM('active', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($createUsersTable) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    die("Error creating users table: " . $conn->error);
}

// Create cleaner_profiles table
$createCleanerProfileTable = "
CREATE TABLE IF NOT EXISTS cleaner_profiles (
    user_id INT UNSIGNED PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20),
    service_type VARCHAR(100),
    working_hours VARCHAR(100),
    working_days VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($createCleanerProfileTable) === TRUE) {
    echo "Table 'cleaner_profiles' created successfully.<br>";
} else {
    die("Error creating cleaner_profiles table: " . $conn->error);
}

// Create homeowner_profiles table
$createHomeownerProfileTable = "
CREATE TABLE IF NOT EXISTS homeowner_profiles (
    user_id INT UNSIGNED PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($createHomeownerProfileTable) === TRUE) {
    echo "Table 'homeowner_profiles' created successfully.<br>";
} else {
    die("Error creating homeowner_profiles table: " . $conn->error);
}

$conn->close();
?>
