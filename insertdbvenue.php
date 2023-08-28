<?php

require_once('config.php');

$isSuccess = false; // Initialize to false

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["venueID"]) && isset($_POST["type"])) {
        $venueID = $_POST["venueID"];
        $type = $_POST["type"];

        $sql = "INSERT INTO `venue`(`venueid`, `venuetype`) VALUES ('$venueID','$type')";

        if ($mysqli->query($sql) === TRUE) {
            $isSuccess = true; // Set to true if insertion is successful
        }
    }
}

$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/asset/timetable.css">
    <title>Insert Venue</title>
</head>
<body>
    <h1>Insert Venue</h1>
    <div class="formbox3">
        <!-- Show a JavaScript alert based on success or failure -->
        <script>
            <?php if ($isSuccess) { ?>
                alert("Record created successfully");
            <?php } else { ?>
                alert("Error: Record creation failed");
            <?php } ?>
            window.location.href = "viewvenue.php"; // Redirect to viewvenue.php
        </script>
    </div>
</body>
</html>
