-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2024 at 01:28 PM
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
-- Database: `finalerd`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessibilityfeatures`
--

CREATE TABLE `accessibilityfeatures` (
  `LicensePlate` varchar(8) NOT NULL,
  `WheelChairLift` enum('Yes','No') NOT NULL,
  `Ramp` enum('Yes','No') NOT NULL,
  `StudentID` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accessibilityfeatures`
--

INSERT INTO `accessibilityfeatures` (`LicensePlate`, `WheelChairLift`, `Ramp`, `StudentID`) VALUES
('LLL-1234', 'Yes', 'Yes', 123456789);

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) NOT NULL,
  `university_name` varchar(100) NOT NULL,
  `driver_license` varchar(20) DEFAULT NULL,
  `license_plate` varchar(20) DEFAULT NULL,
  `years_of_service` int(10) NOT NULL,
  `wheelchair` enum('yes','no') NOT NULL DEFAULT 'no',
  `ramp` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `first_name`, `middle_name`, `last_name`, `university_name`, `driver_license`, `license_plate`, `years_of_service`, `wheelchair`, `ramp`) VALUES
('Driver 1', '123', 'Driver', 'test', 'one', 'Cebu Institute University', 'abc123', 'AAA-1111', 2, 'yes', 'yes'),
('driver test', '123', 'test', 'driver', 'test', 'test', 'tests12', 'test1test', 3, 'no', 'no'),
('test', '123', '', '', '', '', '', '', 0, 'no', 'no'),
('test1', '123', 'testsdsd', 'test', 'testw', 'testw', 'tests12', 'LLL-1111', 21, 'yes', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `student_account`
--

CREATE TABLE `student_account` (
  `student_id` int(9) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `number` int(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_account`
--

INSERT INTO `student_account` (`student_id`, `first_name`, `last_name`, `number`, `email`, `username`, `password`) VALUES
(123456789, 'test', 'test', 0, 'test@test.com', 'test1', 123),
(182661808, 'Magnus', 'Charles', 2147483647, 'student@test.com', 'studenttest', 123);

-- --------------------------------------------------------

--
-- Table structure for table `university`
--

CREATE TABLE `university` (
  `UniversityID` varchar(20) NOT NULL,
  `UniversityName` varchar(100) NOT NULL,
  `LicensePlate` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userreserve`
--

CREATE TABLE `userreserve` (
  `license_plate` varchar(8) NOT NULL,
  `wheelchair` enum('Yes','No') NOT NULL,
  `ramp` enum('Yes','No') NOT NULL,
  `student_id` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `student_account`
--
ALTER TABLE `student_account`
  ADD KEY `idx_student_id` (`student_id`);

--
-- Indexes for table `userreserve`
--
ALTER TABLE `userreserve`
  ADD PRIMARY KEY (`license_plate`),
  ADD KEY `fk_student_id` (`student_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `userreserve`
--
ALTER TABLE `userreserve`
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `student_account` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

