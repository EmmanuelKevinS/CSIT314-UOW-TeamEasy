<?php
session_start();
require_once "../db.php";
require_once "../classes/admin/ServiceCategoryManager.php";



if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$manager = new ServiceCategoryManager($conn);

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['category_name'] ?? '');
    if ($name !== '') {
        $manager->addCategory($name);
        header("Location: manage_service_categories.php");
        exit();
    }
}

// Handle Delete
$deleteMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    if (!$manager->deleteCategory($id)) {
        $deleteMessage = "Cannot delete category. It is currently assigned to one or more cleaners.";
    } else {
        header("Location: manage_service_categories.php");
        exit();
    }
}

$categories = $manager->getAllCategories();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Service Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            flex: 1;
            padding: 8px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
            
        }
        button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            
            background-color:rgb(38, 107, 218);
          
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
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
        .delete-btn {
            background-color:  #dc3545;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color:rgb(228, 33, 52);
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
        <h2>Manage Service Categories</h2>

        <?php if (!empty($deleteMessage)): ?>
            <p style="color: red; text-align: center;"><?= htmlspecialchars($deleteMessage) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="category_name" placeholder="New category name" required>
            <button type="submit" name="add_category">Add</button>
        </form>

        <table>
            <thead>
                <tr><th>Category</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php while ($row = $categories->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="delete_id" value="<?= (int)$row['category_id'] ?>">
                                <button class="delete-btn" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="admin.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>