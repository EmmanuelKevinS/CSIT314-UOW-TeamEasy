<?php
session_start();
require_once "../db.php";
require_once "../classes/homeowner/BookingManager.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "homeowner") {
        header("Location: ../auth.php");
        exit;
    }

    $homeowner_id = $_SESSION["user_id"];
    $cleaner_id = intval($_POST["cleaner_id"]);
    $date = $_POST["booking_date"];
    $time = $_POST["booking_time"];
    $service_required = $_POST["service_required"];

    $manager = new BookingManager($conn);
    $address = $manager->getHomeownerAddress($homeowner_id);

    if ($manager->createBooking($homeowner_id, $cleaner_id, $service_required, $date, $time, $address)) {
        echo '
        <div style="max-width: 500px; 
        margin: 80px auto; 
        padding: 30px; 
        background-color: white; 
        box-shadow: 0 0 10px #ccc; 
        border-radius: 10px; 
        font-family: Arial, sans-serif; 
        text-align: center;">

            <h2 style="color: black;">Booking Confirmed!</h2>

            <p style="font-size: 16px; color: #333;">Your cleaner has been successfully booked, please wait for cleaner confirmation.</p>
            <a href="homeowner.php" style="display: inline-block; 
            margin-top: 20px; 
            padding: 10px 20px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; ">Return to Dashboard</a>

          

        </div>';

    } else {
        echo "<p>Error processing booking.</p>";
    }

    $conn->close();
}
?>