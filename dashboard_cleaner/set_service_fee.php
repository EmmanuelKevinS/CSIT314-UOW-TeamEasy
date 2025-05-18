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

// Fetch current fee
$current_fee = $manager->getServiceFee($userId);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fee = floatval($_POST["service_fee"]);
    if ($manager->updateServiceFee($userId, $fee)) {
        $message = "Service fee updated successfully.";
        $current_fee = $fee;
    } else {
        $message = "Failed to update service fee.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Service Fee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f8fa;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .card {
            background: #fff;
            margin: auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
        }
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            display: inline-block;
            margin-top: 10px;
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
        <h2>Set Your Service Fee</h2>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div> <br>
        <?php endif; ?>
        <form method="POST">
            <label for="service_fee">Service Fee ($):</label>
            <input type="number" step="0.01" name="service_fee" value="<?= htmlspecialchars($current_fee) ?>" required>
            <button type="submit">Update Fee</button>
        </form>

        <div class="back-link">
            <a href="cleaner.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>