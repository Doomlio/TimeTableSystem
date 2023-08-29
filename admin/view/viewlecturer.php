<?php
include ('../../config.php');

$sql = "SELECT * FROM lecturer";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  echo "<table class ='custom-table'><tr><th>Lecturer ID</th><th>Lecturer Name</th><th>Email</th><th>Max Hours</th></tr>";

  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["lec_id"]."</td>
              <td>".$row["lecname"]."</td>
              <td>".$row["email"]."</td>
              <td>".$row["maxhours"]."</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AGT System</title>
  <link rel="stylesheet" href="/asset/timetable.css">
</head>
<body>
  
</body>
</html>
<button class="link-button2" onclick="window.location.href='/admin/view/timetable.php';">Back to timetable</button>
<button class="link-button2" onclick="window.location.href='/admin/insert/insertlecturer.php';">Insert Lecturer</button>
<button class="link-button2" onclick="window.location.href='/admin/edit/editlecturer.php';">Edit Lecturer</button>
