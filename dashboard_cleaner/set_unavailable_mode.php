<?php
session_start();
require_once "../db.php";
require_once "../classes/cleaner/CleanerProfileManager.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "cleaner") {
    header("Location: ../auth.php");
    exit;
}

$userId = $_SESSION["user_id"];
$manager = new CleanerProfileManager($conn);
$message = "";

// Handle toggle
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newStatus = isset($_POST["unavailable_mode"]) ? 1 : 0;
    if ($manager->setUnavailableMode($userId, $newStatus)) {
        $message = $newStatus ? "Unavailable mode enabled." : "Unavailable mode disabled.";
    } else {
        $message = "Error updating unavailable mode.";
    }
}

$isUnavailable = $manager->getUnavailableMode($userId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Toggle Availability Mode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: flex;
            align-items: center;
            font-size: 16px;
        }
        input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }
        button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            color: green;
            margin: 10px 0;
            font-weight: bold;
        }
        .back-link {
            text-align: center;
            margin-top: 25px;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Unavailable Mode</h2>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div> <br>
    <?php endif; ?>
    <form method="POST">
        <label>
            <input type="checkbox" name="unavailable_mode" <?= $isUnavailable ? "checked" : "" ?>>
            Enable Unavailable Mode
        </label>
        <button type="submit">Save</button>
    </form>

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>
    
</div>
</body>
</html>