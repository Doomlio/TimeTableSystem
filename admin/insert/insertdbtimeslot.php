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

// Check if any required field is empty
if (empty($subcode) || empty($lecID) || empty($starttime) || empty($endtime) || empty($day) || empty($classType) || empty($venue)) {
    echo "<script>alert('Error: All fields are required.'); window.location.href = '/admin/insert/insertsubject.php';</script>";
    exit;
}

// Calculate hours (difference between endtime and starttime)
$hours = (strtotime($endtime) - strtotime($starttime)) / 3600;

// Prepare SQL query to check for time slot clashes
$sqlCheckClashes = "SELECT * FROM timetable WHERE  day = ? 
                    AND (
                        (start_time >= ? AND start_time < ?) 
                        OR (end_time > ? AND end_time <= ?) 
                        OR (start_time <= ? AND end_time >= ?) 
                        OR (start_time = ? AND end_time = ?)
                    )";
$stmtCheckClashes = $mysqli->prepare($sqlCheckClashes);
$stmtCheckClashes->bind_param("ssssssssss", $lecID, $day, $starttime, $endtime, $starttime, $endtime, $starttime, $endtime, $starttime, $endtime);
$stmtCheckClashes->execute();
$resultCheckClashes = $stmtCheckClashes->get_result();

// Count the number of clashes
$clashCount = $resultCheckClashes->num_rows;

// Prepare SQL query to retrieve lecturer's maxhours
$maxHoursQuery = "SELECT maxhours FROM lecturer WHERE lec_id = ?";
$stmtMaxHours = $mysqli->prepare($maxHoursQuery);
$stmtMaxHours->bind_param("s", $lecID);
$stmtMaxHours->execute();
$resultMaxHours = $stmtMaxHours->get_result();
$rowMaxHours = $resultMaxHours->fetch_assoc();
$maxHours = $rowMaxHours['maxhours'];

// Prepare SQL query to retrieve lecturer's current total hours
$currentHoursQuery = "SELECT SUM(hours) AS current_hours FROM timetable WHERE lec_id = ?";
$stmtHours = $mysqli->prepare($currentHoursQuery);
$stmtHours->bind_param("s", $lecID);
$stmtHours->execute();
$resultHours = $stmtHours->get_result();
$rowHours = $resultHours->fetch_assoc();
$currentHours = $rowHours['current_hours'];

// Calculate the new total hours after adding the new timeslot
$newHours = $hours + $currentHours;

// Check for clashes and max hours
if ($clashCount > 0) {
    echo "<script>alert('Error: Time slot clashes with an existing one.'); window.location.href = '/admin/view/viewtimeslot.php';</script>";
} elseif ($newHours > $maxHours) {
    echo "<script>alert('Error: Total hours exceed the maximum hours allowed for the lecturer.'); window.location.href = '/admin/view/viewtimeslot.php';</script>";
} else {
    // Proceed with inserting the new timeslot

    // Prepare SQL query to insert data into the timetable table
    $sqlInsert = "INSERT INTO `timetable` (`subID`, `lec_id`, `start_time`, `end_time`, `day`, `classtype`, `venueID`, `hours`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtInsert = $mysqli->prepare($sqlInsert);
    $stmtInsert->bind_param("sssssssi", $subcode, $lecID, $starttime, $endtime, $day, $classType, $venue, $hours);

    if ($stmtInsert->execute()) {
        echo "<script>alert('Timeslot added successfully.'); window.location.href = '/admin/view/timetable.php';</script>";
    } else {
        echo "Error: " . $sqlInsert . "<br>" . $stmtInsert->error;
    }

    $stmtInsert->close();
}

$stmtCheckClashes->close();
$stmtMaxHours->close();
$stmtHours->close();

$mysqli->close();
?>
