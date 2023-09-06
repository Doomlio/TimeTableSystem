-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2023 at 07:56 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fyptimetable`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `adminname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `adminname`, `email`, `password`) VALUES
(1, 'ADMIN', 'admin@admin.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lec_id` int(11) NOT NULL,
  `lecname` varchar(100) NOT NULL,
  `lecemail` varchar(100) NOT NULL,
  `lecpassword` varchar(100) NOT NULL,
  `maxhours` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lec_id`, `lecname`, `lecemail`, `lecpassword`, `maxhours`) VALUES
(1, 'Wan Nor Abcl-Ashekin', 'ashekin@gmail.com', '123456', 16),
(2, 'Noor Zuhaili Md.Yasin', 'zuhaili@gmail.com', '123456', 16),
(3, 'Thagirarani Muniandy', 'rani@gmail.com', '123456', 16);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `Reqid` int(11) NOT NULL,
  `lecid` int(10) NOT NULL,
  `timetable_id` int(11) NOT NULL,
  `new_start_time` time NOT NULL,
  `new_end_time` time NOT NULL,
  `new_day` varchar(20) NOT NULL,
  `new_class_type` varchar(20) NOT NULL,
  `new_venue_id` varchar(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`Reqid`, `lecid`, `timetable_id`, `new_start_time`, `new_end_time`, `new_day`, `new_class_type`, `new_venue_id`, `status`, `created_at`) VALUES
(34, 2, 19, '14:00:00', '16:00:00', 'monday', 'lab', 'S106', 'approved', '2023-08-27 09:51:29'),
(35, 2, 32, '12:00:00', '14:00:00', 'tuesday', 'lecture', 'S105', 'deny', '2023-08-27 09:54:35'),
(36, 2, 19, '14:00:00', '16:00:00', 'monday', 'lab', 'S106', 'approved', '2023-08-28 16:25:11'),
(37, 2, 33, '08:00:00', '10:00:00', 'friday', 'lecture', 'S105', 'approved', '2023-08-28 21:32:55'),
(38, 2, 48, '14:00:00', '16:00:00', 'monday', 'lecture', 'S103', 'approved', '2023-08-29 04:00:09'),
(39, 2, 32, '10:00:00', '12:00:00', 'tuesday', 'lecture', 'S103', 'deny', '2023-08-29 04:00:28'),
(40, 2, 33, '13:00:00', '15:00:00', 'friday', 'Lab', 'S101', 'approved', '2023-08-31 01:33:48'),
(41, 2, 48, '14:00:00', '16:00:00', 'monday', 'Lab', 'S105', 'pending', '2023-09-04 04:48:15'),
(42, 2, 32, '15:00:00', '17:00:00', 'wednesday', 'Lab', 'S101', 'pending', '2023-09-04 04:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subID` varchar(11) NOT NULL,
  `subname` varchar(100) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `sem` varchar(100) DEFAULT NULL,
  `lecid` int(11) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subID`, `subname`, `qualification`, `sem`, `lecid`, `course`) VALUES
('EC1', 'Database Management Fundamentals', 'Diploma', 'Oct', 1, 'DIT'),
('EC2', 'Intro to ABC', 'Diploma', 'Oct', 2, 'DIT'),
('EC3', 'Intro to DEF', 'Diploma', 'Oct', 2, 'DCS'),
('EC4', 'Intro to GHI', 'Diploma', 'Oct', 2, 'DCS'),
('EC5', 'ABCDEFG', 'Diploma', 'oct', 3, 'DCS'),
('EC6', 'Web Development Basics', 'Diploma', 'Oct', 1, 'DCS'),
('EC7', 'Intro to MNO', 'Diploma', 'Oct', 3, 'DCS'),
('EC8', 'Intro to e', 'Diploma', 'Oct', 3, 'DIT'),
('EC9', 'Intro to database', 'diploma', 'Oct', 1, 'May');

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `timetable_id` int(11) NOT NULL,
  `lec_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day` varchar(50) DEFAULT NULL,
  `classtype` varchar(100) DEFAULT NULL,
  `subID` varchar(11) DEFAULT NULL,
  `venueID` varchar(11) DEFAULT NULL,
  `cstatus` varchar(255) DEFAULT 'active',
  `hours` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`timetable_id`, `lec_id`, `start_time`, `end_time`, `day`, `classtype`, `subID`, `venueID`, `cstatus`, `hours`) VALUES
(1, 2, '10:00:00', '12:00:00', 'wednesday', 'lecture', 'EC4', 'S101', 'active', 2),
(2, 3, '11:00:00', '13:00:00', 'monday', 'lecture', 'EC7', 'S101', 'active', 2),
(14, 2, '15:00:00', '17:00:00', 'wednesday', 'lab', 'EC2', 'S102', 'active', 2),
(15, 2, '10:00:00', '12:00:00', 'thursday', 'lab', 'EC3', 'S102', 'active', 2),
(17, 2, '09:00:00', '11:00:00', 'thursday', 'lecture', 'EC2', 'S101', 'active', 2),
(32, 2, '13:00:00', '15:00:00', 'tuesday', 'lecture', 'EC3', 'S101', 'active', 2),
(33, 2, '12:00:00', '14:00:00', 'friday', 'lecture', 'EC3', 'S105', 'active', 2),
(48, 2, '14:00:00', '16:00:00', 'monday', 'lecture', 'EC3', 'S103', 'active', 2),
(310, 1, '09:00:00', '11:00:00', 'thursday', 'lab', 'EC1', 'S101011', 'active', 2),
(311, 1, '12:00:00', '14:00:00', 'friday', 'lecture', 'EC1', 'S101', 'active', 2),
(312, 1, '14:00:00', '16:00:00', 'friday', 'lecture', 'EC9', 'S101', 'replacement', 2),
(316, 1, '13:00:00', '15:00:00', 'monday', 'lecture', 'EC1', 'S101', 'active', 2),
(317, 1, '13:00:00', '15:00:00', 'thursday', 'lab', 'EC9', 'S104', 'active', 2),
(318, 1, '15:00:00', '17:00:00', 'tuesday', 'lecture', 'EC9', 'S106', 'active', 2);

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venueid` varchar(11) NOT NULL,
  `venuetype` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venueid`, `venuetype`) VALUES
('S101', 'lecture'),
('S101011', 'lab'),
('S102', 'lab'),
('S103', 'lecture'),
('S104', 'lab'),
('S105', 'lecture'),
('S106', 'lab'),
('S123432', 'lecture'),
('wqe', 'laab');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lec_id`),
  ADD UNIQUE KEY `email` (`lecemail`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`Reqid`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subID`),
  ADD KEY `fk_subject_lecturer` (`lecid`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`timetable_id`),
  ADD KEY `indexlecturerfk` (`lec_id`),
  ADD KEY `ind_subID` (`subID`),
  ADD KEY `subID` (`subID`),
  ADD KEY `venueID` (`venueID`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venueid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `lec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `Reqid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `timetable_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `fk_timetable_subject` FOREIGN KEY (`subID`) REFERENCES `subject` (`subID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_timetable_venue` FOREIGN KEY (`venueID`) REFERENCES `venue` (`venueid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
