<?php
session_start();
include('../config/dbcon.php');

// Check if the user is an admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch the package details for the specified package_id
if (isset($_GET['package_id'])) {
    $package_id = $_GET['package_id'];
    $query = "SELECT * FROM packages WHERE id = '$package_id'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $package = mysqli_fetch_assoc($result);
    } else {
        echo "Package not found!";
        exit();
    }
}

// Handle form submission for updating package details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Update the package in the database
    $update_query = "UPDATE packages SET name = '$name', description = '$description', price = '$price', image = '$image' WHERE id = '$package_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<div class='alert alert-success'>Package updated successfully!</div>";
        header("Location: admin.php"); // Redirect back to the admin dashboard
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating package: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Package</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Package</h2>
    <a href="admin.php" class="btn btn-secondary mb-3">Back to Admin Dashboard</a>
    
    <!-- Edit Package Form -->
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Package Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($package['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($package['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($package['price']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image URL</label>
            <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($package['image']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Package</button>
    </form>
</div>
</body>
</html>
