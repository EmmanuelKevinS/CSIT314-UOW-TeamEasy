<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['cleaner_id'])) {
    http_response_code(400);
    exit("Invalid request");
}

$homeowner_id = $_SESSION['user_id'];
$cleaner_id = intval($_POST['cleaner_id']);

$stmt = $conn->prepare("DELETE FROM cleaner_shortlists WHERE homeowner_id = ? AND cleaner_id = ?");
$stmt->bind_param("ii", $homeowner_id, $cleaner_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>