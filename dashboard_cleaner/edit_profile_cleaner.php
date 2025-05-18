<?php
session_start();
require_once "../db.php";
require_once "../classes/cleaner/CleanerProfileManager.php";
require_once "../classes/admin/ServiceCategoryManager.php";



if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "cleaner") {
    header("Location: ../auth.php");
    exit;
}

$userId = $_SESSION["user_id"];
$manager = new CleanerProfileManager($conn);

$categoryManager = new ServiceCategoryManager($conn);
$allCategories = $categoryManager->getAllCategories();

$selectedCategoryIds = $manager->getCleanerServiceCategories($userId);



$message = "";

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $selectedCategories = $_POST['service_type'] ?? [];   
    $categoryMap = [];
        foreach ($allCategories as $cat) {
            $categoryMap[$cat['category_id']] = $cat['name'];
        } 
    $selectedCategoryNames = array_map(fn($id) => $categoryMap[$id] ?? 'Unknown', $selectedCategories);
    
    $formData = [
        "name" => $_POST["name"],
        "phone" => $_POST["phone"],
        "service_type" => implode(", ", $selectedCategoryNames), 
        "working_hours" => isset($_POST["working_hours"]) ? implode(", ", $_POST["working_hours"]) : "",
        "working_days" => isset($_POST["working_days"]) ? implode(", ", $_POST["working_days"]) : ""
    ];

    if ($manager->updateProfile($userId, $formData)) {
        // Update service categories in join table
        $manager->updateCleanerCategories($userId, $selectedCategories);
        $message = "Profile updated successfully.";

        $profile = $manager->getProfileByUserId($userId);
        $selectedCategoryIds = $manager->getCleanerServiceCategories($userId);
        $selected_hours = explode(", ", $profile["working_hours"]);
        $selected_days = explode(", ", $profile["working_days"]);
    } else {
        $message = "Error updating profile.";
    }
}

// Fetch profile
$profile = $manager->getProfileByUserId($userId);
$categoryManager = new ServiceCategoryManager($conn);
$categoryMap = [];
foreach ($categoryManager->getAllCategories() as $cat) {
    $categoryMap[$cat['category_id']] = $cat['name'];
}

$selectedServiceIds = $manager->getCleanerServiceCategories($userId);
$selectedServiceNames = array_map(fn($id) => $categoryMap[$id] ?? 'Unknown', $selectedServiceIds);


$selected_services = explode(", ", $profile["service_type"]);
$selected_hours = explode(", ", $profile["working_hours"]);
$selected_days = explode(", ", $profile["working_days"]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Cleaner Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            padding: 40px;
        }
        .container {
            max-width: 750px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        


        .checkbox-sections {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
        }

        .checkbox-sections .section {
            background: #ffffff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            flex: 1;
        }

        .checkbox-sections .section label {
            display: block;
            margin: 4px 0;
        }

        
        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"], a.button {
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
        input[type="submit"]:hover, a.button:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            margin: 10px 0;
            font-weight: bold;
        }
        .current-info p {
            margin: 5px 0;
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
    <h2>Edit Cleaner Profile</h2>
    <p class="message"><?= htmlspecialchars($message) ?></p>
    <br>    
    <?php if ($profile): ?>
        <div class="current-info">
            <p><strong>Current Name:</strong> <?= htmlspecialchars($profile['name']) ?></p>
            <p><strong>Current Phone:</strong> <?= htmlspecialchars($profile['phone']) ?></p>
            <p><strong>Current Services:</strong> <?= htmlspecialchars(implode(", ", $selectedServiceNames)) ?></p>
            <p><strong>Current Working Hours:</strong> <?= htmlspecialchars($profile['working_hours']) ?></p>
            <p><strong>Current Working Days:</strong> <?= htmlspecialchars($profile['working_days']) ?></p>
        </div>
    <?php endif; ?>
    <br>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($profile['name']) ?>" required>

        <label>Phone Number:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($profile['phone']) ?>" required>

        <div class="checkbox-sections">
            
        <div class="section">
        
        <label>Service Types:</label><br>
        <?php foreach ($allCategories as $cat): ?>
            <?php
                $catId = $cat['category_id'] ?? $cat['id']; // fallback for aliasing
                $checked = in_array($catId, $selectedCategoryIds) ? 'checked' : '';
            ?>
            <label>
                <input type="checkbox" name="service_type[]" value="<?= $catId ?>" <?= $checked ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </label><br>
        <?php endforeach; ?>


        </div>
        
        <div class="section">
        <label>Working Hours:</label> <br>
        <?php
        $hours = ["8AM-11AM", "11AM-2PM", "2PM-5PM", "5PM-8PM"];
        foreach ($hours as $hour) {
            $checked = in_array($hour, $selected_hours) ? "checked" : "";
            echo "<label><input type='checkbox' name='working_hours[]' value='$hour' $checked> $hour</label><br>";
        }
        ?>
        </div>

        <div class="section">
        <label>Working Days:</label> <br>
        <?php
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        foreach ($days as $day) {
            $checked = in_array($day, $selected_days) ? "checked" : "";
            echo "<label><input type='checkbox' name='working_days[]' value='$day' $checked> $day</label><br>";
        }
        ?>
        </div>
        </div>


        <input type="submit" value="Update Profile">
    </form>

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>
    
</div>
</body>
</html>