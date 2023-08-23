<?php

include ('db.php');

$sql = "SELECT * FROM user2 ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table><tr><th>IC Number</th><th>Name</th><th>Age</th><th>Weight</th><th>Address</th></tr>";

  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["icnumber"]."</td>
              <td>".$row["name"]."</td>
              <td>".$row["age"]."</td>
              <td>".$row["weight"]."</td>
              <td>".$row["address"]."</td>
          </tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$conn->close();

?>

<button onclick="window.location.href='renewal.php';">Update Page</button>
