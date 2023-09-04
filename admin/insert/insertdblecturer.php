<?php

require_once('../../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["lecname"]) && isset($_POST["email"])) {
        $lecname = $_POST["lecname"];
        $email = $_POST["email"];

        $sql = "INSERT INTO `lecturer`(`lecname`, `lecemail`, `lecpassword`, `maxhours`)
                VALUES ('$lecname','$email','123456','16')";

        if ($mysqli->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }

        header("refresh:1;url=/admin/view/viewlecturer.php");
        echo "<script>alert('Lecturer added  successfully.')</script>";
    } else {
        echo "Lecturer name and email are required.";
    }
}

$mysqli->close();

?>
