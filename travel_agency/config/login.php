<?php
session_start();
include('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Validate password and set session
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: ../dashboard/admin.php");
        } elseif ($user['role'] == 'agent') {
            header("Location: ../dashboard/agent.php");
        } else {
            header("Location: ../dashboard/traveler.php");
        }
        exit();
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>Invalid email or password.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Body and Background */
        body {
            background: linear-gradient(to top right, #6a11cb, #2575fc);
            height: 100vh;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* Add Background Image */
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/travel.jpg') no-repeat center center fixed;

            background-size: cover;
            opacity: 0.4;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% { background-position: 0 0; }
            100% { background-position: 100% 100%; }
        }

        /* Login Container */
        .login-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        .floating-labels {
            position: relative;
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            border: none;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.6);
            outline: none;
        }

        label {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 14px;
            color: #aaa;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .form-control:focus ~ label, .form-control:not(:focus):valid ~ label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: #007bff;
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            background: linear-gradient(to right, #ff4b2b, #ff416c);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            margin-top: 20px;
            text-align: center;
            color: red;
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                width: 80%;
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            .form-control {
                padding: 12px;
            }

            .btn-primary {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<div class="background"></div>

<div class="login-container">
    <h2>Welcome Back!</h2>
    <form method="POST">
        <!-- Email Input Field -->
        <div class="mb-3 floating-labels">
            <input type="email" class="form-control" id="email" name="email" required placeholder=" " aria-label="Email Address">
            <label for="email">Email Address</label>
        </div>

        <!-- Password Input Field -->
        <div class="mb-3 floating-labels">
            <input type="password" class="form-control" id="password" name="password" required placeholder=" " aria-label="Password">
            <label for="password">Password</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <!-- Registration Link -->
    <div class="register-link">
        <p>Don't have an account? <a href="register.php">Register Here</a></p>
    </div>
</div>

</body>
</html>
