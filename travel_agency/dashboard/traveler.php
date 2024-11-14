<?php
session_start();
include('../config/dbcon.php');

// Check if user is a traveler
if ($_SESSION['role'] != 'traveler') {
    header("Location: ../index.php");
    exit();
}

// Fetch only approved packages
$query = "SELECT * FROM packages WHERE status = 'approved'";
$result = mysqli_query($conn, $query);

if ($result) {
    $packages = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($conn);  // For debugging if the query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traveler Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white text-center p-4">
        <h1>Welcome to the Traveler Dashboard</h1>
        <a href="../config/logout.php" class="btn btn-danger float-end">Logout</a>
    </header>

    <main class="container mt-4">
        <h2>Available Travel Packages</h2>
        <div class="row">
            <?php if (count($packages) > 0): ?>
                <?php foreach ($packages as $package): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($package['image'])): ?>
                                <img src="<?php echo htmlspecialchars($package['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($package['name'] ?? 'Package Image'); ?>">
                            <?php else: ?>
                                <img src="path/to/default-image.jpg" class="card-img-top" alt="Default Image">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($package['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($package['description']); ?></p>
                                <p class="card-text text-primary">Price: $<?php echo htmlspecialchars($package['price']); ?></p>
                                <a href="../config/book.php?package_id=<?php echo $package['id']; ?>" class="btn btn-success">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No approved packages available at the moment.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
