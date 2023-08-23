<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subID = $_POST["lecID"];    // Changed 'subID' to 'lecID'
    $reqtext = $_POST["reqtext"];

    $sql = "INSERT INTO `request` (`subID`, `reqtext`) 
        VALUES ('$subID', '$reqtext')";

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

$mysqli->close();
?>
