-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 02:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_gpk`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `assessment_date` date DEFAULT NULL,
  `assessment_type` varchar(255) DEFAULT NULL,
  `result` text DEFAULT NULL,
  `evaluator` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`id`, `student_id`, `assessment_date`, `assessment_type`, `result`, `evaluator`) VALUES
(1, 1, '2001-03-13', 'tes', 'tes', 'tes');

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calendar`
--

INSERT INTO `calendar` (`id`, `user_id`, `event_title`, `event_date`, `event_description`, `created_at`, `updated_at`) VALUES
(2, 1, 'AAAAAAAAAAA', '2025-01-17', 'AAAAAAAAAAAAAAAAAA', '2025-01-07 00:53:37', '2025-01-07 00:53:37'),
(3, 1, 'BBBB', '2025-01-13', 'AAAAAAAAAAAAAAAAAAAAAAAAB', '2025-01-07 00:54:15', '2025-01-07 00:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `pbs`
--

CREATE TABLE `pbs` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `program_name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `resources` text DEFAULT NULL,
  `implementation` text DEFAULT NULL,
  `evaluation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pbs`
--

INSERT INTO `pbs` (`id`, `student_id`, `program_name`, `start_date`, `end_date`, `objectives`, `resources`, `implementation`, `evaluation`) VALUES
(3, 3, 'aa', '2026-01-07', '2001-03-13', 'a', 'a', 'a', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `ppi`
--

CREATE TABLE `ppi` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `current_ability` text DEFAULT NULL,
  `long_term_goals` text DEFAULT NULL,
  `short_term_goals` text DEFAULT NULL,
  `special_services` text DEFAULT NULL,
  `service_provision` text DEFAULT NULL,
  `implementation_time` text DEFAULT NULL,
  `evaluation_criteria` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ppi`
--

INSERT INTO `ppi` (`id`, `student_id`, `current_ability`, `long_term_goals`, `short_term_goals`, `special_services`, `service_provision`, `implementation_time`, `evaluation_criteria`) VALUES
(1, 2, 'a', 'a', 'a', 'a', 'a', 'a', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `birth_of_date` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `disability_type` varchar(100) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `photo`, `name`, `birth_of_date`, `gender`, `disability_type`, `class`) VALUES
(1, 1, 'a.png', 'anas', '2001-03-13', 'Male', 'anasaaa', 'anas'),
(2, 1, 'GetFoto.jpeg', 'anas lagi', '2001-03-13', 'Male', 'tes', 'tes'),
(3, 1, 'login.png', 'HEHE', '2000-02-13', 'Male', 'HEHE', 'EHEH');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `school`, `email`, `username`, `password`) VALUES
(1, 'anas', 'anas', 'anasyusuf12@gmail.com', 'anas', '$2y$10$/8f7ULs6jhRdAGfqZ0u52eX/P4GXSjp3z9HwGJEcOD.vveopfJQo2'),
(2, 'TES', 'TES', 'anasyusuf1711@gmail.com', 'tes', '$2y$10$f2C7u31p.2i9YSIYsfb6n.4iTM..aGTcVH5UyNeBw8.xg5f1sQ9Nm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pbs`
--
ALTER TABLE `pbs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `ppi`
--
ALTER TABLE `ppi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pbs`
--
ALTER TABLE `pbs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ppi`
--
ALTER TABLE `ppi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `calendar`
--
ALTER TABLE `calendar`
  ADD CONSTRAINT `calendar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pbs`
--
ALTER TABLE `pbs`
  ADD CONSTRAINT `pbs_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `ppi`
--
ALTER TABLE `ppi`
  ADD CONSTRAINT `ppi_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
