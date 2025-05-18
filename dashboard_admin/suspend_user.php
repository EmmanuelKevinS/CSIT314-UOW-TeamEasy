<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth.php");
    exit;
}

require_once "../db.php";
require_once "../classes/admin/UserManager.php";

$userManager = new UserManager($conn);

// Handle suspension
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"])) {
    $userManager->suspendUser($_POST["user_id"]);
    header("Location: suspend_user.php");
    exit;
}

$sortField = $_GET["sort"] ?? "id";
$result = $userManager->getActiveUsers($sortField);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Suspend Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
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
            color: #2c3e50;
            text-align: center;
        }
        p {
            text-align: center;
            margin-top: 10px;
        }
        a.sort-link {
            margin: 0 10px;
            color: #007bff;
            text-decoration: none;
        }
        a.sort-link:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #c82333;
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
        <h2>Suspend Users</h2>
        <p>Sort by:
            <a class="sort-link" href="?sort=id">User ID</a> |
            <a class="sort-link" href="?sort=username">Username</a> |
            <a class="sort-link" href="?sort=role">Role</a>
        </p>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <form method="post" style="margin:0;" onsubmit="return confirm('Are you sure you want to suspend this user?');">
                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                        <button type="submit">Suspend</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div class="back-link">
            <a href="admin.php">Back to Dashboard</a>
        </div>

    </div>
</body>
</html>