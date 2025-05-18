<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/CleanerProfile.php";
require_once "../classes/homeowner/CleanerSearch.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Cleaner ID not provided.";
    exit;
}

$cleaner_id = $_GET['id'];
$cleanerProfile = new CleanerProfile($conn);
$cleaner = $cleanerProfile->getCleanerById($cleaner_id);
$averageRating = $cleanerProfile->getAverageRating($cleaner['user_id']);

if (!$cleaner) {
    echo "Cleaner not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cleaner Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
        }
        .card {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        label {
            font-weight: bold;
            display: block;
        }
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        .book-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }
        .book-btn:hover {
            background-color: #218838;
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

    <!-- Cleaner Profile Card -->
    <div class="card" style="margin-bottom: 30px;">
        <h2>Cleaner Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($cleaner["name"]) ?></p>
        <p><strong>Service Category:</strong> <?= htmlspecialchars($cleaner["service_type"]) ?></p>
        <p><strong>Service Fee:</strong> SGD <?= htmlspecialchars($cleaner["service_fee"]) ?></p>
        <p><strong>Working Hours:</strong> <?= htmlspecialchars($cleaner["working_hours"]) ?></p>
        <p><strong>Working Days:</strong> <?= htmlspecialchars($cleaner["working_days"]) ?></p>
        <p><strong>Preferred Location:</strong> <?= htmlspecialchars($cleaner["preferred_locations"]) ?></p>
        <p><strong>Average Rating:</strong>
            <?php if ($averageRating): ?>
                <span style="color: gold; font-weight: bold;">
                    <?= str_repeat("⭐", floor($averageRating)) ?>
                </span>
                <?= number_format($averageRating, 1) ?> / 5.0
            <?php else: ?>
                Not rated yet
            <?php endif; ?>
        </p>
    </div>

    <!-- Booking Form Card -->
    <div class="card booking-card">
        <h2>Book This Cleaner</h2>
        <form method="POST" action="book_cleaner.php">
            <input type="hidden" name="cleaner_id" value="<?= htmlspecialchars($cleaner_id) ?>">

            <!-- Service Selection -->
            <label for="service_required">Service Required:</label><br>
            <?php
            $services = explode(',', $cleaner["service_type"]);
            foreach ($services as $service):
                $cleaned = htmlspecialchars(trim($service));
            ?>
                <input type="radio" name="service_required" value="<?= $cleaned ?>"> <?= $cleaned ?><br>
            <?php endforeach; ?>
            <br>

            <label for="booking_date">Date:</label><br>
            <input type="date" id="booking_date" name="booking_date" required><br><br>

            <label for="booking_time">Available Time Slots:</label><br>
            <?php
            $time_slots = explode(',', $cleaner["working_hours"]);
            foreach ($time_slots as $slot):
                $slot_clean = htmlspecialchars(trim($slot));
            ?>
                <input type="radio" name="booking_time" value="<?= $slot_clean ?>"> <?= $slot_clean ?><br>
            <?php endforeach; ?>
            <br>

            <button type="submit" class="book-btn">Confirm Booking</button>
        </form>

         <div class="back-link">
            <br><a href="javascript:history.back()">Back to Search</a>
        </div>
    </div>

    <!-- Script to Disable Non-Working Days -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const workingDaysStr = <?= json_encode($cleaner["working_days"]) ?>;
            const allowedDays = workingDaysStr.split(',').map(day => day.trim());
            const dayToIndex = {
                "Sunday": 0,
                "Monday": 1,
                "Tuesday": 2,
                "Wednesday": 3,
                "Thursday": 4,
                "Friday": 5,
                "Saturday": 6
            };

            const allowedIndexes = allowedDays.map(day => dayToIndex[day]);

            const dateInput = document.getElementById("booking_date");
            dateInput.addEventListener("input", function () {
                const selectedDate = new Date(this.value);
                const dayIndex = selectedDate.getDay();
                if (!allowedIndexes.includes(dayIndex)) {
                    alert("This cleaner is not available on the selected day. Please choose a valid working day.");
                    this.value = '';
                }
            });
        });
    </script>

   

</div>
</body>
</html>