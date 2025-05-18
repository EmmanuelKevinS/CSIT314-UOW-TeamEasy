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

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    if ($homeownerManager->updateHomeownerProfile($user_id, $name, $phone, $address)) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Failed to update profile.";
    }
}

// Load profile data
$homeowner = $homeownerManager->getHomeownerByUserId($user_id);
if (!$homeowner) {
    echo "Homeowner not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            padding: 40px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2c3e50;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 25px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            font-weight: bold;
            text-align: center;
            color: green;
        }
        .success { color: green; }
        .error { color: red; }
        .info p {
            margin: 5px 0;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
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
        <h2>Edit Your Profile</h2>

        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($homeowner->getName()); ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($homeowner->getPhone()); ?>" required>

            <label>Address:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($homeowner->getAddress()); ?>" required>

            <button type="submit">Update Profile</button>
        </form>

        <div class="back-link">
            <a href="homeowner.php">Back to Dashboard</a>
        </div>
    </div>

    
</body>
</html>