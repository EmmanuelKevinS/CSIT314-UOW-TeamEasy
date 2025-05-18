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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contact = trim($_POST["emergency_contact"]);
    if ($manager->updateEmergencyContact($userId, $contact)) {
        $message = "Emergency contact updated successfully.";
    } else {
        $message = "Error updating emergency contact.";
    }
}

// Fetch existing value
$currentEmergency = $manager->getEmergencyContact($userId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Emergency Contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .card {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
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
    <h2>Emergency Contact</h2>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div> <br>
    <?php endif; ?>
    <form method="POST">
        <label for="emergency_contact">Enter contact number:</label>
        <input type="text" name="emergency_contact" id="emergency_contact" value="<?= htmlspecialchars($currentEmergency) ?>" required>
        <button type="submit">Save</button>
    </form>

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>
    
</div>
</body>
</html>