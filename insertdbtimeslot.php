<?php
require_once('config.php');

$subcode = $_POST["subID"];
$starttime = $_POST["starttime"];
$endtime = $_POST["endtime"];
$day = $_POST["day"];
$lecID=$_POST["lecID"];


$sql = "INSERT INTO `timetable` (`subID`, `lec_id`, `start_time`, `end_time`, `day`) 
        VALUES ('$subcode', '$lecID', '$starttime', '$endtime', '$day')";
    
      
if ($mysqli->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}

echo "
<form action='timetable.php'>
    <button>Go back</button>
</form>";
$mysqli->close();
?>

