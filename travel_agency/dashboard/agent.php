<?php
session_start();
include('../config/dbcon.php');

// Check if user is an agent
if ($_SESSION['role'] != 'agent') {
    header("Location: ../index.php");
    exit();
}

// Handle package creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = !empty($_POST['image']) ? $_POST['image'] : 'path/to/default-image.jpg'; // Default image path
    $created_by = $_SESSION['user_id'];

    // Insert the package as pending
    $query = "INSERT INTO packages (name, description, price, image, created_by, status) 
              VALUES ('$name', '$description', '$price', '$image', '$created_by', 'pending')";
    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'>Package submitted for approval!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: Could not submit package.</div>";
    }
}

// Fetch existing packages created by the agent
$agent_id = $_SESSION['user_id'];
$query = "SELECT * FROM packages WHERE created_by = '$agent_id'";
$result = mysqli_query($conn, $query);
$packages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Agent Dashboard</h2>
    <a href="../config/logout.php" class="btn btn-danger float-end">Logout</a>
    
    <h3 class="mt-4">Add New Package</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Package Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image URL</label>
            <input type="text" class="form-control" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Submit for Approval</button>
    </form>

    <h3 class="mt-5">Your Packages</h3>
    <div class="row">
        <?php foreach ($packages as $package): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo !empty($package['image']) ? htmlspecialchars($package['image']) : 'path/to/default-image.jpg'; ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($package['name'] ?? 'Package Image'); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($package['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($package['description']); ?></p>
                        <p class="card-text text-primary">Price: $<?php echo htmlspecialchars($package['price']); ?></p>
                        <p class="card-text text-muted">Status: <?php echo ucfirst($package['status']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
