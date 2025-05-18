<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/CleanerSearch.php";
require_once "../classes/homeowner/ShortlistManager.php";

$searchManager = new CleanerSearch($conn);

$category = $_GET['category'] ?? '';
$sortOrder = $_GET['sort'] ?? '';
$results = null;

if (!empty($category)) {
    $results = $searchManager->searchCleaners($category, $sortOrder);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Cleaners</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        select, button {
            padding: 8px 14px;
            margin: 0 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        
        
        a.sort-link {
            margin: 0 10px;
            text-align: center;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007bff;
        }
        a.sort-link:hover {
            text-decoration: underline;
        }

        p {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th {
            background-color: #007bff;
            color: white;
            text-align: left;
            padding: 10px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button.view-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
        }
        button.view-btn:hover {
            background-color: #0056b3;
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
    <h2>Search Cleaners by Service</h2>

    <form method="get" action="">
        <label>Choose a service category:</label>
        <select name="category" required>
            <option value="">--Select--</option>
            <option value="all" <?= ($category === 'all') ? 'selected' : '' ?>>Everything</option>
            <option value="Carpet" <?= ($category === 'Carpet') ? 'selected' : '' ?>>Carpet</option>
            <option value="Bathroom" <?= ($category === 'Bathroom') ? 'selected' : '' ?>>Bathroom</option>
            <option value="Kitchen" <?= ($category === 'Kitchen') ? 'selected' : '' ?>>Kitchen</option>
            <option value="Living Room" <?= ($category === 'Living Room') ? 'selected' : '' ?>>Living Room</option>
            <option value="High Ceiling" <?= ($category === 'High Ceiling') ? 'selected' : '' ?>>High Ceiling</option>
        </select>
        
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($category)): ?>
        <p>
            Sort by:
            <a class="sort-link" href="?category=<?= urlencode($category) ?>&sort=price_asc">Lowest to Highest</a> |
            <a class="sort-link" href="?category=<?= urlencode($category) ?>&sort=price_desc">Highest to Lowest</a>
        </p>

        <?php if ($results && $results->num_rows > 0): ?>
            <h3>Results for: <?= htmlspecialchars($category === 'all' ? 'Everything' : $category) ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Cleaner Name</th>
                        <th>Services Offered</th>
                        <th>Service Fee (SGD)</th>
                        <th>Click to View Profile</th>
                        <th>Shortlist</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['service_type']) ?></td>
                            <td><?= number_format($row['service_fee'], 2) ?></td>
                            <td>
                                <a href="view_cleaner_profile.php?id=<?= $row['user_id'] ?>">
                                    <button class="view-btn">View</button>
                                </a>
                            </td>
                            <td>
                                <?php
                                
                                $shortlistManager = new ShortlistManager($conn);
                                $is_shortlisted = $shortlistManager->isShortlisted($_SESSION['user_id'], $row['user_id']);
                                ?>

                                <?php if ($is_shortlisted): ?>
                                    <button disabled style="background-color: grey;">Shortlisted</button>
                                <?php else: ?>
                                    <button class="shortlist-btn" data-cleaner-id="<?= $row['user_id'] ?>">Shortlist</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                       
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <br>
            <p>No cleaners found for "<?= htmlspecialchars($category) ?>"</p>
        <?php endif; ?>
    <?php endif; ?>

   
    <div class="back-link">
        <a href="homeowner.php">Back to Dashboard</a>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".shortlist-btn").forEach(button => {
        button.addEventListener("click", function () {
            const cleanerId = this.getAttribute("data-cleaner-id");
            const btn = this;

            fetch("shortlist_cleaner.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "cleaner_id=" + encodeURIComponent(cleanerId)
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success" || data === "exists") {
                    btn.textContent = "Shortlisted";
                    btn.disabled = true;
                    btn.style.backgroundColor = "grey";
                } else {
                    alert("Error shortlisting cleaner: " + data);
                }
            });
        });
    });
});
</script>

</body>
</html>