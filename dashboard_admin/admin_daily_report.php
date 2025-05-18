<?php
session_start();
require_once "../db.php";
require_once "../classes/admin/AdminReportManager.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth.php");
    exit;
}

$report = new AdminReportManager($conn);

$new_users = $report->getNewUsers();
$new_bookings = $report->getNewBookings();
$decided_bookings = $report->getDecidedBookings();
$completed_jobs = $report->getCompletedJobs();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f4f8;
            padding: 30px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        h3 {
            margin-top: 30px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
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
        .accepted {
        color:  #28a745;
        font-weight: bold;
        }
        .completed {
            color:  #28a745;
            font-weight: bold;
        }
        .declined {
            color:  #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Admin Daily Report (<?= date("Y-m-d") ?>)</h1>

    <h3>New Users Signups Today</h3>
    <table>
        <thead><tr><th>Username</th><th>Role</th><th>Created At</th></tr></thead>
        <tbody>
         <?php if (count($new_users) > 0): ?>
            <?php foreach ($new_users as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['role'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">No new users today.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <h3>New Bookings Made Today</h3>
    <table>
        <thead><tr><th>Homeowner</th><th>Cleaner</th><th>Booking Date</th><th>Time</th><th>Created At</th></tr></thead>
        <tbody>
        <?php if (count($new_bookings) > 0): ?>
            <?php foreach ($new_bookings as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['homeowner']) ?></td>
                    <td><?= htmlspecialchars($row['cleaner']) ?></td>
                    <td><?= htmlspecialchars($row['booking_date']) ?></td>
                    <td><?= htmlspecialchars($row['booking_time']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No bookings created today.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <h3>Bookings Accepted / Declined Today</h3>
    <table>
        <thead><tr><th>Homeowner</th><th>Cleaner</th><th>Booking Date</th><th>Time</th><th>Status</th><th>Created At</th></tr></thead>
        <tbody>
        <?php if (count($decided_bookings) > 0): ?>
            <?php foreach ($decided_bookings as $row): ?>
            <tr>
                <td><?= $row['homeowner'] ?></td>
                <td><?= $row['cleaner'] ?></td>
                <td><?= $row['booking_date'] ?></td>
                <td><?= $row['booking_time'] ?></td>
                <td>
                    <?php
                        $status = $row["status"];
                        $display = $status;
                        $colorClass = "";
                        if ($status === "confirmed") {
                            $display = "accepted";
                            $colorClass = "accepted";
                        } elseif ($status === "completed") {
                            $colorClass = "completed";
                        } elseif ($status === "cancelled") {
                            $display = "declined";
                            $colorClass = "declined";
                        } elseif ($status === "pending") {
                            $colorClass = "pending";
                        }
                        echo "<span class='$colorClass'>" . htmlspecialchars($display) . "</span>";
                    ?>
                </td>
                <td><?= htmlspecialchars($row['status_updated_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No accepted or declined bookings today.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <h3>Completed Jobs</h3>
    <table>
        <thead><tr><th>Homeowner</th><th>Cleaner</th><th>Date</th><th>Time</th><th>Created At</th></tr></thead>
        <tbody>
        <?php if (count($completed_jobs) > 0): ?>
            <?php foreach ($completed_jobs as $row): ?>
                <tr>
                    <td><?= $row['homeowner'] ?></td>
                    <td><?= $row['cleaner'] ?></td>
                    <td><?= $row['booking_date'] ?></td>
                    <td><?= $row['booking_time'] ?></td>
                    <td><?= $row['status_updated_at'] ?></td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">No jobs completed today.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="back-link">
        <a href="admin.php">Back to Dashboard</a>
    </div>

</div>
</body>
</html>