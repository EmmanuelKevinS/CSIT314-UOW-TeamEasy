<?php
session_start();
require_once "../db.php";
require_once "../classes/admin/AdminDashboardManager.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth.php");
    exit;
}

$adminManager = new AdminDashboardManager($conn);
$username = htmlspecialchars($_SESSION['username']);

// Fetch metrics
$totalUsers = $adminManager->getTotalUsers();
$suspendedUsers = $adminManager->getSuspendedUsers();
$totalCleaners = $adminManager->getTotalCleaners();
$totalHomeowners = $adminManager->getTotalHomeowners();
$totalBookings = $adminManager->getTotalBookings();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            padding: 40px;
        }
        .container {
            max-width: 700px;
            min-height: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }
        .summary-container {
            max-width: 200px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
            
            text-align: center;
        }
        .summary-container h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
        }
        .dashboard-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
            max-width: 1000px;
            margin: auto;
            padding: 40px;
        }

        .summary-container,
        .container {
            flex: 1 1 48%;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
        }
        
        .metrics {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 30px;
        }
        .metric {
            flex: 1 1 45%;
            background-color: #eef2f7;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .metric h3 {
            margin: 0;
            font-size: 16px;
            color: #555;
        }
        .metric p {
            font-size: 24px;
            color: #007bff;
            margin: 5px 0 0 0;
        }
        ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
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
    </style>
</head>
<body>
<div class="dashboard-wrapper">
<div class="summary-container">
    
    

    <div class="metrics">
        <div class="metric">
            <h3>Total Users</h3>
            <p><?= $totalUsers ?></p>
        </div>
        <div class="metric">
            <h3>Total Cleaners</h3>
            <p><?= $totalCleaners ?></p>
        </div>
        <div class="metric">
            <h3>Total Homeowners</h3>
            <p><?= $totalHomeowners ?></p>
        </div>
        <div class="metric">
            <h3>Suspended Users</h3>
            <p><?= $suspendedUsers ?></p>
        </div>
        <div class="metric">
            <h3>Total Bookings</h3>
            <p><?= $totalBookings ?></p>
        </div>
    </div>
</div>

<div class="container">
    <ul>
    <h2>Welcome, Admin <?= $username ?></h2>
    </ul>

    <p>Use the options below to manage the platform:</p>
    

    
        <li><a href="view_users.php">View All Users</a></li>
        <li><a href="suspend_user.php">Suspend a User</a></li>
        <li><a href="unsuspend_user.php">Unsuspend a User</a></li>
        <li><a href="admin_monitor_cleaners.php">Monitor Cleaners</a></li>
        <li><a href="view_feedback.php">View Feedback</a></li>
        <li><a href="admin_daily_report.php">View Daily Report</a></li>
        <li><a href="manage_service_categories.php">Manage Service Categories</a></li>
        <li><a href="../auth.php">Logout</a></li>
    
       
    
</div>
</div>
</body>
</html>