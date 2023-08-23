-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2023 at 10:30 AM
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
(1, 'Wan Nor Al-Ashekin', 'ashekin@gmail.com', '123456', 16),
(2, 'Noor Zuhaili Md.Yasin', 'zuhaili@gmail.com', '123456', 16),
(3, 'Thagirarani Muniandy', 'rani@gmail.com', '123456', 16);

-- --------------------------------------------------------

--
-- Table structure for table `lecturerpreference`
--

CREATE TABLE `lecturerpreference` (
  `prefid` int(11) NOT NULL,
  `timestartGOOD` time(6) NOT NULL,
  `timeendGOOD` time(6) NOT NULL,
  `lec_id` int(100) NOT NULL,
  `timestartBAD` time(6) NOT NULL,
  `timeendBAD` time(6) NOT NULL,
  `monstartgood` time DEFAULT NULL,
  `monendgood` time DEFAULT NULL,
  `tuestartgood` time DEFAULT NULL,
  `tueendgood` time DEFAULT NULL,
  `wedstartgood` time DEFAULT NULL,
  `wedendgood` time DEFAULT NULL,
  `thustartgood` time DEFAULT NULL,
  `thuendgood` time DEFAULT NULL,
  `fristartgood` time DEFAULT NULL,
  `friendgood` time DEFAULT NULL,
  `monstartbad` time DEFAULT NULL,
  `monendbad` time DEFAULT NULL,
  `tuestartbad` time DEFAULT NULL,
  `tueendbad` time DEFAULT NULL,
  `wedstartbad` time DEFAULT NULL,
  `wedendbad` time DEFAULT NULL,
  `thustartbad` time DEFAULT NULL,
  `thuendbad` time DEFAULT NULL,
  `fristartbad` time DEFAULT NULL,
  `friendbad` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecturerpreference`
--

INSERT INTO `lecturerpreference` (`prefid`, `timestartGOOD`, `timeendGOOD`, `lec_id`, `timestartBAD`, `timeendBAD`, `monstartgood`, `monendgood`, `tuestartgood`, `tueendgood`, `wedstartgood`, `wedendgood`, `thustartgood`, `thuendgood`, `fristartgood`, `friendgood`, `monstartbad`, `monendbad`, `tuestartbad`, `tueendbad`, `wedstartbad`, `wedendbad`, `thustartbad`, `thuendbad`, `fristartbad`, `friendbad`) VALUES
(1, '08:00:00.000000', '10:00:00.000000', 1, '12:00:00.000000', '04:00:00.000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '14:00:00.000000', '16:00:00.000000', 2, '15:00:00.000000', '17:00:00.000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `reqid` int(11) NOT NULL,
  `reqtext` varchar(1000) DEFAULT NULL,
  `lec_id` int(11) DEFAULT NULL,
  `subID` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`reqid`, `reqtext`, `lec_id`, `subID`) VALUES
(1, '', NULL, 'EC1'),
(2, '\r\n    qwertyuiop', NULL, 'EC1');

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
('EC1', 'Intro to ABC', 'Diploma', 'Oct', 1, 'DCS'),
('EC2', 'Intro to DEF', 'Diploma', 'Oct', 2, 'DIT'),
('EC3', 'Intro to GHI', 'Diploma', 'Oct', 3, 'DCS'),
('EC3101', 'Intro to IT', 'diploma', 'Jan', 1, 'DCS'),
('EC4', 'Intro to JKL', 'Diploma', 'Oct', 1, 'DIT'),
('EC5', 'Intro to MNO', 'Diploma', 'Oct', 2, 'DCS');

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
  `subID` varchar(11) DEFAULT NULL,
  `venueID` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`timetable_id`, `subject_name`, `lec_id`, `start_time`, `end_time`, `day`, `classtype`, `subID`, `venueID`) VALUES
(1, 'Intro to ABC', 1, '10:00:00', '12:00:00', 'wednesday', 'lecture', 'EC1', 'S101'),
(14, 'Intro to DEF', 2, '08:00:00', '10:00:00', 'monday', 'lab', 'EC2', 'S102'),
(15, 'Intro to GHI', 3, '08:00:00', '10:00:00', 'monday', 'lecture', 'EC3', 'S101'),
(16, 'Intro to ABC', 1, '13:00:00', '15:00:00', 'friday', 'lab', 'EC1', 'S102'),
(17, 'Intro to DEF', 2, '15:00:00', '17:00:00', 'wednesday', 'lecture', 'EC2', 'S101'),
(18, 'Intro to ABC', 1, '14:00:00', '16:00:00', 'thursday', 'lab', 'EC1', 'S102'),
(19, 'Intro to ABC', 1, '08:00:00', '10:00:00', 'wednesday', 'lecture ', 'EC1', 'S101'),
(32, 'Intro to GHI', 3, '11:00:00', '13:00:00', 'thursday', 'lab', 'EC3', 'S102'),
(33, 'Intro to GHI', 3, '14:00:00', '16:00:00', 'tuesday', 'lab', 'EC3', 'S102'),
(34, 'Intro to ABC', 1, '00:00:15', '00:00:17', 'monday', 'lecture', 'EC1', 'S101');

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
('S101', 'lecture', 'EC1'),
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
-- Indexes for table `lecturerpreference`
--
ALTER TABLE `lecturerpreference`
  ADD PRIMARY KEY (`prefid`),
  ADD KEY `lec_id` (`lec_id`),
  ADD KEY `prefID` (`prefid`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`reqid`),
  ADD KEY `lec_id` (`lec_id`),
  ADD KEY `subID` (`subID`);

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
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD UNIQUE KEY `venueid` (`venueid`),
  ADD KEY `fk_venue_subject` (`subID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `lec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lecturerpreference`
--
ALTER TABLE `lecturerpreference`
  MODIFY `prefid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `reqid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `timetable_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`lec_id`) REFERENCES `lecturer` (`lec_id`),
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`subID`) REFERENCES `subject` (`subID`);

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
-- Constraints for table `venue`
--
ALTER TABLE `venue`
  ADD CONSTRAINT `fk_venue_subject` FOREIGN KEY (`subID`) REFERENCES `subject` (`subID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
