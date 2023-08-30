<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGT Systems</title>
   
</head>
<h2>VIEW REPLACEMENT CLASS</h2>
<body>
<?php
session_start();
include ('../../config.php');

$lecID = $_SESSION["lec_id"]; // Get lecturer ID from the session
$classtype = "replacement"; // Class type condition

$sqlTimetable = "SELECT * FROM timetable WHERE lec_id = ? AND cstatus = ?";
$stmt = $mysqli->prepare($sqlTimetable);
$stmt->bind_param("is", $lecID, $classtype);
$stmt->execute();
$resultTimetable = $stmt->get_result();

if ($resultTimetable->num_rows > 0) {
    echo "<table class ='custom-table'><tr><th>Timetable ID</th><th>Subject Name</th><th>Start Time</th><th>End Time</th><th>Day</th><th>Sub ID</th><th>Venue ID</th></tr>";

    while($row = $resultTimetable->fetch_assoc()) {
        $timetableID = $row["timetable_id"];
        $startTime = $row["start_time"];
        $endTime = $row["end_time"];
        $day = $row["day"];
        $subID = $row["subID"];
        $venueID = $row["venueID"];
        
        echo "<tr>
              <td>$timetableID</td>
              <td>$subID</td>
              <td>$startTime</td>
              <td>$endTime</td>
              <td>$day</td>
              <td>$subID</td>
              <td>$venueID</td>
          </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$stmt->close();
$mysqli->close();
?>
<button class="link-button" onclick="window.location.href='/lecturer/view/lectimetable.php';">Back to timetable</button>
<button class="link-button" onclick="window.location.href='/lecturer/insert/insertrepclass.php';">Add replacement</button>
<button class="link-button" onclick="window.location.href='/lecturer/edit/editrepclass.php';">Edit replacement</button>
</body>
</html>
