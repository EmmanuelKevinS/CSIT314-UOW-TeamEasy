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

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["booking_id"], $_POST["booking_date"], $_POST["booking_time"])) {
    $bookingId = intval($_POST["booking_id"]);
    $homeownerName = $bookingManager->markBookingCompleted($bookingId, $cleanerId, $_POST["booking_date"], $_POST["booking_time"]);
    if ($homeownerName) {
        $success_message = "Job for " . htmlspecialchars($homeownerName) . " marked as completed.";
    } else {
        $error_message = "Booking time has not yet ended. You cannot mark this job as completed.";
    }
}

$bookings = $bookingManager->getConfirmedBookings($cleanerId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Completed Jobs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
        }
        .card {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .table-wrapper {
            max-width: 1000px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin: 0 auto;
          
            table-layout: fixed;
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

        .button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 5px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            cursor: pointer;
        }

        button:hover {
            background-color:rgb(52, 185, 92);
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        .btn-complete {
            background-color: #28a745;
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
<div class="container">
<h2>Report Completed Jobs</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php endif; ?>

    <div class="table-wrapper">
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
                <?php while ($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["service_required"]) ?></td>
                        <td><?= htmlspecialchars($row["booking_date"]) ?></td>
                        <td><?= htmlspecialchars($row["booking_time"]) ?></td>
                        <td><?= htmlspecialchars($row["address"]) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?= $row["id_booking"] ?>">
                                <input type="hidden" name="booking_date" value="<?= $row["booking_date"] ?>">
                                <input type="hidden" name="booking_time" value="<?= $row["booking_time"] ?>">
                                <button name="action" value="complete" class="btn btn-complete">Mark as Completed</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No confirmed jobs to report yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>


   

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>
    
</div>
</body>
</html>