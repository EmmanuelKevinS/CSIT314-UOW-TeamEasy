<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth.php");
    exit;
}

require_once "../db.php";
require_once "../classes/admin/UserManager.php";

$userManager = new UserManager($conn);

$sortField = $_GET["sort"] ?? "id";
$result = $userManager->getAllUsers($sortField);

$roleCounts = $userManager->countUsersByRole();
$totalUsers = array_sum($roleCounts);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            padding: 40px;
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
            color: #2c3e50;
        }
        p {
            text-align: center;
        }
        a.sort-link {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
        }
        a.sort-link:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .back-link {
            display: inline-block;
            margin-top: 25px;
            text-align: center;
            width: 100%;
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
    <h2>User Management</h2>

    <p style="margin-bottom: 10px;">
        <span style="margin-right: 20px;"><strong>Total Users:</strong> <?= $totalUsers ?></span>
        <span style="margin-right: 20px;"><strong>Cleaners:</strong> <?= $roleCounts["cleaner"] ?></span>
        <span style="margin-right: 20px;"><strong>Homeowners:</strong> <?= $roleCounts["homeowner"] ?></span>
        <span><strong>Admins:</strong> <?= $roleCounts["admin"] ?></span>
    </p>
    
    <p>Sort by:
        <a class="sort-link" href="?sort=id">User ID</a> |
        <a class="sort-link" href="?sort=username">Username</a> |
        <a class="sort-link" href="?sort=role">Role</a>
    </p>

    <table>
        <tr>
            <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="back-link">
        <a href="admin.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>