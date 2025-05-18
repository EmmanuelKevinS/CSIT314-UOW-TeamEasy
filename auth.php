<?php
require_once "db.php";
require_once "classes/UserManager.php";
session_start();

$userManager = new UserManager($conn);

$registerSuccess = $registerError = "";
$loginError = "";

// Handle Registration
if (isset($_POST["register"])) {
    $username = trim($_POST["reg_username"]);
    $password = $_POST["reg_password"];
    $role = $_POST["reg_role"];

    if ($userManager->isUsernameTaken($username)) {
        $registerError = "Username already taken.";
    } else {
        if ($userManager->registerUser($username, $password, $role)) {
            $registerSuccess = "Account registered. You can now log in.";
        } else {
            $registerError = "Registration failed.";
        }
    }
}

// Handle Login
if (isset($_POST["login"])) {
    $username = trim($_POST["log_username"]);
    $password = $_POST["log_password"];
    $role = $_POST["log_role"];

    [$success, $data] = $userManager->loginUser($username, $password, $role);

    if (!$success) {
        $loginError = $data;
    } else {
        $_SESSION["user_id"] = $data["id"];
        $_SESSION["username"] = $data["username"];
        $_SESSION["role"] = $data["role"];

        if ($role === 'admin') {
            header("Location: dashboard_admin/admin.php");
        } elseif ($role === 'cleaner') {
            header("Location: dashboard_cleaner/cleaner.php");
        } elseif ($role === 'homeowner') {
            header("Location: dashboard_homeowner/homeowner.php");
        }
        exit;
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login & Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 40px;
        }
        .card {
            background: white;
            padding: 25px 30px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 20px;
        }
        h2 {
            margin-bottom: 10px;
            color: #333;
            text-align: center;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
<div class="container">
    

   
<div class="card">
    <h2>Register</h2>
    <?php if ($registerError): ?>
        <p class="message error"><?= htmlspecialchars($registerError) ?></p>
    <?php elseif ($registerSuccess): ?>
        <p class="message success"><?= htmlspecialchars($registerSuccess) ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="reg_username" placeholder="Username" required>
        <input type="password" name="reg_password" placeholder="Password" required>
        <select name="reg_role">
            <option value="cleaner">Cleaner</option>
            <option value="homeowner">Homeowner</option>
            <option value="admin">User Admin</option>
        </select>
        <button type="submit" name="register">Register</button>
    </form>
</div>
    <br>
<div class="card">
    <h2>Login</h2>
    <?php if ($loginError): ?>
        <p class="message error"><?= htmlspecialchars($loginError) ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="log_username" placeholder="Username" required>
        <input type="password" name="log_password" placeholder="Password" required>
        <select name="log_role">
            <option value="cleaner">Cleaner</option>
            <option value="homeowner">Homeowner</option>
            <option value="admin">User Admin</option>
        </select>
        <button type="submit" name="login">Login</button>
    </form>
</div>
</div>
</body>
</html>