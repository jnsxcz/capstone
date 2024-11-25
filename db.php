<?php
//config of databasae
$servername = "localhost";
$username = "root";
$password ="";
$dbname = "cap";


//Establishing a PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>