<?php
session_start();
require_once "../db.php";
require_once "../classes/cleaner/ShortlistManager.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "cleaner") {
    header("Location: ../auth.php");
    exit;
}

$cleanerId = $_SESSION["user_id"];
$shortlistManager = new ShortlistManager($conn);

$shortlistCount = $shortlistManager->getShortlistCount($cleanerId);
$shortlistResult = $shortlistManager->getShortlistDetails($cleanerId);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shortlist Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
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
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
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
    </style>
</head>
<body>
<div class="card">
    <h2>You have been shortlisted by <?= $shortlistCount ?> homeowner<?= $shortlistCount != 1 ? "s" : "" ?>.</h2>

    <table>
        <tr>
            <th>Homeowner Name</th>
            <th>Address</th>
        </tr>
        <?php while ($row = $shortlistResult->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["name"]) ?></td>
                <td><?= htmlspecialchars($row["address"]) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="back-link">
        <a href="cleaner.php">Back to Dashboard</a>
    </div>

</div>
</body>
</html>