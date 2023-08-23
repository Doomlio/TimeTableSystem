-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2023 at 08:29 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

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
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `timetable_id` int(11) NOT NULL,
  `subject_name` varchar(20) DEFAULT NULL,
  `lec_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day` varchar(50) DEFAULT NULL,
  `classtype` varchar(100) DEFAULT NULL,
  `subID` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`timetable_id`, `subject_name`, `lec_id`, `start_time`, `end_time`, `day`, `classtype`, `subID`) VALUES
(1, 'Intro to ABC', 1, '10:00:00', '12:00:00', 'wednesday', 'lecture', 'EC1'),
(14, 'Intro to DEF', 2, '08:00:00', '10:00:00', 'monday', 'lab', 'EC2'),
(15, 'Intro to GHI', 3, '08:00:00', '10:00:00', 'monday', 'lecture', 'EC3'),
(16, 'Intro to ABC', 1, '13:00:00', '15:00:00', 'friday', 'lab', 'EC1'),
(17, 'Intro to DEF', 2, '15:00:00', '17:00:00', 'wednesday', 'lecture', 'EC2'),
(18, 'Intro to ABC', 1, '14:00:00', '16:00:00', 'thursday', 'lab', 'EC1'),
(19, 'Intro to ABC', 1, '08:00:00', '10:00:00', 'wednesday', 'lecture ', 'EC1'),
(32, 'Intro to GHI', 3, '11:00:00', '13:00:00', 'thursday', 'lab', 'EC3'),
(33, 'Intro to GHI', 3, '14:00:00', '16:00:00', 'tuesday', 'lab', 'EC3'),
(34, 'Intro to ABC', 1, '00:00:15', '00:00:17', 'monday', NULL, 'EC1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`timetable_id`),
  ADD KEY `indexlecturerfk` (`lec_id`),
  ADD KEY `ind_subID` (`subID`),
  ADD KEY `subID` (`subID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `timetable_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

ALTER TABLE `timetable`
MODIFY COLUMN `subID` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
ADD CONSTRAINT `fk_timetable_subject`
FOREIGN KEY (`subID`) REFERENCES `subject` (`subID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
