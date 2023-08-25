-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2023 at 04:20 AM
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
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lec_id` int(11) NOT NULL,
  `lecname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `maxhours` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lec_id`, `lecname`, `email`, `password`, `maxhours`) VALUES
(1, 'Wan Nor Abcl-Ashekin', 'ashekin@gmail.com', '123456', 16),
(2, 'Noor Zuhaili Md.Yasin', 'zuhaili@gmail.com', '123456', 16),
(3, 'Thagirarani Muniandy', 'rani@gmail.com', '123456', 16);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subID`, `subname`, `qualification`, `sem`, `lecid`, `course`) VALUES
('EC1', 'Database Management Fundamentals', 'diploma', 'Oct', 3, 'DIT'),
('EC2', 'Intro to ABC', 'Diploma', 'Oct', 1, 'DIT'),
('EC3', 'Intro to Mweff', 'Diploma', 'Oct', 1, 'DCS'),
('EC4', 'Intro to MNOqewrqwer', 'Diploma', 'Oct', 3, 'DCS'),
('EC5', 'Web Development Basics', 'diploma', 'Oct', 3, 'DCS'),
('EC6', 'Web Development Basics', 'diploma', 'Oct', 1, 'DCS'),
('EC7', 'Intro to MNO', 'Diploma', 'Oct', 1, 'DCS'),
('EC8', 'Intro to e', 'Diploma', 'Oct', 2, 'DIT'),
('EC9', 'Intro to MNOewf', 'Diploma', 'Oct', 1, 'DIT');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`timetable_id`, `lec_id`, `start_time`, `end_time`, `day`, `classtype`, `subID`, `venueID`, `cstatus`, `hours`) VALUES
(1, 1, '11:00:00', '13:00:00', 'wednesday', 'lecture', 'EC4', 'S101', 'cancelled', 2),
(14, 2, '15:00:00', '17:00:00', 'monday', 'lab', 'EC2', 'S102', 'active', 2),
(15, 3, '11:00:00', '13:00:00', 'monday', 'lecture', 'EC3', 'S103', 'active', 2),
(16, 1, '15:00:00', '17:00:00', 'friday', 'lab', 'EC9', 'S104', 'active', 2),
(17, 2, '12:00:00', '14:00:00', 'wednesday', 'lecture', 'EC2', 'S105', 'active', 2),
(18, 1, '13:00:00', '15:00:00', 'thursday', 'lab', 'EC7', 'S106', 'active', 2),
(19, 1, '15:00:00', '17:00:00', 'wednesday', 'lecture ', 'EC9', 'S102', 'active', 2),
(32, 3, '14:00:00', '16:00:00', 'thursday', 'lab', 'EC3', 'S102', 'active', 2),
(33, 3, '13:00:00', '15:00:00', 'tuesday', 'lab', 'EC3', 'S104', 'active', 2),
(34, 1, '08:00:00', '10:00:00', 'monday', 'lecture', 'EC7', 'S103', 'active', 2);

-- --------------------------------------------------------

--
-- Table structure for table `timetablevenue`
--

CREATE TABLE `timetablevenue` (
  `subID` varchar(11) NOT NULL,
  `venueID` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venueid` varchar(11) NOT NULL,
  `venuetype` varchar(100) DEFAULT NULL,
  `subID` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venueid`, `venuetype`, `subID`) VALUES
('S101', 'lecture', 'EC7'),
('S102', 'lab', 'EC2'),
('S103', 'lecture', NULL),
('S104', 'lab', NULL),
('S105', 'lecture', NULL),
('S106', 'lab', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lec_id`),
  ADD UNIQUE KEY `lec_id` (`lec_id`),
  ADD KEY `indexlecturer` (`lec_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subID`),
  ADD UNIQUE KEY `subID_2` (`subID`),
  ADD KEY `lecid` (`lecid`),
  ADD KEY `subID` (`subID`);

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
-- Indexes for table `timetablevenue`
--
ALTER TABLE `timetablevenue`
  ADD PRIMARY KEY (`subID`,`venueID`),
  ADD KEY `venueID` (`venueID`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD UNIQUE KEY `venueid` (`venueid`),
  ADD KEY `subID` (`subID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `lec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `timetable_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`lecid`) REFERENCES `lecturer` (`lec_id`);

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `timetable_ibfk_1` FOREIGN KEY (`subID`) REFERENCES `subject` (`subID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `timetable_ibfk_2` FOREIGN KEY (`venueID`) REFERENCES `venue` (`venueid`);

--
-- Constraints for table `timetablevenue`
--
ALTER TABLE `timetablevenue`
  ADD CONSTRAINT `timetablevenue_ibfk_1` FOREIGN KEY (`subID`) REFERENCES `subject` (`subID`),
  ADD CONSTRAINT `timetablevenue_ibfk_2` FOREIGN KEY (`venueID`) REFERENCES `venue` (`venueid`);

--
-- Constraints for table `venue`
--
ALTER TABLE `venue`
  ADD CONSTRAINT `venue_ibfk_1` FOREIGN KEY (`subID`) REFERENCES `subject` (`subID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
