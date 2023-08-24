<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <link rel="stylesheet" href="timetable.css"> -->
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

include('config.php');

$sql = "SELECT t.*, s.subname 
        FROM timetable t
        LEFT JOIN subject s ON t.subID = s.subID";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Timetable ID</th><th>Subject Name</th><th>Lecturer ID</th><th>Start Time</th><th>End Time</th><th>Day</th><th>Class Type</th><th>Sub ID</th><th>Venue ID</th></tr>";

    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["timetable_id"] . "</td>
              <td>" . $row["subname"] . "</td>
              <td>" . $row["lec_id"] . "</td>
              <td>" . $row["start_time"] . "</td>
              <td>" . $row["end_time"] . "</td>
              <td>" . $row["day"] . "</td>
              <td>" . $row["classtype"] . "</td>
              <td>" . $row["subID"] . "</td>
              <td>" . $row["venueID"] . "</td>
          </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$mysqli->close();

?>
<button onclick="window.location.href='insertsubject.php';">Insert page</button>
<button onclick="window.location.href='edittimeslot.php';">Edit subjects</button>
</body>
</html>
