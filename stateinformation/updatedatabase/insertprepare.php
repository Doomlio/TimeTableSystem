<?php

include ('db.php');


$ic=$_POST["ic"];
$name=$_POST["name"];
$age=$_POST["age"];
$weight=$_POST["weight"];
$address=$_POST["address"];

//echo $name;

// prepare and bind
$stmt = $mysqli->prepare("INSERT INTO user2 (icnumber, name, age, weight, address) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isiis", $ic, $name, $age, $weight, $address);

$stmt->execute();

echo "<p>New records created successfully</p>";
echo "
<form method=post action=view.php>
<input type=submit value='View Record'>

</form>



";



$stmt->close();
$mysqli->close();

?>