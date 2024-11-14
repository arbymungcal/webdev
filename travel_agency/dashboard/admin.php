<?php
session_start();
include('../config/dbcon.php');

// Check if user is an admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Handle package approval
if (isset($_GET['approve_id'])) {
    $package_id = $_GET['approve_id'];
    $query = "UPDATE packages SET status = 'approved' WHERE id = '$package_id'";
    mysqli_query($conn, $query);
    header("Location: admin.php");
    exit();
}

// Handle package disapproval (deletion)
if (isset($_GET['disapprove_id'])) {
    $package_id = $_GET['disapprove_id'];
    // First, delete any associated image or related data (if necessary)
    $delete_query = "DELETE FROM packages WHERE id = '$package_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<div class='alert alert-success'>Package deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting package: " . mysqli_error($conn) . "</div>";
    }
    header("Location: admin.php");
    exit();
}

// Handle package editing
if (isset($_POST['edit_package'])) {
    $package_id = $_POST['package_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = !empty($_POST['image']) ? $_POST['image'] : null;

    $update_query = "UPDATE packages SET name = '$name', description = '$description', price = '$price'";
    if ($image) {
        $update_query .= ", image = '$image'";
    }
    $update_query .= " WHERE id = '$package_id'";

    if (mysqli_query($conn, $update_query)) {
        echo "<div class='alert alert-success'>Package updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating package: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch all packages with pending status
$query = "SELECT packages.*, users.name AS agent_name FROM packages 
          LEFT JOIN users ON packages.created_by = users.id WHERE packages.status = 'pending'";
$result = mysqli_query($conn, $query);
$packages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <a href="../config/logout.php" class="btn btn-danger float-end">Logout</a>

    <h3 class="mt-4">Pending Packages</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Package Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($packages as $package): ?>
                <tr>
                    <td><?php echo htmlspecialchars($package['name']); ?></td>
                    <td><?php echo htmlspecialchars($package['description']); ?></td>
                    <td><?php echo htmlspecialchars($package['price']); ?></td>
                    <td><?php echo htmlspecialchars($package['agent_name']); ?></td>
                    <td>
                        <a href="admin.php?approve_id=<?php echo $package['id']; ?>" class="btn btn-success">Approve</a>
                        <a href="admin.php?disapprove_id=<?php echo $package['id']; ?>" class="btn btn-danger">Disapprove</a>
                        <a href="admin.php?edit_id=<?php echo $package['id']; ?>" class="btn btn-warning">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    // If 'edit_id' is set, show the edit form for the selected package
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $query = "SELECT * FROM packages WHERE id = '$edit_id'";
        $edit_result = mysqli_query($conn, $query);
        $edit_package = mysqli_fetch_assoc($edit_result);

        if ($edit_package): ?>
            <h3 class="mt-5">Edit Package</h3>
            <form method="POST">
                <input type="hidden" name="package_id" value="<?php echo $edit_package['id']; ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Package Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($edit_package['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($edit_package['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($edit_package['price']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image URL</label>
                    <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($edit_package['image']); ?>">
                </div>
                <button type="submit" name="edit_package" class="btn btn-primary">Update Package</button>
            </form>
        <?php endif;
    }
    ?>
</div>
</body>
</html>
