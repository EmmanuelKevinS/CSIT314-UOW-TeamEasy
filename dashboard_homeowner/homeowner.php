<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/HomeownerManager.php";
require_once "../classes/homeowner/DashboardManager.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "homeowner") {
    header("Location: ../auth.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$dashboardManager = new DashboardManager($conn);
$homeownerManager = new HomeownerManager($conn);

// Mark notifications as seen
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["mark_home_seen"])) {
    $dashboardManager->markAllAsSeen($user_id);
}

// Fetch data
$homeowner = $homeownerManager->getHomeownerByUserId($user_id);
$name = $homeowner ? htmlspecialchars($homeowner->getName()) : "Homeowner";
$notifications = $dashboardManager->getUnseenStatusUpdates($user_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Homeowner Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            padding: 40px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
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
        .notification strong {
            display: block;
            margin-bottom: 10px;
        }
        .notification form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .notification form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome, <?= $name ?>!</h2>
    <p>You are now logged in as a Homeowner.</p>

    <?php if ($notifications->num_rows > 0): ?>
        <div class="notification">
            <strong>🔔 You have <?= $notifications->num_rows ?> new update<?= $notifications->num_rows > 1 ? 's' : '' ?>:</strong>
            <ul>
                <?php while ($b = $notifications->fetch_assoc()): ?>
                    <li>
                        <?= htmlspecialchars($b["username"]) ?>
                        <?php
                            if ($b["status"] === "confirmed") echo " accepted your request";
                            elseif ($b["status"] === "cancelled") echo " declined your request";
                            elseif ($b["status"] === "completed") echo " completed the job";
                        ?>
                        for <?= htmlspecialchars($b["booking_date"]) ?> (<?= htmlspecialchars($b["booking_time"]) ?>)
                    </li>
                <?php endwhile; ?>
            </ul>
            <form method="post">
                <button name="mark_home_seen">Mark all as seen</button>
            </form>
        </div>
    <?php endif; ?>

    <ul>
        <li><a href="edit_profile_homeowner.php">Edit Profile</a></li>
        <li><a href="search_cleaners.php">Search for a Cleaner</a></li>
        <li><a href="view_shortlisted_cleaners.php">View Shortlisted Cleaners</a></li>
        <li><a href="track_requests.php">View Job Status</a></li>
        <li><a href="service_usage_history.php">View History</a></li>
        <li><a href="leave_review.php">Leave a Review</a></li>
        <li><a href="contact_admin.php">Contact Admin</a></li>
        <li><a href="../auth.php?logout=true">Logout</a></li>
    </ul>
</div>
</body>
</html>