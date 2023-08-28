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
    <script>
        function showMessage(message) {
            alert(message);
        }
    </script>
</head>

<body>

<?php
session_start();
require_once("config.php");

if (!isset($_SESSION["lec_id"]) || !isset($_SESSION["name"])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit;
}

$lec_id = $_SESSION["lec_id"];
$lecname = $_SESSION["name"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["activate"])) {
        $timetableID = $_POST["activate"];
        $updateQuery = "UPDATE timetable SET cstatus = 'active' WHERE timetable_id = '$timetableID'";
        if ($mysqli->query($updateQuery)) {
            echo "Class activated successfully.";
        } else {
            echo "Error activating class.";
        }
    } elseif (isset($_POST["cancel"])) {
        $timetableID = $_POST["cancel"];
        $updateQuery = "UPDATE timetable SET cstatus = 'cancelled' WHERE timetable_id = '$timetableID'";
        if ($mysqli->query($updateQuery)) {
            echo "Class cancelled successfully.";
        } else {
            echo "Error cancelling class.";
        }
    }
}

$result = $mysqli->query("
    SELECT t.*, s.subname, l.lecname
    FROM timetable t
    JOIN lecturer l ON t.lec_id = l.lec_id
    JOIN subject s ON t.subID = s.subID
    WHERE t.lec_id = '$lec_id'
    ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')
");

if ($result->num_rows > 0) {
    echo "<form method='post' action='viewlectimeslot.php'>
    <table class ='custom-table'>
    <tr><th>Timetable ID</th>
    <th>Subject ID</th>
    <th>Subject Name</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Day</th>
    <th>Class Type</th>
    <th>Sub ID</th>
    <th>Venue ID</th>
    <th>Actions</th>
    </tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        $timetableID = $row["timetable_id"];
        $lecName = $row["lecname"]; 
        $subName = $row["subname"];
        $startTime = $row["start_time"];
        $endTime = $row["end_time"];
        $day = $row["day"];
        $classtype = $row["classtype"];
        $subID = $row["subID"];
        $venueID = $row["venueID"];
        
        echo "<tr>
              <td>$timetableID</td>
              <td>$subID</td>
              <td>$subName</td>
              <td>$startTime</td>
              <td>$endTime</td>
              <td>$day</td>
              <td>$classtype</td>
              <td>$subID</td>
              <td>$venueID</td>
              <td>
              <button type='submit' class='link-button' name='activate' value='$timetableID'>Activate Class</button>
              <br>
              <button type='submit' class='back' name='cancel' value='$timetableID'>Cancel Class</button>
            </td>
        </tr>";
    }
    echo "</table>
    </form>";
} else {
    echo "0 results";
}
$mysqli->close();

?>
<form method="post" action="lectimetable.php">
      <button class="link-button "type="submit">Back to timetable</button>
  </form>
</body>
</html>