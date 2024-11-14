<?php
session_start();
include('dbcon.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['package_id'])) {
    $package_id = $_GET['package_id'];
    $user_id = $_SESSION['user_id'];
    
    $query = "INSERT INTO bookings (user_id, package_id, payment_status) VALUES ('$user_id', '$package_id', 'Pending')";
    mysqli_query($conn, $query);
    echo "Booking successful!";
} else {
    echo "Invalid package!";
}
?>
