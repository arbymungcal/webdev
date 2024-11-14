<?php
session_start();
include 'dbcon.php'; // Include database connection

// Fetch reviews from the database
$query = "SELECT * FROM reviews"; // Assuming 'reviews' is the table name
$result = mysqli_query($conn, $query);
$reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reviews</title>
    <style>
        /* Reset some default browser styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
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

        /* Main styles */
        main {
            margin-top: 20px;
        }

        h2 {
            margin-bottom: 15px;
            color: #007bff;
        }

        .review-list {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .review-item {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .review-item h3 {
            margin-bottom: 5px;
            color: #333;
        }

        /* Footer styles */
        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>User Reviews</h1>
    </header>

    <main>
        <h2>What Our Travelers Say</h2>
        <div class="review-list">
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <h3><?php echo htmlspecialchars($review['title']); ?></h3>
                    <p><?php echo htmlspecialchars($review['content']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Travel Agency. All rights reserved.</p>
    </footer>
</body>
</html>
