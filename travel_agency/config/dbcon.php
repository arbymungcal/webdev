<?php
// Database connection details
$servername = "localhost";
$username = "root"; // change as needed
$password = ""; // change as needed
$dbname = "travel_agency"; // change as needed

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
