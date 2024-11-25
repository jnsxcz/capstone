<?php
// Start session for user authentication
session_start();

// Check if the user is already logged in, redirect to home page
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Define error variables
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if username and password are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Get the input values
        $username = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'cap');

        // Check if connection was successful
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Prepare SQL query to fetch user data
        $stmt = $conn->prepare('SELECT user_id, email, password FROM users WHERE email = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        
        // Check if username exists in the database
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $db_username, $db_password);
            $stmt->fetch();

            // Verify password using password_hash
            if (password_verify($password, $db_password)) {
                // Password is correct, start session and save user data
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $db_username;
                
                // Redirect to home page or dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Please fill in both fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEUST-MGT Faculty Evaluation System - Login</title>
    <!-- Google Font for Trendy Look -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(145deg, #6e7dff, #3a4fcd);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        /* Container for the login form */
        .login-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 20px 20px 60px #d3d9e0, -20px -20px 60px #ffffff;
            padding: 40px;
            width: 100%;
            max-width: 380px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .login-container:hover {
            box-shadow: 30px 30px 100px #d3d9e0, -30px -30px 100px #ffffff;
        }

        /* Heading Styles */
        h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        /* Input Field Styles */
        input[type="text"], input[type="password"] {
            width: 80%;
            padding: 15px 20px;
            margin: 10px 0 20px 0; /* Adjusted margins for spacing */
            border-radius: 12px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
        }

        /* Input focus state for trendy look */
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #6e7dff;
            background-color: #fff;
        }

        /* Icon inside input field (using font-awesome or material icons can be a cool touch) */
        .input-wrapper {
            position: relative;
            text-align: left;
            width: 100%;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        input[type="text"], input[type="password"] {
            padding-left: 40px; /* Adjust for icons */
        }

        /* Button Styles */
        button {
            background-color: #6e7dff;
            color: white;
            padding: 15px;
            width: 100%;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 20px; /* Added top margin to separate button */
        }

        button:hover {
            background-color: #5a6dff;
            transform: translateY(-2px);
        }

        /* Error message style */
        p.error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: left;
            padding-left: 10px;
        }

        /* Label Styles */
        label {
            font-size: 14px;
            text-align: left;
            color: #444;
            margin-bottom: 8px;
            display: block;
            padding-left: 10px; /* Slight padding for better alignment */
        }

        /* Footer or small info link */
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #aaa;
        }
        .footer a {
            color: #6e7dff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>NEUST-MGT Faculty Evaluation System</h2>
        
        <!-- Error Message (conditionally displayed) -->
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="">
            <!-- Username Input -->
            <div class="input-wrapper">
                <label for="email">Username</label>
                <input type="text" id="email" name="email" required placeholder="Enter your username">
            </div>
            
            <!-- Password Input -->
            <div class="input-wrapper">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            
            <button type="submit">Login</button>
        </form>

    </div>
</body>
</html>
