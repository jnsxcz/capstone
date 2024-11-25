<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle CSV file upload and database insertion (same as previous code)
if (isset($_POST['submit']) && isset($_FILES['csvFile'])) {
    // Get the uploaded file details
    $file = $_FILES['csvFile'];

    // Check if the file was uploaded without errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Get file info
        $fileTmpName = $file['tmp_name'];
        $fileName = $file['name'];

        // Check if the file is a CSV file (optional)
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (strtolower($fileExtension) !== 'csv') {
            echo "Sorry, only CSV files are allowed.";
            exit;
        }

        // Open the CSV file
        if (($handle = fopen($fileTmpName, 'r')) !== FALSE) {
            // Skip the first row if it contains headers (optional)
            fgetcsv($handle);

            // Prepare the SQL insert query
            $stmt = $conn->prepare("
                INSERT INTO users (
                    firstname, middlename, lastname, suffixname, contact, house_no, street, barangay, city, 
                    province, postalcode, birthday, gender, email, accesstype
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            // Loop through each row in the CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Bind the CSV data to the SQL query
                $stmt->bind_param(
                    "sssssssssssssss", 
                    $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], 
                    $data[6], $data[7], $data[8], $data[9], $data[10], 
                    $data[11], $data[12], $data[13], $data[14]
                );

                // Execute the statement
                $stmt->execute();
            }

            // Close the file handle and prepared statement
            fclose($handle);
            $stmt->close();
        }
    } else {
        echo "Error uploading the file.";
    }
}

// Handle Edit request
if (isset($_POST['edit'])) {
    $user_id = $_POST['user_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    // Update the user in the database
    $stmt = $conn->prepare("UPDATE users SET firstname=?, middlename=?, lastname=?, contact=?, gender=?, email=? WHERE user_id=?");
    $stmt->bind_param("ssssssi", $firstname, $middlename, $lastname, $contact, $gender, $email, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: your_page.php"); // Redirect to avoid resubmitting the form
    exit();
}

// Handle Archive request
if (isset($_GET['archive'])) {
    $user_id = $_GET['user_id'];

    // Mark the user as archived (assuming an "is_archived" column)
    $stmt = $conn->prepare("UPDATE users SET is_archived=1 WHERE user_id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: your_page.php"); // Redirect to avoid resubmitting the form
    exit();
}

// Retrieve all users (active and not archived)
$sql = "SELECT * FROM users WHERE is_archived = 0"; // Change condition as per your archive logic
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

    <h2>Upload CSV File to Users Table</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="csvFile">Select CSV File:</label>
        <input type="file" name="csvFile" id="csvFile" required>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
    </form>

    <h2>User List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Contact</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']; ?></td>
                        <td><?php echo $row['contact']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <button class="btn btn-warning" data-toggle="modal" data-target="#editModal" 
                                    data-user-id="<?php echo $row['user_id']; ?>"
                                    data-firstname="<?php echo $row['firstname']; ?>"
                                    data-middlename="<?php echo $row['middlename']; ?>"
                                    data-lastname="<?php echo $row['lastname']; ?>"
                                    data-contact="<?php echo $row['contact']; ?>"
                                    data-gender="<?php echo $row['gender']; ?>"
                                    data-email="<?php echo $row['email']; ?>"
                            >Edit</button>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#archiveModal" 
                                    data-user-id="<?php echo $row['user_id']; ?>"
                            >Archive</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No active users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="hidden" name="user_id" id="editUserId">
                    <div class="form-group">
                        <label for="editFirstname">First Name:</label>
                        <input type="text" class="form-control" id="editFirstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="editMiddlename">Middle Name:</label>
                        <input type="text" class="form-control" id="editMiddlename" name="middlename">
                    </div>
                    <div class="form-group">
                        <label for="editLastname">Last Name:</label>
                        <input type="text" class="form-control" id="editLastname" name="lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="editContact">Contact:</label>
                        <input type="text" class="form-control" id="editContact" name="contact" required>
                    </div>
                    <div class="form-group">
                        <label for="editGender">Gender:</label>
                        <input type="text" class="form-control" id="editGender" name="gender" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email:</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Archive Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive this user?</p>
            </div>
            <div class="modal-footer">
                <a href="#" id="archiveBtn" class="btn btn-danger">Yes, Archive</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Populate the Edit Modal with user data
    $('#editModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var firstname = button.data('firstname');
        var middlename = button.data('middlename');
        var lastname = button.data('lastname');
        var contact = button.data('contact');
        var gender = button.data('gender');
        var email = button.data('email');

        $('#editUserId').val(userId);
        $('#editFirstname').val(firstname);
        $('#editMiddlename').val(middlename);
        $('#editLastname').val(lastname);
        $('#editContact').val(contact);
        $('#editGender').val(gender);
        $('#editEmail').val(email);
    });

    // Set the Archive Button link
    $('#archiveModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        $('#archiveBtn').attr('href', 'your_page.php?archive=1&user_id=' + userId);
    });
</script>

</body>
</html>
