<?php

require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["lecname"]) && isset($_POST["email"])) {
        $lecname = $_POST["lecname"];
        $email = $_POST["email"];

        $sql = "INSERT INTO `lecturer`(`lecname`, `email`, `password`, `maxhours`)
                VALUES ('$lecname','$email','123456','16')";

        if ($mysqli->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }

        echo "
        <form action='timetable.php'>
            <button>Go back to timetable</button>
        </form>";
    } else {
        echo "Lecturer name and email are required.";
    }
}

$mysqli->close();

?>
