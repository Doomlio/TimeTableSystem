
<!DOCTYPE html>
<html lang="en">
<head>   
    <!--  <link rel="stylesheet" href="timetable.css"> -->
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

<?php

include ('config.php');

$sql = "SELECT * FROM subject ";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  echo "<table><tr><th>Subject Code</th><th>Subject Name</th><th>Qualification</th><th>Semester</th><th>Lecturer</th><th>course</th></tr>";

  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["subID"]."</td>
              <td>".$row["subname"]."</td>
              <td>".$row["qualification"]."</td>
              <td>".$row["sem"]."</td>
              <td>".$row["lecid"]."</td>
              <td>".$row["course"]."</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$mysqli->close();

?>
<button onclick="window.location.href='insertsubject.php';">Insert page</button>
<button onclick="window.location.href='editsubject.php';">edit subjects</button>
