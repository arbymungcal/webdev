<?php
session_start();
include('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Packages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Travel Packages</h2>
    <div class="row">
        <?php
        $query = "SELECT * FROM packages";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-md-4">';
            echo '<div class="card mb-4 shadow-sm">';
            echo '<img src="images/'.$row['image'].'" class="card-img-top" alt="Package Image">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">'.$row['name'].'</h5>';
            echo '<p class="card-text">'.$row['description'].'</p>';
            echo '<p class="card-text"><strong>Price: $'.$row['price'].'</strong></p>';
            echo '<a href="book.php?package_id='.$row['id'].'" class="btn btn-primary">Book Now</a>';
            echo '</div></div></div>';
        }
        ?>
    </div>
</div>
</body>
</html>
