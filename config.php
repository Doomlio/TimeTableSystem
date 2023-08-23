<?php
$mysqli = new mysqli("localhost","root","","fyptimetable");
// Check connection
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error; exit();
}else{
    echo"the database is connected" ;
    echo "<br>";
}
?>