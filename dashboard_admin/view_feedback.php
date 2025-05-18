<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth.php");
    exit;
}

require_once "../db.php";
require_once "../classes/admin/FeedbackManager.php";

$feedbackManager = new FeedbackManager($conn);


$sort = $_GET['sort'] ?? 'all';

switch (strtolower($sort)) {
    case 'cleaner':
        $result = $feedbackManager->getMessagesSortedByCustomRole(['cleaner', 'homeowner']);
        break;
    case 'homeowner':
        $result = $feedbackManager->getMessagesSortedByCustomRole(['homeowner', 'cleaner']);
        break;
    default:
        $result = $feedbackManager->getAllMessages();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #007bff;
            color: white;
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
        .role-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
        }
        .cleaner {
            background-color: #28a745;
            padding: 6px 12px;
            border-radius: 4px;
           
        }
        .homeowner {
            background-color:orange;
            padding: 6px 12px;
            border-radius: 4px;
            
        }
    </style>
</head>
<body>
<div class="container">
    <h2>User Feedback</h2>
    
    <p>Sort by Role:
        <a class="sort-link" href="?sort=all">All</a> |
        <a class="sort-link" href="?sort=cleaner">Cleaner First</a> |
        <a class="sort-link" href="?sort=homeowner">Homeowner First</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["username"]) ?></td>
                        <td>
                            <span class="role-badge <?= htmlspecialchars($row["role"]) ?>">
                                <?= ucfirst(htmlspecialchars($row["role"])) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($row["subject"]) ?></td>
                        <td><?= nl2br(htmlspecialchars($row["message"])) ?></td>
                        <td><?= htmlspecialchars($row["created_at"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">No feedback messages available.</td></tr>
            <?php endif; ?>
        </tbody>

        
    </table>
   

    <div class="back-link"><a href="admin.php">Back to Dashboard</a></div>
    </div>
</body>
</html>