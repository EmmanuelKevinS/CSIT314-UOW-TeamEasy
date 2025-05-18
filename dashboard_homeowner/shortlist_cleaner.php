<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/ShortlistManager.php";

header("Content-Type: text/plain");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'homeowner') {
    echo "unauthorized";
    exit;
}

$homeowner_id = $_SESSION['user_id'];
$cleaner_id = $_POST['cleaner_id'] ?? null;

if ($cleaner_id) {
    $manager = new ShortlistManager($conn);
    $result = $manager->addToShortlist($homeowner_id, $cleaner_id);

    if ($result === "success") {
        echo "success";
    } elseif ($result === "exists") {
        echo "exists";
    } else {
        echo "error";
    }
} else {
    echo "no_cleaner_id";
}
?>