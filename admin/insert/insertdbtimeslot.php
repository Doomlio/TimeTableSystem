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
    // Prepare SQL query to retrieve lecturer's current total hours
    $currentHoursQuery = "SELECT SUM(TIMEDIFF(end_time, start_time)) AS current_hours FROM timetable WHERE lec_id = ?";
    $stmtHours = $mysqli->prepare($currentHoursQuery);
    $stmtHours->bind_param("s", $lecID);
    $stmtHours->execute();
    $result = $stmtHours->get_result();
    $row = $result->fetch_assoc();
    $currentHours = $row['current_hours'];

    // Prepare SQL query to retrieve lecturer's maxhours
    $maxHoursQuery = "SELECT maxhours FROM lecturer WHERE lec_id = ?";
    $stmtMaxHours = $mysqli->prepare($maxHoursQuery);
    $stmtMaxHours->bind_param("s", $lecID);
    $stmtMaxHours->execute();
    $resultMaxHours = $stmtMaxHours->get_result();
    $rowMaxHours = $resultMaxHours->fetch_assoc();
    $maxHours = $rowMaxHours['maxhours'];

    // Calculate the new total hours after adding the new timeslot
    $newHours = strtotime($endtime) - strtotime($starttime) + $currentHours;

    // Check if new total hours exceed maxhours
    if ($newHours <= $maxHours) {
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
    } else {
        echo "<script>alert('Error: The lecturer's total hours would exceed the maximum allowed hours.'); window.location.href = '/admin/insert/insertsubject.php';</script>";
    }

    $stmtHours->close();
    $stmtMaxHours->close();
}

$mysqli->close();
?>
