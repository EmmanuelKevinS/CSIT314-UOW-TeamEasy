<?php
session_start();
require_once "../db.php";
require_once "../classes/cleaner/CleanerDashboardManager.php";
require_once "../classes/cleaner/CleanerProfileManager.php"; // added for profile checking

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "cleaner") {
    header("Location: ../auth.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$manager = new CleanerDashboardManager($conn);

$profileManager = new CleanerProfileManager($conn); // create instance
$profile = $profileManager->getProfileByUserId($user_id); // use correct method

// Check if profile is missing or incomplete
if (!$profile || empty($profile["name"])) {
    header("Location: profile_cleaner.php");
    exit;
}




// Handle 'mark as seen'
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["mark_seen"])) {
    $manager->markBookingsAsSeen($user_id);
}

$name = htmlspecialchars($manager->getCleanerName($user_id));
$new_bookings = $manager->getUnseenBookings($user_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cleaner Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
        }
        ul {
            margin-top: 20px;
            padding-left: 20px;
        }
        li {
            margin: 15px 0;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .notification {
            background-color: #fff3cd;
            padding: 15px 20px;
            border: 1px solid #ffeeba;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 15px;
        }
        .notification button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
        }
        .notification button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome, <?= $name ?>!</h2>
    <p>You are now logged in as a cleaner.</p>

    <?php if ($new_bookings && $new_bookings->num_rows > 0): ?>
        <div class="notification">
            <strong>🔔 You have <?= $new_bookings->num_rows ?> new booking<?= $new_bookings->num_rows > 1 ? 's' : '' ?>:</strong>
            <ul>
                <?php while ($b = $new_bookings->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($b["name"]) ?> booked you for <?= $b["booking_date"] ?> (<?= $b["booking_time"] ?>)</li>
                <?php endwhile; ?>
            </ul>
            <form method="post">
                <button name="mark_seen">Mark all as seen</button>
            </form>
        </div>
    <?php endif; ?>

    <ul>
        <li><a href="edit_profile_cleaner.php">Edit Profile</a></li>
        <li><a href="set_service_fee.php">Set Service Fee</a></li>
        <li><a href="set_working_locations.php">Set Preferred Locations</a></li>
        <li><a href="set_emergency_contact.php">Set Emergency Contact</a></li>
        <li><a href="set_unavailable_mode.php">Set Availability</a></li>
        <li><a href="view_shortlists.php">See Shortlist Count</a></li>
        <li><a href="manage_bookings.php">Accept / Decline Bookings</a></li>
        <li><a href="completed_jobs.php">Report Completed Jobs</a></li>
        <li><a href="service_match_history.php">View History</a></li>
        <li><a href="contact_admin.php">Contact Admin</a></li>
        <li><a href="../auth.php?logout=true">Logout</a></li>
    </ul>
</div>
</body>
</html>