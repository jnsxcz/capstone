<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cap"; // Your database name

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Restore section
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Set the section status back to 'active'
        $stmt = $pdo->prepare("UPDATE section SET status = 'active' WHERE section_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect back to the section page
        header("Location: section.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
