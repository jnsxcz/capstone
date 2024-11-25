<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cap"; // Replace with your actual database name

// Establishing a PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle class creation or update (for add or edit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['year_level'])) {
    $year_level = $_POST['year_level'];

    try {
        // Check if editing or adding a class
        if (isset($_POST['id'])) {
            // Updating an existing class
            $stmt = $pdo->prepare("UPDATE class SET year_level = :year_level WHERE class_id = :id");
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        } else {
            // Adding a new class
            $stmt = $pdo->prepare("INSERT INTO class (year_level) VALUES (:year_level)");
        }

        // Bind parameters to the query
        $stmt->bindParam(':year_level', $year_level, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        header("Location: class.php"); // Redirect to class page after successful update/add
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch all classes (for listing)
try {
    $stmt = $pdo->prepare("SELECT * FROM class");
    $stmt->execute();
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch the class data for editing if needed
$classToEdit = null;
if (isset($_GET['class_id'])) {
    $id = $_GET['class_id'];
    $stmt = $pdo->prepare("SELECT * FROM class WHERE class_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $classToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/Admin/style.css">
</head>


<body>
    <div class="containerr">
        <div class="navigation">
            <ul>
                <li>
                    <a href="index.php"> <!-- Make sure the link works as per your requirement -->
                        <span class="icon"><ion-icon name="school"></ion-icon></span>
                        <span class="title">NEUST</span>
                    </a>
                </li>

                <li id="dashboard">
                    <a href="/Admin/dashboard.php"><span class="icon"><ion-icon name="home"></ion-icon></span><span class="title">Dashboard</span></a>
                </li>
                <li id="instructor">
                    <a href="/Admin/Instructor/instructor.php"><span class="icon"><ion-icon name="person-add"></ion-icon></span><span class="title">Instructor</span></a>
                </li>
                <li id="student">
                    <a href="student.php"><span class="icon"><ion-icon name="person-add"></ion-icon></span><span class="title">Student</span></a>
                </li>
                <li id="department">
                    <a href="/Admin/department/department.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Department</span></a>
                </li>
                <li id="class">
                    <a href="/Admin/Class/class.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Class</span></a>
                </li>
                <li id="section">
                    <a href="/Admin/section/section.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Section</span></a>
                </li>
                <li id="semester">
                    <a href="/Admin/Semester/semester.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Semester</span></a>
                </li>
                <li id="academic">
                    <a href="/Admin/Acadyear/acad_year.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Academic Year</span></a>
                </li>
                <li id="question">
                    <a href="question.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Question</span></a>
                </li>
                <li id="rate">
                    <a href="rate.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Rate</span></a>
                </li>
                <li id="evaluation">
                    <a href="evaluation.php"><span class="icon"><ion-icon name="desktop"></ion-icon></span><span class="title">Evaluation</span></a>
                </li>
            </ul>
        </div>

        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu"></ion-icon>
                </div>

                <div class="user">
                    <div class="dropdown">
                        <!-- Clickable Image -->
                        <button class="dropdown-btn">
                            <img src="/img/admin.jpg" alt="User Profile" class="profile-img">
                        </button>
                        <!-- Dropdown Menu -->
                        <div class="dropdown-content">
                            <a href="#">Manage Account</a>
                            <a href="/Admin/logout.php">Logout</a>
                            <!-- PHP to log out user -->
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <!-- Add Class Button -->
            <button id="addClassBtn" class="add-btn">Add Class</button>

            <!-- Modal Structure for Adding/Editing -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 class="form-title"><?php echo isset($classToEdit) ? 'Edit' : 'Add'; ?> Class</h2>

                    <form action="class.php" method="POST">
                        <!-- Hidden ID field for editing -->
                        <?php if ($classToEdit): ?>
                            <input type="hidden" name="id" value="<?php echo $classToEdit['class_id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="year_level" class="form-label">Year Level</label>
                            <input type="text" name="year_level" id="year_level" class="form-input" 
                                   value="<?php echo $classToEdit ? htmlspecialchars($classToEdit['year_level']) : ''; ?>" 
                                   placeholder="Enter year level" required>
                        </div>

                        <button type="submit" class="submit-btn"><?php echo isset($classToEdit) ? 'Update' : 'Add'; ?> Class</button>
                    </form>
                </div>
            </div>

            <!-- Class List -->
            <h2>Class List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Year Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($classes)): ?>
                        <?php foreach ($classes as $class): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($class['year_level']); ?></td>
                                <td>
                                    <a href="?class_id=<?php echo $class['class_id']; ?>" class="btn edit-btn">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No classes found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="/Admin/main.js"></script>
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    
    <script>
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("addClassBtn");
        var span = document.getElementsByClassName("close")[0];

        // Open modal for adding new class
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Close modal when clicking the close button
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal if clicked outside of modal content
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Open modal automatically if editing
        <?php if ($classToEdit): ?>
            modal.style.display = "block";
        <?php endif; ?>
    </script>
</body>
</html>