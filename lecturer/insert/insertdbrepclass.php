<?php
session_start();
require_once("../../config.php");

if (!isset($_SESSION["lec_id"]) || !isset($_SESSION["name"])) {
    // Redirect the user to the login page if not logged in
    header("Location: /lecturer/login/login.php");
    exit;
}

$lec_id = $_SESSION["lec_id"];
$lecname = $_SESSION["name"];

$subcode = $_POST["subID"];
$starttime = $_POST["starttime"];
$endtime = $_POST["endtime"];
$day = $_POST["day"];
$lecID = $_SESSION["lec_id"];
$venueID = $_POST["venueID"];
$classtype =$_POST["type"];

// Calculate the difference in hours
$startTimestamp = strtotime($starttime);
$endTimestamp = strtotime($endtime);
$hours = ($endTimestamp - $startTimestamp) / 3600; // Convert seconds to hours

// Check for conflicts
$conflictQuery = "SELECT * FROM `timetable` WHERE `lec_id` = '$lecID' AND `day` = '$day'
    AND ((`start_time` >= '$starttime' AND `start_time` < '$endtime')
    OR (`end_time` > '$starttime' AND `end_time` <= '$endtime'))";
$conflictResult = $mysqli->query($conflictQuery);

if ($conflictResult && $conflictResult->num_rows > 0) {
    // Conflict with lecturer's existing timetable
    echo "<script>alert('There is a time conflict with your existing timetable. Please choose a different time.');</script>";
    echo "<script>window.location.href = 'insertrepclass.php';</script>";
    exit;
}

$venueConflictQuery = "SELECT * FROM `timetable` WHERE `venueID` = '$venueID' AND `day` = '$day'
    AND ((`start_time` >= '$starttime' AND `start_time` < '$endtime')
    OR (`end_time` > '$starttime' AND `end_time` <= '$endtime'))";
$venueConflictResult = $mysqli->query($venueConflictQuery);

if ($venueConflictResult && $venueConflictResult->num_rows > 0) {
    // Conflict with existing timetable in the same venue
    echo "<script>alert('There is a venue conflict with another class at this time. Please choose a different venue .');</script>";
    echo "<script>window.location.href = 'insertrepclass.php';</script>";
    exit;
}

if (isset($_POST["confirm"]) && $_POST["confirm"] == 1) {
    // Insert data into the database after confirmation
    $sql = "INSERT INTO `timetable` (`subID`, `lec_id`, `start_time`, `end_time`, `day`, `venueID`, `hours`, `classtype`,`cstatus`) 
            VALUES ('$subcode', '$lecID', '$starttime', '$endtime', '$day', '$venueID', '$hours', '$classtype','replacement')";

    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('New record created successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "\\n" . $mysqli->error . "');</script>";
    }

    // Redirect to /lecturer/view/lectimetable.php after confirmation
    echo "<script>window.location.href = '/lecturer/view/lectimetable.php';</script>";
    exit;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <title>Confirmation</title>
</head>
    <body>
        <h1>Confirm Timetable Entry</h1>
        <p>Subject: <?php echo $subcode; ?></p>
        <p>Start Time: <?php echo $starttime; ?></p>
        <p>End Time: <?php echo $endtime; ?></p>
        <p>Day: <?php echo $day; ?></p>
        <p>Venue: <?php echo $venueID; ?></p>
        <p>Class Type : <?php echo $classtype; ?></p>

        <form method="post">
            <input type="hidden" name="subID" value="<?php echo $subcode; ?>">
            <input type="hidden" name="starttime" value="<?php echo $starttime; ?>">
            <input type="hidden" name="endtime" value="<?php echo $endtime; ?>">
            <input type="hidden" name="day" value="<?php echo $day; ?>">
            <input type="hidden" name="venueID" value="<?php echo $venueID; ?>">
            <input type="hidden" name="type" value="<?php echo $classtype; ?>">
            <input type="hidden" name="confirm" value="1">
            <input type="submit" value="Confirm">
        </form>

        <form action="/lecturer/view/lectimetable.php">
            <button>Cancel</button>
        </form>
    </body>
    </html>
