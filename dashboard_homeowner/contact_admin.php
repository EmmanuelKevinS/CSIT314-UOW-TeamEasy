<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/ContactAdmin.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$homeowner_id = $_SESSION['user_id'];
$contact = new ContactAdmin($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $body = trim($_POST['message'] ?? '');

    if ($subject && $body) {
        if ($contact->sendMessage($homeowner_id, $subject, $body)) {
            $message = "success";
        } else {
            $message = "Failed to send message.";
        }
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
            margin-top: 10px;
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

    <?php if ($message === "success"): ?>
        <p class="success">Your message has been sent successfully.</p>
    <?php elseif (!empty($message)): ?>
        <p class="error"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" required>
        </div>
        <br>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea name="message" id="message" required></textarea>
        </div>
        <br>
        <div>
            <button type="submit">Send</button>
        </div>
    </form>

    <div class="back-link">
        <a href="homeowner.php">Back to Dashboard</a>
    </div>
    </div>  
</body>
</html>