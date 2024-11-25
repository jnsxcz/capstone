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

// Handle academic year creation or update (for add or edit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['year_start'])) {
    $year_start = $_POST['year_start'];
    $isActive = isset($_POST['isActive']) ? 1 : 0; // Default active is checked (1) or unchecked (0)

    try {
        // Check if editing or adding an academic year
        if (isset($_POST['ay_id'])) {
            // Updating an existing academic year
            $stmt = $pdo->prepare("UPDATE acad_year SET year_start = :year_start, isActive = :isActive WHERE ay_id = :ay_id");
            $stmt->bindParam(':ay_id', $_POST['ay_id'], PDO::PARAM_INT);
        } else {
            // Adding a new academic year
            $stmt = $pdo->prepare("INSERT INTO acad_year (year_start, isActive) VALUES (:year_start, :isActive)");
        }

        // Bind parameters to the query
        $stmt->bindParam(':year_start', $year_start, PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $isActive, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        header("Location: acad_year.php"); // Redirect to acad_year page after successful update/add
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle archiving an academic year (set isActive to 0)
if (isset($_GET['archive']) && isset($_GET['ay_id'])) {
    $ay_id = $_GET['ay_id'];
    try {
        $stmt = $pdo->prepare("UPDATE acad_year SET isActive = 0 WHERE ay_id = :ay_id");
        $stmt->bindParam(':ay_id', $ay_id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: acad_year.php"); // Redirect after archiving
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle restoring an archived academic year (set isActive to 1)
if (isset($_GET['restore']) && isset($_GET['ay_id'])) {
    $ay_id = $_GET['ay_id'];
    try {
        $stmt = $pdo->prepare("UPDATE acad_year SET isActive = 1 WHERE ay_id = :ay_id");
        $stmt->bindParam(':ay_id', $ay_id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: acad_year.php"); // Redirect after restoring
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch all academic years (for listing)
try {
    $stmt = $pdo->prepare("SELECT * FROM acad_year WHERE isActive = 1");
    $stmt->execute();
    $academicYears = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch all archived academic years (if needed)
$archivedAcademicYears = [];
if (isset($_GET['archived']) && $_GET['archived'] == 'true') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM acad_year WHERE isActive = 0");
        $stmt->execute();
        $archivedAcademicYears = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch the academic year data for editing if needed
$academicYearToEdit = null;
if (isset($_GET['ay_id'])) {
    $id = $_GET['ay_id'];
    $stmt = $pdo->prepare("SELECT * FROM acad_year WHERE ay_id = :ay_id");
    $stmt->bindParam(':ay_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $academicYearToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
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
                    <a href="index.php">
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
                        <button class="dropdown-btn">
                            <img src="/img/admin.jpg" alt="User Profile" class="profile-img">
                        </button>
                        <div class="dropdown-content">
                            <a href="#">Manage Account</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <!-- Button to Add Academic Year -->
            <button id="addAcadYearBtn" class="add-btn">Add Academic Year</button>

            <!-- Modal Structure for Adding/Editing -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 class="form-title"><?php echo isset($academicYearToEdit) ? 'Edit' : 'Add'; ?> Academic Year</h2>

                    <form action="acad_year.php" method="POST">
                        <!-- Hidden ID field for editing -->
                        <?php if ($academicYearToEdit): ?>
                            <input type="hidden" name="ay_id" value="<?php echo $academicYearToEdit['ay_id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="year_start" class="form-label">Year Start</label>
                            <input type="number" name="year_start" id="year_start" class="form-input" 
                                   value="<?php echo $academicYearToEdit ? htmlspecialchars($academicYearToEdit['year_start']) : ''; ?>" 
                                   placeholder="Enter year start" required>
                        </div>

                        <div class="form-group">
                            <label for="isActive" class="form-label">Active</label>
                            <input type="checkbox" name="isActive" id="isActive" <?php echo $academicYearToEdit && $academicYearToEdit['isActive'] ? 'checked' : ''; ?>>
                        </div>

                        <button type="submit" class="submit-btn"><?php echo isset($academicYearToEdit) ? 'Update' : 'Add'; ?> Academic Year</button>
                    </form>
                </div>
            </div>

            <!-- Link to view archived academic years -->
            <a href="acad_year.php<?php echo isset($_GET['archived']) && $_GET['archived'] == 'true' ? '' : '?archived=true'; ?>" class="btn view-archived-btn">
                <?php echo isset($_GET['archived']) && $_GET['archived'] == 'true' ? 'View Active Academic Years' : 'View Archived Academic Years'; ?>
            </a>

            <!-- Academic Year List -->
            <h2>Academic Year List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Year Start</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($academicYears)): ?>
                        <?php foreach ($academicYears as $acadYear): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($acadYear['year_start']); ?></td>
                                <td><?php echo $acadYear['isActive'] == 1 ? 'Active' : 'Archived'; ?></td>
                                <td>
                                    <a href="?ay_id=<?php echo $acadYear['ay_id']; ?>" class="btn edit-btn">Edit</a>
                                    <a href="?archive=true&ay_id=<?php echo $acadYear['ay_id']; ?>" class="btn archive-btn">Archive</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No active academic years found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Archived Academic Years Section -->
            <?php if (isset($_GET['archived']) && $_GET['archived'] == 'true'): ?>
                <h2>Archived Academic Years</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Year Start</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($archivedAcademicYears)): ?>
                            <?php foreach ($archivedAcademicYears as $acadYear): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($acadYear['year_start']); ?></td>
                                    <td><?php echo $acadYear['isActive'] == 1 ? 'Active' : 'Archived'; ?></td>
                                    <td>
                                        <a href="?restore=true&ay_id=<?php echo $acadYear['ay_id']; ?>" class="btn restore-btn">Restore</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No archived academic years found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <script src="/Admin/main.js"></script>
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    
    <script>
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("addAcadYearBtn");
        var span = document.getElementsByClassName("close")[0];

        // Open modal for adding new academic year
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
    </script>
</body>
</html>
