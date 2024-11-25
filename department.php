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

// Handle department creation or update (for add or edit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['department']) && isset($_POST['description'])) {
    $department = $_POST['department'];
    $description = $_POST['description'];

    try {
        // Check if editing or adding a department
        if (isset($_POST['id'])) {
            // Updating an existing department
            $stmt = $pdo->prepare("UPDATE department SET department = :department, description = :description WHERE dep_id = :id");
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        } else {
            // Adding a new department
            $stmt = $pdo->prepare("INSERT INTO department (department, description) VALUES (:department, :description)");
        }

        // Bind parameters to the query
        $stmt->bindParam(':department', $department, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        header("Location: department.php"); // Redirect to department page after successful update/add
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch all departments (for listing)
try {
    $stmt = $pdo->prepare("SELECT * FROM department");
    $stmt->execute();
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch the department data for editing if needed
$departmentToEdit = null;
if (isset($_GET['dep_id'])) {
    $id = $_GET['dep_id'];
    $stmt = $pdo->prepare("SELECT * FROM department WHERE dep_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $departmentToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all active departments (for listing)
try {
    $stmt = $pdo->prepare("SELECT * FROM department WHERE status = 'active'");
    $stmt->execute();
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch archived departments
$archivedDepartments = [];
if (isset($_GET['archived']) && $_GET['archived'] == 'true') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM department WHERE status = 'archived'");
        $stmt->execute();
        $archivedDepartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
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
                    <a href="/Admin//dashboard.php">
                        <span class="icon"><ion-icon name="home"></ion-icon></span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li id="instructor">
                    <a href="/Admin/Instructor/instructor.php">
                        <span class="icon"><ion-icon name="person-add"></ion-icon></span>
                        <span class="title">Instructor</span>
                    </a>
                </li>

                <li id="student">
                    <a href="student.php">
                        <span class="icon"><ion-icon name="person-add"></ion-icon></span>
                        <span class="title">Student</span>
                    </a>
                </li>

                <li id="department">
                    <a href="/Admin/department/department.php">
                        <span class="icon"><ion-icon name="desktop"></ion-icon></span>
                        <span class="title">Department</span>
                    </a>
                </li>

                <li id="class">
                    <a href="/Admin/Class/class.php">
                        <span class="icon"><ion-icon name="desktop"></ion-icon></span>
                        <span class="title">Class</span>
                    </a>
                </li>

                <li id="section">
                    <a href="/Admin/section/section.php">
                        <span class="icon"><ion-icon name="desktop"></ion-icon></span>
                        <span class="title">Section</span>
                    </a>
                </li>

                <li id="semester">
                    <a href="/Admin/Semester/semester.php">
                        <span class="icon"><ion-icon name="desktop"></ion-icon></span>
                        <span class="title">Semester</span>
                    </a>
                </li>

                <li id="academic">
                    <a href="/Admin/Acadyear/acad_year.php">
                        <span class="icon"><ion-icon name="desktop"></ion-icon></span>
                        <span class="title">Academic Year</span>
                    </a>
                </li>

                <li id="question">
                    <a href="question.php">
                        <span class="icon"><ion-icon name="desktop"></ion-icon></span>
                        <span class="title">Question</span>
                    </a>
                </li>

                <li id="rate">
                    <a href="rate.php">
                        <span class="icon"><ion-icon name="desktop"></ion-icon></span>
                        <span class="title">Rate</span>
                    </a>
                </li>

                <li id="evaluation">
                    <a href="evaluation.php">
                        <span class="icon"><ion-icon name="desktop"></ion-icon></span>
                        <span class="title">Evaluation</span>
                    </a>
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
                            <a href="logout.php">Logout</a>
                            <!-- PHP to log out user -->
                        </div>
                    </div>
                </div>
            </div>
<br> 
<button id="addDeptBtn" class="add-btn">Add Department</button>


            <!-- Modal Structure for Adding/Editing -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 class="form-title"><?php echo isset($departmentToEdit) ? 'Edit' : 'Add'; ?> Department</h2>

                    <form action="department.php" method="POST">
                        <!-- Hidden ID field for editing -->
                        <?php if ($departmentToEdit): ?>
                            <input type="hidden" name="id" value="<?php echo $departmentToEdit['dep_id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="department" class="form-label">Department Name</label>
                            <input type="text" name="department" id="department" class="form-input" 
                                   value="<?php echo $departmentToEdit ? htmlspecialchars($departmentToEdit['department']) : ''; ?>" 
                                   placeholder="Enter department name" required>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-input" rows="4" 
                                      placeholder="Enter department description"><?php echo $departmentToEdit ? htmlspecialchars($departmentToEdit['description']) : ''; ?></textarea>
                        </div>

                        <button type="submit" class="submit-btn"><?php echo isset($departmentToEdit) ? 'Update' : 'Add'; ?> Department</button>
                    </form>
                </div>
            </div>

            <!-- Button to View Archived Departments -->
            <a href="department.php<?php echo isset($_GET['archived']) && $_GET['archived'] == 'true' ? '' : '?archived=true'; ?>" class="btn view-archived-btn">
                <?php echo isset($_GET['archived']) && $_GET['archived'] == 'true' ? 'View Active Departments' : 'View Archived Departments'; ?>
            </a>

           <!-- Department List -->
<h2>Department List</h2>
<table>
    <thead>
        <tr>
            <th>Department Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($departments)): ?>
            <?php foreach ($departments as $department): ?>
                <tr>
                    <td><?php echo htmlspecialchars($department['department']); ?></td>
                    <td><?php echo htmlspecialchars($department['description']); ?></td>
                    <td>
                        <a href="?dep_id=<?php echo $department['dep_id']; ?>" class="btn edit-btn">Edit</a>
                        <a href="archive_department.php?id=<?php echo $department['dep_id']; ?>" class="btn archive-btn">Archive</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No active departments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
        
<!-- Archived Departments Section -->
<?php if (isset($_GET['archived']) && $_GET['archived'] == 'true'): ?>
    <h2>Archived Departments</h2>
    <table>
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($archivedDepartments)): ?>
                <?php foreach ($archivedDepartments as $department): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($department['department']); ?></td>
                        <td><?php echo htmlspecialchars($department['description']); ?></td>
                        <td>
                            <a href="restore_department.php?id=<?php echo $department['dep_id']; ?>" class="btn restore-btn">Restore</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No archived departments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

    <script src="/Admin/main.js"></script>

    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    
    <script>
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("addDeptBtn");
        var span = document.getElementsByClassName("close")[0];

        // Open modal for adding new department
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
        <?php if ($departmentToEdit): ?>
            modal.style.display = "block";
        <?php endif; ?>
    </script>
</body>
</html>