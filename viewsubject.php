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
$conn->close();

?>

<button onclick="window.location.href='renewal.php';">Update Page</button>
