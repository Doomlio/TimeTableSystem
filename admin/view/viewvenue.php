<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGT Systems</title>
    
</head>

<body>
<?php
include('../../config.php'); // Make sure you have included the database configuration

$sql = "SELECT * FROM venue";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  echo "<table class ='custom-table'><tr><th>Venue ID</th><th>Venue Type</th></tr>";

  // Output data of each row
  while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["venueid"] . "</td>
              <td>" . $row["venuetype"] . "</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$mysqli->close();
?>


<button class="link-button" onclick="window.location.href='/admin/view/timetable.php';">Back to Timetable</button>
<button class="link-button"onclick="window.location.href='/admin/insert/insertvenue.php';">Insert Venue</button>
<button class="link-button" onclick="window.location.href='/admin/edit/ editvenue.php';">Edit Venue</button>
</body>
</html>