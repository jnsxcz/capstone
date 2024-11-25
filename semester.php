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

// Handle semester creation or update (for add or edit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['semesters'])) {
    $semester_name = $_POST['semesters'];

    try {
        // Check if editing or adding a semester
        if (isset($_POST['id'])) {
            // Updating an existing semester
            $stmt = $pdo->prepare("UPDATE semester SET semesters = :semesters WHERE sem_id = :id");
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        } else {
            // Adding a new semester with automatic timestamp
            $stmt = $pdo->prepare("INSERT INTO semester (semesters) VALUES (:semesters)");
        }

        // Bind parameters to the query
        $stmt->bindParam(':semesters', $semester_name, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        header("Location: semester.php"); // Redirect to semester page after successful update/add
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch all semesters (for listing)
try {
    $stmt = $pdo->prepare("SELECT * FROM semester");
    $stmt->execute();
    $semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch the semester data for editing if needed
$semesterToEdit = null;
if (isset($_GET['sem_id'])) {
    $id = $_GET['sem_id'];
    $stmt = $pdo->prepare("SELECT * FROM semester WHERE sem_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $semesterToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester Management</title>
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
                            <a href="/Admin/logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <!-- Add Semester Button -->
            <button id="addSemesterBtn" class="add-btn">Add Semester</button>

            <!-- Modal Structure for Adding/Editing -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 class="form-title"><?php echo isset($semesterToEdit) ? 'Edit' : 'Add'; ?> Semester</h2>

                    <form action="semester.php" method="POST">
                        <!-- Hidden ID field for editing -->
                        <?php if ($semesterToEdit): ?>
                            <input type="hidden" name="id" value="<?php echo $semesterToEdit['sem_id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="semesters" class="form-label">Semester Name</label>
                            <input type="text" name="semesters" id="semesters" class="form-input" 
                                   value="<?php echo $semesterToEdit ? htmlspecialchars($semesterToEdit['semesters']) : ''; ?>" 
                                   placeholder="Enter semester name" required>
                        </div>

                        <button type="submit" class="submit-btn"><?php echo isset($semesterToEdit) ? 'Update' : 'Add'; ?> Semester</button>
                    </form>
                </div>
            </div>

            <!-- Semester List -->
            <h2>Semester List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Semester Name</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($semesters)): ?>
                        <?php foreach ($semesters as $semester): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($semester['semesters']); ?></td>
                                <td><?php echo $semester['date_created']; ?></td>
                                <td>
                                    <a href="?sem_id=<?php echo $semester['sem_id']; ?>" class="btn edit-btn">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No semesters found.</td>
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
        var btn = document.getElementById("addSemesterBtn");
        var span = document.getElementsByClassName("close")[0];

        // Open modal for adding new semester
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
        <?php if ($semesterToEdit): ?>
            modal.style.display = "block";
        <?php endif; ?>
    </script>
</body>
</html>
