<?php

require_once('ref/connection.php');


$ic=$_POST["ic"];
$name=$_POST["name"];
$age=$_POST["age"];
$weight=$_POST["weight"];
$address=$_POST["address"];

//echo $name;


$sql = "INSERT INTO user2 (icnumber, name, age, weight, address)
VALUES ($ic, $name, $age, $weight, $address)";

if ($mysqli->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}

$mysqli->close();



?>