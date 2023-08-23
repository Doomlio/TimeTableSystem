<?php

require_once('config.php');


$lecid=$_POST["lecid"];
$lecname=$_POST["lecname"];
$email=$_POST["email"];
$password=$_POST["password"];
    
$sql = "INSERT INTO `timetable` (`semestertype`, `subject_name`, `start_time`, `end_time`, `day`) 
        VALUES ('$lecid', '$lecname', '$email', '$password')";


if ($mysqli->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}
echo"
<form action='timetable.php'>
<button>go back to timetable</button>
</form>";
$mysqli->close();
?>


