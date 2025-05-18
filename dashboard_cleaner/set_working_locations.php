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
    $locations = isset($_POST["locations"]) ? implode(", ", $_POST["locations"]) : "";
    if ($manager->updatePreferredLocations($userId, $locations)) {
        $message = "Preferred locations updated successfully.";
    } else {
        $message = "Error updating preferred locations.";
    }
}

// Fetch existing
$currentLocations = explode(", ", $manager->getPreferredLocations($userId));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Preferred Working Locations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .card {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .message {
           color: green;
            margin: 10px 0;
            font-weight: bold;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="checkbox"] {
            margin-right: 8px;
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
    <h2>Set Preferred Working Locations</h2>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div> <br>
    <?php endif; ?>
    <form method="post">
        <?php
        $options = ["Ang Mo Kio", "Bedok", "Bukit Batok", "Jurong East", "Woodlands"];
        foreach ($options as $location):
            $checked = in_array($location, $currentLocations) ? "checked" : "";
        ?>
            <label><input type="checkbox" name="locations[]" value="<?= $location ?>" <?= $checked ?>><?= $location ?></label>
        <?php endforeach; ?>
        <button type="submit">Save</button>
    </form>

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>

</div>
</body>
</html>