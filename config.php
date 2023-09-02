<?php
$mysqli = new mysqli("localhost", "root", "", "fyptimetable");
// Check connection
if ($mysqli->connect_errno) {
    $errorMessage = "Failed to connect to MySQL: " . $mysqli->connect_error;
    echo "<script>alert('$errorMessage');</script>";
}
?>