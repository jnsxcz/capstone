<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

echo 'Welcome, ' . htmlspecialchars($_SESSION['username']) . '!<br>';
echo '<a href="logout.php">Logout</a>';
?>
