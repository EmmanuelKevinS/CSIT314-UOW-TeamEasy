<?php
session_start();
require_once "../db.php";
require_once "../classes/cleaner/AdminMessageManager.php";

if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ["cleaner", "homeowner"])) {
    header("Location: ../auth.php");
    exit;
}

$userId = $_SESSION["user_id"];
$role = $_SESSION["role"];
$message_sent = false;
$error = "";

$manager = new AdminMessageManager($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = trim($_POST["subject"]);
    $body = trim($_POST["message"]);
    $result = $manager->sendMessage($userId, $role, $subject, $body);

    if ($result["success"]) {
        $message_sent = true;
    } else {
        $error = $result["error"];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        textarea {
           
            min-height: 120px;
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
        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
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
<div class="container">
    <h2>Contact Admin</h2>

    <?php if ($message_sent): ?>
        <div class="success">Message sent successfully!</div>
    <?php elseif ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" required>
        </div> <br>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea name="message" rows="5" required></textarea>
        </div> 
        <button type="submit">Send</button>
    </form>

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>