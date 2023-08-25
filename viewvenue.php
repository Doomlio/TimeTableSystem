<?php
include('config.php'); // Make sure you have included the database configuration

$sql = "SELECT * FROM venue";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  echo "<table><tr><th>Venue ID</th><th>Venue Type</th><th>Subject ID</th></tr>";

  // Output data of each row
  while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["venueid"] . "</td>
              <td>" . $row["venuetype"] . "</td>
              <td>" . $row["subID"] . "</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$mysqli->close();
?>

<!-- Buttons for inserting and editing venues -->
<button onclick="window.location.href='insertvenue.php';">Insert Venue</button>
<button onclick="window.location.href='editvenue.php';">Edit Venue</button>
