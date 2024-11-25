<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection once
include('db.php');

// Query to count the number of students
$students_query = "SELECT COUNT(*) AS total_students FROM users WHERE role = 'student'";
$students_stmt = $pdo->prepare($students_query);
$students_stmt->execute();
$total_students_row = $students_stmt->fetch(PDO::FETCH_ASSOC);
$total_students = $total_students_row['total_students'];

// Query to count the number of instructors
$instructors_query = "SELECT COUNT(*) AS total_instructors FROM users WHERE role = 'instructor'";
$instructors_stmt = $pdo->prepare($instructors_query);
$instructors_stmt->execute();
$total_instructors_row = $instructors_stmt->fetch(PDO::FETCH_ASSOC);
$total_instructors = $total_instructors_row['total_instructors'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
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
                    <a href="dashboard.php">
                        <span class="icon"><ion-icon name="home"></ion-icon></span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li id="instructor">
                    <a href="index.php">
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
                    <a href="/Admin//Semester/semester.php">
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
		
	
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_students; ?></div>
                        <div class="cardName">Total Students</div>
                    </div>
                    
                </div>

                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_instructors; ?></div>
                        <div class="cardName">Total Instructors</div>
                    </div>
        
    </div>


    <script src="/Admin/main.js"></script>

    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
</body>
</html>