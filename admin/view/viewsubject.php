
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/asset/timetable.css">   
    <!--  <link rel="stylesheet" href="/asset/timetable.css"> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGT Systems</title>
</head>

<?php

include ('../../config.php');

$sql = "SELECT subject.*, lecturer.lecname 
        FROM subject
        INNER JOIN lecturer ON subject.lecid = lecturer.lec_id
        ORDER BY subject.lecid;";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  echo "<table class ='custom-table'><tr><th>Subject Code</th><th>Subject Name</th><th>Qualification</th><th>Semester</th><th>Lecturer</th><th>course</th></tr>";

  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["subID"]."</td>
              <td>".$row["subname"]."</td>
              <td>".$row["qualification"]."</td>
              <td>".$row["sem"]."</td>
              <td>".$row["lecname"]."</td>
              <td>".$row["course"]."</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$mysqli->close();

?>
<div class="btn-container">
    <button class="link-button" onclick="window.location.href='/admin/view/timetable.php';">Back to Timetable</button>
    <button class="link-button" onclick="window.location.href='/admin/insert/insertsubject.php';">Insert Subjects</button>
    <button class="link-button" onclick="window.location.href='/admin/edit/editsubject.php';">Edit Subjects</button>
</div>
