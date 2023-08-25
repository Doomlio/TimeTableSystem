-- Create the database
CREATE DATABASE IF NOT EXISTS fyptimetable;
USE fyptimetable;

-- Set SQL mode and time zone
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Create the lecturer table
CREATE TABLE IF NOT EXISTS `lecturer` (
  `lec_id` INT NOT NULL AUTO_INCREMENT,
  `lecname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `maxhours` INT DEFAULT NULL,
  PRIMARY KEY (`lec_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data into the lecturer table
INSERT INTO `lecturer` (`lecname`, `email`, `password`, `maxhours`) VALUES
  ('Wan Nor Abcl-Ashekin', 'ashekin@gmail.com', '123456', 16),
  ('Noor Zuhaili Md.Yasin', 'zuhaili@gmail.com', '123456', 16),
  ('Thagirarani Muniandy', 'rani@gmail.com', '123456', 16);

-- Create the subject table
CREATE TABLE IF NOT EXISTS `subject` (
  `subID` VARCHAR(11) NOT NULL,
  `subname` VARCHAR(100) DEFAULT NULL,
  `qualification` VARCHAR(100) DEFAULT NULL,
  `sem` VARCHAR(100) DEFAULT NULL,
  `lecid` INT DEFAULT NULL,
  `course` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`subID`),
  CONSTRAINT `fk_subject_lecturer` FOREIGN KEY (`lecid`) REFERENCES `lecturer` (`lec_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data into the subject table
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

-- Create the venue table
CREATE TABLE IF NOT EXISTS `venue` (
  `venueid` VARCHAR(11) NOT NULL,
  `venuetype` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`venueid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data into the venue table
INSERT INTO `venue` (`venueid`, `venuetype`) VALUES
  ('S101', 'lecture'),
  ('S102', 'lab'),
  ('S103', 'lecture'),
  ('S104', 'lab'),
  ('S105', 'lecture'),
  ('S106', 'lab');

-- Create the timetable table
CREATE TABLE IF NOT EXISTS `timetable` (
  `timetable_id` INT NOT NULL AUTO_INCREMENT,
  `lec_id` INT NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `day` VARCHAR(50) DEFAULT NULL,
  `classtype` VARCHAR(100) DEFAULT NULL,
  `subID` VARCHAR(11) DEFAULT NULL,
  `venueID` VARCHAR(11) DEFAULT NULL,
  `cstatus` VARCHAR(255) DEFAULT 'active',
  `hours` INT(10) DEFAULT NULL,
  PRIMARY KEY (`timetable_id`),
  KEY `indexlecturerfk` (`lec_id`),
  KEY `ind_subID` (`subID`),
  KEY `subID` (`subID`),
  KEY `venueID` (`venueID`),
  CONSTRAINT `fk_timetable_subject` FOREIGN KEY (`subID`) REFERENCES `subject` (`subID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_timetable_venue` FOREIGN KEY (`venueID`) REFERENCES `venue` (`venueid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data into the timetable table
INSERT INTO `timetable` (`lec_id`, `start_time`, `end_time`, `day`, `classtype`, `subID`, `venueID`, `cstatus`, `hours`) VALUES
  (1, '11:00:00', '13:00:00', 'wednesday', 'lecture', 'EC4', 'S101', 'cancelled', 2),
  (2, '15:00:00', '17:00:00', 'monday', 'lab', 'EC2', 'S102', 'active', 2),
  (3, '11:00:00', '13:00:00', 'monday', 'lecture', 'EC3', 'S103', 'active', 2),
  -- ... (insert other timetable data);

-- Commit changes
COMMIT;

-- Reset character set and collation
SET SQL_MODE = '';
SET time_zone = '';
