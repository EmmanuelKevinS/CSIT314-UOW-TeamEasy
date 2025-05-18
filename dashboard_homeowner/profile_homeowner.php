<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/HomeownerManager.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$homeownerManager = new HomeownerManager($conn);
$homeowner = $homeownerManager->getHomeownerByUserId($user_id);

if (!$homeowner) {
    echo "Homeowner profile not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 2em;
        }
        .container {
            background: #fff;
            max-width: 600px;
            margin: auto;
            padding: 2em;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        .profile-info {
            margin-top: 1em;
        }
        .profile-info label {
            font-weight: bold;
            display: block;
            margin-top: 1em;
        }
        .profile-info span {
            display: block;
            margin-top: 0.3em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Profile</h2>
        <div class="profile-info">
            <label>Name:</label>
            <span><?php echo htmlspecialchars($homeowner->getName()); ?></span>

            <label>Phone:</label>
            <span><?php echo htmlspecialchars($homeowner->getPhone()); ?></span>

            <label>Address:</label>
            <span><?php echo htmlspecialchars($homeowner->getAddress()); ?></span>
        </div>
    </div>
</body>
</html>