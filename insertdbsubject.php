<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subID = $_POST["subID"];
    $subname = $_POST["subname"];
    $qual = $_POST["qual"];
    $sem = $_POST["sem"];
    $lecID = $_POST["lecID"];
    $course = $_POST["course"];
    
    $sql = "INSERT INTO `subject` (`subID`, `subname`, `qualification`,      `sem`, `lecid`, `course`) 
            VALUES ('$subID', '$subname', '$qual', '$sem', '$lecID', '$course')";

    if ($mysqli->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    echo "
    <form action='timetable.php'>
    <button>Go back to timetable</button>
    </form>";
}

echo " <input type='submit' value='Submit'>";

$mysqli->close();
?>