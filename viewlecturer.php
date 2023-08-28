<?php
include ('config.php');

$sql = "SELECT * FROM lecturer";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
  echo "<table class="custom-table"><tr><th>Lecturer ID</th><th>Lecturer Name</th><th>Email</th><th>Password</th><th>Max Hours</th></tr>";

  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["lec_id"]."</td>
              <td>".$row["lecname"]."</td>
              <td>".$row["email"]."</td>
              <td>".$row["password"]."</td>
              <td>".$row["maxhours"]."</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$mysqli->close();
?>
<form method="post" action="timetable.php">
        <button type="submit">Back to timetable</button>
    </form>
<button onclick="window.location.href='insertlecturer.php';">Insert Lecturer</button>
<button onclick="window.location.href='editlecturer.php';">Edit Lecturer</button>
