<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGT Systems</title>
    <style>
        .class-type {
            font-size: 12px;
            font-weight: normal;
            display: block;
        }
        td {
            height: 30px;
            border: 1px solid #000;
            padding: 10px;
            width: 100px;
        }
    </style>
</head>

<body>
<?php
session_start();
include ('config.php');

$lecID = $_SESSION["lec_id"]; // Get lecturer ID from the session
$classType = "replacement"; // Class type condition

$sqlTimetable = "SELECT * FROM timetable WHERE lec_id = ? AND classtype = ? AND cstatus = ?";
$stmt = $mysqli->prepare($sqlTimetable);
$stmt->bind_param("iss", $lecID, $classType, $classType);
$stmt->execute();
$resultTimetable = $stmt->get_result();

if ($resultTimetable->num_rows > 0) {
    echo "<table><tr><th>Timetable ID</th><th>Subject Name</th><th>Start Time</th><th>End Time</th><th>Day</th><th>Class Type</th><th>Sub ID</th><th>Venue ID</th></tr>";

    while($row = $resultTimetable->fetch_assoc()) {
        $timetableID = $row["timetable_id"];
        $startTime = $row["start_time"];
        $endTime = $row["end_time"];
        $day = $row["day"];
        $classType = $row["classtype"];
        $subID = $row["subID"];
        $venueID = $row["venueID"];
        
        echo "<tr>
              <td>$timetableID</td>
              <td>$subID</td>
              <td>$startTime</td>
              <td>$endTime</td>
              <td>$day</td>
              <td>$classType</td>
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
<form method="post" action="timetable.php">
        <button type="submit">Back to timetable</button>
    </form>
<button onclick="window.location.href='insertsubject.php';">Add timeslot</button>
<button onclick="window.location.href='edittimeslot.php';">Edit timeslot</button>
</body>
</html>
