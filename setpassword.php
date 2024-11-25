<?php
// Include your database connection
include "db.php";  

// Define the username and password you want to store
$username = 'admin';  // The username you're setting (e.g., admin)
$password = 'admin123'; // Assuming the access_id you want to assign is 1 (make sure this value exists in the `accesstype` table)

// Hash the password using bcrypt
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert the username, password, and accesstype_id into the database
$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
$stmt->execute([
    'email' => $username,
    'password' => $hashed_password
]);

echo "Password has been successfully inserted!";
?>
