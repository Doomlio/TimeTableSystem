<?php
include('config.php'); // Make sure you have included the database configuration

$sql = "SELECT * FROM venue";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  echo "<table><tr><th>Venue ID</th><th>Venue Type</th></tr>";

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
<form method="post" action="lectimetable.php">
        <button type="submit">Back to timetable</button>
  </form>
