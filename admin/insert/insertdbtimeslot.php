<?php
require_once('../../config.php');

$subcode = $_POST["subID"];
$starttime = $_POST["starttime"];
$endtime = $_POST["endtime"];
$day = $_POST["day"];
$lecID = $_POST["lecID"];

// Check for empty fields
if (empty($subcode) || empty($starttime) || empty($endtime) || empty($day) || empty($lecID)) {
    echo "<script>alert('Error: All fields are required.'); window.location.href = '/admin/insert/insertsubject.php';</script>";
} else {
    $sql = "INSERT INTO `timetable` (`subID`, `lec_id`, `start_time`, `end_time`, `day`) 
            VALUES (?, ?, ?, ?, ?)";
        
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssss", $subcode, $lecID, $starttime, $endtime, $day);

    if ($stmt->execute()) {
        echo "<script>alert('Timeslot added successfully.'); window.location.href = '/admin/view/timetable.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
?>
