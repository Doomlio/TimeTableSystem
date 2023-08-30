<?php
require_once('../../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subID = $_POST["subID"];
    $subname = $_POST["subname"];
    $qual = $_POST["qual"];
    $sem = $_POST["sem"];
    $course = $_POST["course"];

    $sql = "INSERT INTO `subject` (`subID`, `subname`, `qualification`,`sem`, `course`) 
            VALUES ('$subID', '$subname', '$qual', '$sem', '$course')";

    if ($mysqli->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    header("refresh:1;url=insertsubject.php");
    echo "<script>alert('subject added successfully.')</script>";
}

$mysqli->close();
?>
