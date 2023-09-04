<?php
require_once('../../config.php');

// Retrieve POST data
$subcode = $_POST["subID"];
$starttime = $_POST["starttime"];
$endtime = $_POST["endtime"];
$day = $_POST["day"];
$lecID = $_POST["lecID"];
$classType = $_POST["type"];
$venue = $_POST["venue"];

if (empty($subcode) || empty($lecID) || empty($starttime) || empty($endtime) || empty($day) || empty($classType) || empty($venue)) {
    echo "<script>alert('Error: All fields are required.'); window.location.href = '/admin/insert/insertsubject.php';</script>";
} else {
     // Calculate hours (difference between endtime and starttime)
     $hours = (strtotime($endtime) - strtotime($starttime)) / 3600;
     
   // Prepare SQL query to insert data into the timetable table
   $sql = "INSERT INTO `timetable` (`subID`, `lec_id`, `start_time`, `end_time`, `day`, `classtype`, `venueID`, `hours`) 
   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssssssi", $subcode, $lecID, $starttime, $endtime, $day, $classType, $venue, $hours);

    if ($stmt->execute()) {
        echo "<script>alert('Timeslot added successfully.'); window.location.href = '/admin/view/timetable.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
?>