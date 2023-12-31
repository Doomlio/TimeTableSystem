<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/asset/timetable.css">
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

include('../../config.php');

$sqlTimetable = "SELECT timetable.*, lecturer.lecname 
                FROM timetable
                INNER JOIN lecturer ON timetable.lec_id = lecturer.lec_id
                ORDER BY timetable.lec_id,
                 FIELD(day, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday');";
$resultTimetable = $mysqli->query($sqlTimetable);

if ($resultTimetable->num_rows > 0) {
    echo '<table class ="custom-table" class="custom-table">';
    echo '<tr><th>Timetable ID</th><th>Subject Name</th><th>Lecturer ID</th><th>Start Time</th><th>End Time</th><th>Day</th><th>Class Type</th><th>Sub ID</th><th>Venue ID</th></tr>';

    // output data of each row
    while($row = $resultTimetable->fetch_assoc()) {
        $timetableID = $row["timetable_id"];
        $lecID = $row["lec_id"];
        $lecName = $row["lecname"]; 
        $startTime = $row["start_time"];
        $endTime = $row["end_time"];
        $day = $row["day"];
        $classtype = $row["classtype"];
        $subID = $row["subID"];
        $venueID = $row["venueID"];
        
        echo "<tr>
              <td>$timetableID</td>
              <td>$subID</td>
              <td>$lecID</td>
              <td>$startTime</td>
              <td>$endTime</td>
              <td>$day</td>
              <td>$classtype</td>
              <td>$subID</td>
              <td>$venueID</td>
          </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$mysqli->close();


?>
<div class="btn-container">
<button class="link-button" onclick="window.location.href='/admin/view/timetable.php'">Back to Timetable</button>
    <button class="link-button" onclick="window.location.href='/admin/insert/inserttimeslot.php';">Add Timeslot</button>
    <button  class="link-button"onclick="window.location.href='/admin/edit/edittimeslot.php';">Edit Timeslot</button>
    <button  class="link-button"onclick="window.location.href='/admin/insert/regentime.php';">Recreate Timetable</button>
</div>
</body>
</html> 
