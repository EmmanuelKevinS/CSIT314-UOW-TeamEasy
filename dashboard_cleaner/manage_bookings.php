<?php
session_start();
require_once "../db.php";
require_once "../classes/cleaner/CleanerBookingManager.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "cleaner") {
    header("Location: ../auth.php");
    exit;
}

$cleanerId = $_SESSION["user_id"];
$bookingManager = new CleanerBookingManager($conn);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["booking_id"], $_POST["action"])) {
    $bookingId = intval($_POST["booking_id"]);
    $action = $_POST["action"];
    $bookingManager->updateBookingStatus($bookingId, $cleanerId, $action);
    header("Location: manage_bookings.php"); // Refresh after action
    exit;
}

// Fetch bookings
$bookings = $bookingManager->getPendingBookings($cleanerId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
        }
        .card {
            max-width: 900px;
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
            margin-top: 20px;
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
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        .btn-accept {
            background-color: #28a745;
        }
        .btn-decline {
            background-color: #dc3545;
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
    <h2>Pending Bookings</h2>

    <table>
        <thead>
            <tr>
                <th>Homeowner</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($bookings->num_rows > 0): ?>
                <?php foreach ($bookings as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["service_required"]) ?></td>
                        <td><?= htmlspecialchars($row["booking_date"]) ?></td>
                        <td><?= htmlspecialchars($row["booking_time"]) ?></td>
                        <td><?= htmlspecialchars($row["address"]) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?= $row["id_booking"] ?>">
                                <button name="action" value="accept" class="btn btn-accept">Accept</button>
                                <button name="action" value="decline" class="btn btn-decline">Decline</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">No pending bookings at the moment.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>

</div>

</body>
</html>