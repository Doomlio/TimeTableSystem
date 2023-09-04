<?php
require_once('../../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subID = $_POST["subID"];
    $subname = $_POST["subname"];
    $qual = $_POST["qual"];
    $sem = $_POST["sem"];
    $course = $_POST["course"];

    // Check for empty fields
    if (empty($subID) || empty($subname) || empty($qual) || empty($sem) || empty($course)) {
        echo "<script>alert('All fields are required.');</script>";
        header("refresh:1;url=insertsubject.php");
        exit; // Stop further execution
    }

    // Check if the subID already exists
    $checkQuery = "SELECT COUNT(*) as count FROM `subject` WHERE `subID` = '$subID'";
    $result = $mysqli->query($checkQuery);
    $row = $result->fetch_assoc();
    $subjectCount = $row['count'];

    if ($subjectCount > 0) {
        echo "<script>alert('A subject with this code already exists.');</script>";
    } else {
        $sql = "INSERT INTO `subject` (`subID`, `subname`, `qualification`,`sem`, `course`) 
                VALUES ('$subID', '$subname', '$qual', '$sem', '$course')";

        if ($mysqli->query($sql) === TRUE) {
            echo "<script>alert('New record created successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }

    header("refresh:1;url=insertsubject.php");
}

$mysqli->close();
?>
