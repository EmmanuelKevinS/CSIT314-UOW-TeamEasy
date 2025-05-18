<?php
session_start();
require_once "../db.php";
require_once "../classes/admin/CleanerMonitor.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth.php");
    exit;
}

date_default_timezone_set('Asia/Singapore');
$today = date("Y-m-d");
$currentTime = date("H:i");

$monitor = new CleanerMonitor($conn);
$cleanersStatus = $monitor->getCleanersStatus($today, $currentTime);

// Filter logic
$statusFilter = $_GET['status'] ?? 'all';

if ($statusFilter === 'free') {
    $cleanersStatus = array_filter($cleanersStatus, function($c) {
        return strtolower($c['status']) === 'free';
    });
} elseif ($statusFilter === 'onjob') {
    $cleanersStatus = array_filter($cleanersStatus, function($c) {
        return strtolower($c['status']) === 'on job';
    });
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Monitor Cleaners</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 40px;
        }
        .card {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .sort-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
        }

        .sort-links a:hover {
            text-decoration: underline;
        }
        th, td {
            padding: 12px 10px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #007bff;
            color: white;
        }
        .on-job { color:  #dc3545; }
        .free { color: green; }
        

        .status.free {
            color:  #dc3545;
        }

        .status.on-job {
            color:  #28a745;
        }

        .status.has-bookings {
            color: orange;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
            text-align: center;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 25px;
            text-align: center;
            width: 100%;
            color:  #007bff
         
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Cleaner Activity Monitor</h2>
        
    <div class="sort-links" style="text-align: center; margin-bottom: 20px;">
            Filter by Status:
            <a href="?status=all">All</a> |
            <a href="?status=free">Free</a> |
            <a href="?status=onjob">On Job</a>
        </div>

    <table>
            <thead>
                <tr>
                    <th>Cleaner</th>
                    <th>Status</th>
                    <th>Future Bookings</th>
                </tr>
            </thead>
        <?php foreach ($cleanersStatus as $cleaner): ?>
            <tr>
                <td><?= htmlspecialchars($cleaner['username']) ?></td>
                <td>
                    <?php
                        $statusText = $cleaner['status'];
                        $statusClass = strtolower(str_replace(' ', '-', $statusText)); 
                    ?>
                    <span class="status <?= $statusClass ?>">
                        <?= htmlspecialchars($statusText) ?>
                    </span>
                </td>
                <td>
                    <div style="min-width: 160px;">
                        <?php $future = $monitor->getFutureBookings($cleaner["id"], $today); ?>
                        <?php if (count($future)): ?>
                            <ul style="padding-left: 18px; margin: 0;">
                                <?php foreach ($future as $f): ?>
                                    <li><?= htmlspecialchars($f) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <span style="color:   #dc3545;">none</span>
                        <?php endif; ?>
                    </div>
                </td>
                
                
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="back-link">
    <a href="admin.php">Back to Dashboard</a>
    </div>

</div>
</body>
</html>