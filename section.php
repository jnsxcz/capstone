<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cap"; // Your database name

// Establishing a PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch all sections (active or archived)
$sections = [];
$archivedSections = [];

if (isset($_GET['archived']) && $_GET['archived'] == 'true') {
    // Fetch archived sections
    try {
        $stmt = $pdo->prepare("SELECT * FROM section WHERE status = 'archived'");
        $stmt->execute();
        $archivedSections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Fetch active sections
    try {
        $stmt = $pdo->prepare("SELECT * FROM section WHERE status = 'active'");
        $stmt->execute();
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle section creation or update (for add or edit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['section'])) {
    $section = $_POST['section'];

    try {
        // Check if editing or adding a section
        if (isset($_POST['id'])) {
            // Updating an existing section (without description)
            $stmt = $pdo->prepare("UPDATE section SET sections = :section WHERE section_id = :id");
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        } else {
            // Adding a new section (without description)
            $stmt = $pdo->prepare("INSERT INTO section (sections, status) VALUES (:section, 'active')");
        }

        // Bind parameters to the query
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        header("Location: section.php"); // Redirect to section page after successful update/add
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch the section data for editing if needed
$sectionToEdit = null;
if (isset($_GET['section_id'])) {
    $id = $_GET['section_id'];
    $stmt = $pdo->prepare("SELECT * FROM section WHERE section_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $sectionToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sections</title>
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
            <!-- Add Section Button -->
            <!-- Add Section Button -->
            <button id="addSectionBtn" class="add-btn">Add Section</button>

            <!-- Modal Structure for Adding/Editing -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 class="form-title"><?php echo isset($sectionToEdit) ? 'Edit' : 'Add'; ?> Section</h2>

                    <form action="section.php" method="POST">
                        <!-- Hidden ID field for editing -->
                        <?php if ($sectionToEdit): ?>
                            <input type="hidden" name="id" value="<?php echo $sectionToEdit['section_id']; ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="section" class="form-label">Section Name</label>
                            <input type="text" name="section" id="section" class="form-input" 
                                   value="<?php echo $sectionToEdit ? htmlspecialchars($sectionToEdit['sections']) : ''; ?>" 
                                   placeholder="Enter section name" required>
                        </div>

                        <button type="submit" class="submit-btn"><?php echo isset($sectionToEdit) ? 'Update' : 'Add'; ?> Section</button>
                    </form>
                </div>
            </div>

            <!-- Toggle Active/Archived Sections Button -->
            <a href="section.php<?php echo isset($_GET['archived']) && $_GET['archived'] == 'true' ? '' : '?archived=true'; ?>" class="btn view-archived-btn">
                <?php echo isset($_GET['archived']) && $_GET['archived'] == 'true' ? 'View Active Sections' : 'View Archived Sections'; ?>
            </a>

            <!-- Section List -->
            <h2>Section List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Section Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sections)): ?>
                        <?php foreach ($sections as $section): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($section['sections']); ?></td>
                                <td>
                                    <a href="?section_id=<?php echo $section['section_id']; ?>" class="btn edit-btn">Edit</a>
                                    <a href="archive_section.php?id=<?php echo $section['section_id']; ?>" class="btn archive-btn">Archive</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No active sections found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Archived Sections Section -->
            <?php if (isset($_GET['archived']) && $_GET['archived'] == 'true'): ?>
                <h2>Archived Sections</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Section Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($archivedSections)): ?>
                            <?php foreach ($archivedSections as $section): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($section['sections']); ?></td>
                                    <td>
                                        <a href="restore_section.php?id=<?php echo $section['section_id']; ?>" class="btn restore-btn">Restore</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2">No archived sections found.</td>
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
        // Get the modal
        var modal = document.getElementById("myModal");
        // Get the button that opens the modal
        var btn = document.getElementById("addSectionBtn");
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>
