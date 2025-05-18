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
$bookings = $bookingManager->getBookingsByHomeowner($homeowner_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Booking Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
        }
        .card {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
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
        .status {
            font-weight: bold;
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
    <h2>Track My Service Requests</h2>
    <?php if ($bookings && $bookings->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Cleaner</th>
                    <th>Service</th>
                    <th>Job Created On</th>
                    <th>Booking Date</th>
                    <th>Booking Time</th>
                    <th>Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['cleaner_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['service_required']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
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
        <p>You have not made any bookings yet.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="homeowner.php">Back to Dashboard</a>
    </div>
</body>
</html>