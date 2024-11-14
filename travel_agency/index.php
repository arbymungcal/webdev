<?php
session_start();
include 'config/dbcon.php'; // Include database connection

// Fetch packages from the database
$query = "SELECT * FROM packages"; // Assuming 'packages' is the table name
$result = mysqli_query($conn, $query);

// Check for any errors in the query
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$packages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agency - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Header styles */
        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        /* Main styles */
        main {
            padding: 20px;
        }

        .packages {
            margin: 20px 0;
        }

        .package-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .package-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            padding: 15px;
            width: calc(33.33% - 20px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .package-item:hover {
            transform: scale(1.05);
        }

        .package-item img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .price {
            font-weight: bold;
            color: #007bff;
        }

        .book-button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .book-button:hover {
            background-color: #218838;
        }

        /* Footer styles */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Modal styles */
        .modal-content {
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        }

        .modal-header {
            background-color: #ff6b6b; /* Header background color */
            color: white; /* Header text color */
        }

        .modal-body {
            padding: 20px; /* Padding for body */
        }

        .form-control {
            border-radius: 5px; /* Rounded input fields */
            border: 1px solid #ccc; /* Border color */
        }

        .btn-primary {
            background-color: #ff6b6b; /* Button color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded button */
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Our Travel Agency</h1>
        <nav>
            <ul>
                <li><a href="config/register.php">Register</a></li>
                <!-- Button to trigger login modal -->
                <li><button class="btn btn-link text-white" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button></li>
                <li><a href="config/packages.php"> Packages</a></li>
            </ul>
        </nav>
    </header>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="config/login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                        <p class="mt-2">Don't have an account? <a href="#" id="switchToRegister">Register here</a></p>
                    </form>

                    <form id="registrationForm" style="display: none;" action="config/register.php" method="POST">
                        <div class="mb-3">
                            <label for="regUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="regUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="regEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="regEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="regPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="regPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                        <p class="mt-2">Already have an account? <a href="#" id="switchToLogin">Login here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <main>
        <section class="packages">
            <h2>Available Travel Packages</h2>
            <div class="package-list">
                <?php if (count($packages) > 0): ?>
                    <?php foreach ($packages as $package): ?>
                        <div class="package-item">
                            <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                            <img src="<?php echo htmlspecialchars($package['image']); ?>" alt="<?php echo htmlspecialchars($package['name']); ?>">
                            <p><?php echo htmlspecialchars($package['description']); ?></p>
                            <p class="price">Price: $<?php echo htmlspecialchars($package['price']); ?></p>
                            <a class="book-button" href="config/book.php?package_id=<?php echo $package['id']; ?>">Book Now</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No packages available at the moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('switchToRegister').addEventListener('click', function() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registrationForm').style.display = 'block';
            document.getElementById('loginModalLabel').innerText = 'Register';
        });

        document.getElementById('switchToLogin').addEventListener('click', function() {
            document.getElementById('registrationForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('loginModalLabel').innerText = 'Login';
        });
    </script>
</body>
</html>
