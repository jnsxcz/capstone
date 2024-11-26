-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 03:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cap`
--

-- --------------------------------------------------------

--
-- Table structure for table `acad_year`
--

CREATE TABLE `acad_year` (
  `ay_id` int(11) NOT NULL,
  `year_start` int(11) NOT NULL,
  `isActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acad_year`
--

INSERT INTO `acad_year` (`ay_id`, `year_start`, `isActive`) VALUES
(1, 2024, 1);

-- --------------------------------------------------------

--
-- Table structure for table `advisory_class`
--

CREATE TABLE `advisory_class` (
  `advisory_class_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `ay_id` int(11) NOT NULL,
  `sem_id` int(11) NOT NULL,
  `isActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `year_level` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `year_level`, `section_id`) VALUES
(0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `class_teacher`
--

CREATE TABLE `class_teacher` (
  `class_teacher_id` int(11) NOT NULL,
  `advisory_class_id` int(11) NOT NULL,
  `teacher_type` varchar(50) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dep_id` int(11) NOT NULL,
  `department` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','archived') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dep_id`, `department`, `description`, `status`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology', 'active'),
(2, 'BSBA', 'Bachelor of Science in Business Administration', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE `evaluation` (
  `eval_id` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `rate_result` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_teacher_id` int(11) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `ques_id` int(11) NOT NULL,
  `questions` text NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE `rate` (
  `rate_id` int(11) NOT NULL,
  `rate_name` varchar(100) NOT NULL,
  `rates` int(11) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `ratings_id` int(11) NOT NULL,
  `eval_id` int(11) NOT NULL,
  `ques_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `sections` varchar(11) NOT NULL,
  `status` enum('Active','Archived') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `sections`, `status`) VALUES
(1, 'A', 'Active'),
(2, 'B', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `sem_id` int(11) NOT NULL,
  `semesters` varchar(70) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`sem_id`, `semesters`, `date_created`) VALUES
(1, '2nd Semester', '0000-00-00 00:00:00'),
(2, '1st Semester', '2024-11-24 05:14:47');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `sub_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subjects` varchar(100) NOT NULL,
  `unit` int(11) NOT NULL,
  `lab` int(11) DEFAULT 0,
  `credit` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) NOT NULL,
  `suffixname` varchar(20) DEFAULT NULL,
  `contact_no` varchar(20) NOT NULL,
  `houseno` int(20) NOT NULL,
  `street` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postalcode` varchar(25) NOT NULL,
  `birthdate` date NOT NULL DEFAULT curdate(),
  `gender` enum('Female','Male') NOT NULL,
  `role` enum('Admin','Student','Instructor') NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `mname`, `lname`, `suffixname`, `contact_no`, `houseno`, `street`, `barangay`, `city`, `province`, `postalcode`, `birthdate`, `gender`, `role`, `email`, `password`, `is_archived`) VALUES
(1, '', NULL, '', NULL, '0', 0, '', '', '', '', '', '2024-11-16', 'Female', 'Admin', 'admin', '$2y$10$NIAuJkJQqB31vAyk.lxgvuGDinzOVRgiBvpHcKmw8.KZq6abuYMWe', 1),
(7, '', NULL, '', '', '', 13, 'Zone 3', 'Licaong', 'Science City of Munoz', 'Nueva Ecija', '3119', '2003-03-21', '', '', '', NULL, 1),
(8, '', NULL, '', '', '', 13, 'Zone 3', 'Licaong', 'Science City of Munoz', 'Nueva Ecija', '3119', '2003-03-21', '', '', '', NULL, 1),
(9, '', NULL, '', '', '', 13, 'Zone 3', 'Licaong', 'Science City of Munoz', 'Nueva Ecija', '3119', '2003-03-21', '', '', '', NULL, 1),
(10, 'Jenalyn', '', 'Sabado', '', '9555726637', 13, 'Zone 3', 'Licaong', 'Science City of Munoz', 'Nueva Ecija', '3119', '2003-03-21', 'Female', 'Instructor', 'jenalynsabado29@gmail.com', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_class`
--

CREATE TABLE `user_class` (
  `user_class_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `advisory_class_id` int(11) NOT NULL,
  `isActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_dep`
--

CREATE TABLE `user_dep` (
  `user_dep_id` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `isActive` tinyint(1) DEFAULT 1,
  `date_assigned` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acad_year`
--
ALTER TABLE `acad_year`
  ADD PRIMARY KEY (`ay_id`);

--
-- Indexes for table `advisory_class`
--
ALTER TABLE `advisory_class`
  ADD PRIMARY KEY (`advisory_class_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `ay_id` (`ay_id`),
  ADD KEY `sem_id` (`sem_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `class_teacher`
--
ALTER TABLE `class_teacher`
  ADD PRIMARY KEY (`class_teacher_id`),
  ADD KEY `advisory_class_id` (`advisory_class_id`),
  ADD KEY `sub_id` (`sub_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dep_id`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`eval_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_teacher_id` (`class_teacher_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`ques_id`);

--
-- Indexes for table `rate`
--
ALTER TABLE `rate`
  ADD PRIMARY KEY (`rate_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`ratings_id`),
  ADD KEY `eval_id` (`eval_id`),
  ADD KEY `ques_id` (`ques_id`),
  ADD KEY `rate_id` (`rate_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`sem_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`sub_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_class`
--
ALTER TABLE `user_class`
  ADD PRIMARY KEY (`user_class_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `advisory_class_id` (`advisory_class_id`);

--
-- Indexes for table `user_dep`
--
ALTER TABLE `user_dep`
  ADD PRIMARY KEY (`user_dep_id`),
  ADD KEY `dep_id` (`dep_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acad_year`
--
ALTER TABLE `acad_year`
  MODIFY `ay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `advisory_class`
--
ALTER TABLE `advisory_class`
  MODIFY `advisory_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_teacher`
--
ALTER TABLE `class_teacher`
  MODIFY `class_teacher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dep_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `eval_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `ques_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rate`
--
ALTER TABLE `rate`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `ratings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `sem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_class`
--
ALTER TABLE `user_class`
  MODIFY `user_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_dep`
--
ALTER TABLE `user_dep`
  MODIFY `user_dep_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advisory_class`
--
ALTER TABLE `advisory_class`
  ADD CONSTRAINT `advisory_class_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `advisory_class_ibfk_2` FOREIGN KEY (`ay_id`) REFERENCES `acad_year` (`ay_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `advisory_class_ibfk_3` FOREIGN KEY (`sem_id`) REFERENCES `semester` (`sem_id`) ON DELETE CASCADE;

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`);

--
-- Constraints for table `class_teacher`
--
ALTER TABLE `class_teacher`
  ADD CONSTRAINT `class_teacher_ibfk_1` FOREIGN KEY (`advisory_class_id`) REFERENCES `advisory_class` (`advisory_class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_teacher_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject` (`sub_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_teacher_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `evaluation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluation_ibfk_2` FOREIGN KEY (`class_teacher_id`) REFERENCES `class_teacher` (`class_teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`eval_id`) REFERENCES `evaluation` (`eval_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`ques_id`) REFERENCES `question` (`ques_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`rate_id`) REFERENCES `rate` (`rate_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_class`
--
ALTER TABLE `user_class`
  ADD CONSTRAINT `user_class_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_class_ibfk_2` FOREIGN KEY (`advisory_class_id`) REFERENCES `advisory_class` (`advisory_class_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_dep`
--
ALTER TABLE `user_dep`
  ADD CONSTRAINT `user_dep_ibfk_1` FOREIGN KEY (`dep_id`) REFERENCES `department` (`dep_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_dep_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
