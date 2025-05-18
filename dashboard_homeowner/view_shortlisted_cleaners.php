<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/ShortlistManager.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$homeowner_id = $_SESSION['user_id'];
$shortlist = new ShortlistManager($conn);
$results = $shortlist->getShortlistedCleaners($homeowner_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Shortlisted Cleaners</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 30px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .view-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .remove-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
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
    <h2>My Shortlisted Cleaners</h2>

    <?php if ($results && $results->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Service Type</th>
                    <th>Fee (SGD)</th>
                    <th>View Profile</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["service_type"]) ?></td>
                        <td><?= number_format($row["service_fee"], 2) ?></td>
                        <td>
                            <form method="GET" action="view_cleaner_profile.php" style="margin:0;">
                                <input type="hidden" name="id" value="<?= $row["user_id"] ?>">
                                <button type="submit" class="view-btn">View</button>
                            </form>
                        </td>
                        <td>
                            <button class="remove-btn" data-cleaner-id="<?= $row['user_id'] ?>">Remove</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;">You haven’t shortlisted any cleaners yet.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="homeowner.php">Back to Dashboard</a>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".remove-btn").forEach(button => {
        button.addEventListener("click", function () {
            const cleanerId = this.getAttribute("data-cleaner-id");
            const row = this.closest("tr");

            if (!confirm("Remove this cleaner from your shortlist?")) return;

            fetch("remove_shortlist.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "cleaner_id=" + cleanerId
            })
            .then(res => res.text())
            .then(response => {
                if (response === "success") {
                    row.remove(); // visually remove the row
                } else {
                    alert("Failed to remove cleaner from shortlist.");
                }
            });
        });
    });
});
</script>

</body>
</html>