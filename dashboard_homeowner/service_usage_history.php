<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/BookingManager.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$homeowner_id = $_SESSION['user_id'];
$bookingManager = new BookingManager($conn);

$status = $_GET['status'] ?? 'all';
$service = $_GET['service'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$completedBookings = $bookingManager->filterBookings($homeowner_id, $status, $service, $start_date, $end_date);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Usage History</title>
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
        .accepted { color: goldenrod; font-weight: bold; }
        .completed { color: green; font-weight: bold; }
        .declined { color: red; font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
        
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
        <h2>My Service Usage History</h2>
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

        <?php if ($completedBookings && $completedBookings->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Cleaner</th>
                    <th>Service</th>
                    <th>Booking Date</th>
                    <th>Booking Time</th>
                    <th>Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $completedBookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['cleaner_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['service_required']); ?></td>
                        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
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
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">You have no completed service history yet.</p>
    <?php endif; ?>
    <div class="back-link">
        <a href="homeowner.php">Back to Dashboard</a>
    </div>
</body>
</html>