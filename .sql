-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2023 at 12:37 PM
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
  `classtype` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`timetable_id`, `subject_name`, `lec_id`, `start_time`, `end_time`, `day`, `classtype`) VALUES
(1, 'EC1001', 1, '10:00:00', '12:00:00', 'tuesday', 'lecture'),
(14, 'ec1002', 2, '08:00:00', '10:00:00', 'monday', 'lab'),
(15, 'ec1003', 3, '08:00:00', '10:00:00', 'monday', 'lecture'),
(16, 'ec1004', 1, '13:00:00', '15:00:00', 'friday', 'lab'),
(17, 'ec1005', 2, '15:00:00', '17:00:00', 'wednesday', 'lecture'),
(18, 'EC1006', 1, '14:00:00', '16:00:00', 'thursday', 'lab'),
(19, 'ec1007', 1, '08:00:00', '10:00:00', 'wednesday', 'lecture '),
(32, 'ec1008', 3, '11:00:00', '13:00:00', 'Thursday', 'lab'),
(33, 'EC1001', 0, '14:00:00', '16:00:00', 'tuesday', 'lab');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`timetable_id`),
  ADD KEY `indexlecturerfk` (`lec_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `timetable_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
