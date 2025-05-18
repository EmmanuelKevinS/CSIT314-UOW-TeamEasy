<?php
session_start();
require_once "../db.php";
require_once "../classes/cleaner/CleanerBookingManager.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cleaner') {
    header("Location: ../auth.php");
    exit;
}

$cleanerId = $_SESSION['user_id'];

$statusFilter = $_GET['status'] ?? 'all';
$serviceFilter = $_GET['service'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';

// Get data using filtering method
$bookingManager = new CleanerBookingManager($conn);
$bookings = $bookingManager->filterBookingHistory($cleanerId, $statusFilter, $serviceFilter, $startDate, $endDate);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Match History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
        }
        .card {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .filter-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .filter-bar a {
            margin: 0 12px;
            text-decoration: none;
            color: #007bff;
        }
        .filter-bar a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        color:  #007bff;
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
        .pending {
            color: orange;
            font-weight: bold;
        }
        .filter-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .filter-controls select,
        .filter-controls input[type="date"] {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            outline: none;
        }

        .filter-controls button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .filter-controls button:hover {
            background-color: #0056b3;
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
    <h2>Service Match History</h2>

    <div class="filter-bar">
        Sort by:
        <a href="?status=all">All</a> |
        <a href="?status=pending">Pending</a> |
        <a href="?status=confirmed">Accepted</a> |
        <a href="?status=completed">Completed</a> |
        <a href="?status=cancelled">Declined</a>

        <form method="get" class="filter-controls" style="margin-top: 10px;">
            <label for="service">Service:</label>
            <select name="service" id="service">
                <option value="">All</option>
                <option value="Carpet" <?= ($_GET['service'] ?? '') == 'Carpet' ? 'selected' : '' ?>>Carpet</option>
                <option value="Bathroom" <?= ($_GET['service'] ?? '') == 'Bathroom' ? 'selected' : '' ?>>Bathroom</option>
                <option value="Kitchen" <?= ($_GET['service'] ?? '') == 'Kitchen' ? 'selected' : '' ?>>Kitchen</option>
                <option value="Living Room" <?= ($_GET['service'] ?? '') == 'Living Room' ? 'selected' : '' ?>>Living Room</option>
            </select>

            <label for="start_date">From:</label>
            <input type="date" name="start_date" id="start_date" value="<?= $_GET['start_date'] ?? '' ?>">

            <label for="end_date">To:</label>
            <input type="date" name="end_date" id="end_date" value="<?= $_GET['end_date'] ?? '' ?>">

            <button type="submit">Apply</button>
        </form>

    </div>

    <table>
        <tr>
            <th>Homeowner</th>
            <th>Booking Date</th>
            <th>Booking Time</th>
            <th>Service</th>
            <th>Address</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $bookings->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["homeowner_name"]) ?></td>
                <td><?= htmlspecialchars($row["booking_date"]) ?></td>
                <td><?= htmlspecialchars($row["booking_time"]) ?></td>
                <td><?= htmlspecialchars($row["service_required"]) ?></td>
                <td><?= htmlspecialchars($row["address"]) ?></td>
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
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>
    
</div>
</body>
</html>