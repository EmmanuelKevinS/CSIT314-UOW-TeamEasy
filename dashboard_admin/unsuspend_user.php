<?php
require_once "../db.php";
require_once "../classes/admin/UserManager.php";
session_start();

// Restrict to admin only
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth.php");
    exit;
}

$userManager = new UserManager($conn);

// Handle unsuspend request
if (isset($_GET["id"])) {
    $userManager->unsuspendUser($_GET["id"]);
    header("Location: unsuspend_user.php"); // Refresh page
    exit;
}

// Fetch suspended users
$result = $userManager->getSuspendedUsers();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsuspend Users</title>
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
            text-align: center;
            color: #2c3e50;
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
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        a.unsuspend {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        a.unsuspend:hover {
            background-color:rgb(52, 185, 92);
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
        <h2>Unsuspend Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>User Created At</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <a class="unsuspend" href="?id=<?= $row['id'] ?>" onclick="return confirm('Unsuspend this user?')">
                            Unsuspend
                        </a>
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