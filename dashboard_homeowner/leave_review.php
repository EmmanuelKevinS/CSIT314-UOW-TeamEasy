<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/ReviewManager.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'homeowner') {
    header("Location: ../auth/login.php");
    exit();
}

$homeowner_id = $_SESSION['user_id'];
$reviewManager = new ReviewManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_booking = $_POST['id_booking'] ?? null;
    $rating = $_POST['rating'] ?? null;

    if ($id_booking && $rating) {
        $reviewManager->submitReview($id_booking, $rating);
        header("Location: leave_review.php"); // Prevent resubmission
        exit;
    }
}

$pendingReviews = $reviewManager->getPendingReviewsForHomeowner($homeowner_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leave a Review</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
            overflow-x: hidden;
        }
        .container {
            max-width: 900px;
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
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        select {
            padding: 6px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        select.rating-select {
            padding: 6px 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }
        button.submit-review {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button.submit-review:hover {
            background-color:rgb(23, 211, 45);
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
    <h2>Leave a Review for Cleaners</h2>
    <?php if ($pendingReviews->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Cleaner</th>
                <th>Service</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Address</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $pendingReviews->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['cleaner_name']) ?></td>
                    <td><?= htmlspecialchars($row['service_required']) ?></td>
                    <td><?= htmlspecialchars($row['booking_date']) ?></td>
                    <td><?= htmlspecialchars($row['booking_time']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td>
                        <form method="POST" style="display: flex; gap: 8px;">
                            <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>">
                            <select name="rating" class="rating-select" required>
                                <option value="">Rate</option>
                                            <option value="1">⭐</option>
                                            <option value="2">⭐⭐</option>
                                            <option value="3">⭐⭐⭐</option>
                                            <option value="4">⭐⭐⭐⭐</option>
                                            <option value="5">⭐⭐⭐⭐⭐</option>
                            </select>
                            <button type="submit" class="submit-review">Submit</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="text-align:center;">You have no completed bookings pending review.</p>
    <?php endif; ?>
    <div class="back-link">
        <a href="homeowner.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>