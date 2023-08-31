<?php
require_once('../../config.php');

$isSuccess = false; // Initialize to false
$isEmptyError = false; // Initialize to false
$isDuplicateError = false; // Initialize to false

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["venueID"]) && isset($_POST["type"])) {
        $venueID = $_POST["venueID"];
        $type = $_POST["type"];

        // Check for empty fields
        if (empty($venueID) || empty($type)) {
            $isEmptyError = true;
        } else {
            // Check for duplicate venueID
            $checkQuery = "SELECT COUNT(*) as count FROM `venue` WHERE `venueid` = '$venueID'";
            $result = $mysqli->query($checkQuery);
            $row = $result->fetch_assoc();
            $venueCount = $row['count'];

            if ($venueCount > 0) {
                $isDuplicateError = true;
            } else {
                $sql = "INSERT INTO `venue`(`venueid`, `venuetype`) VALUES ('$venueID','$type')";

                if ($mysqli->query($sql) === TRUE) {
                    $isSuccess = true; // Set to true if insertion is successful
                }
            }
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
                window.location.href = "/admin/view/viewvenue.php"; // Redirect to viewvenue.php
            <?php } elseif ($isEmptyError) { ?>
                alert("Error: Empty fields");
                window.location.href = "/admin/insert/insertvenue.php"; // Redirect back to insertvenue.php
            <?php } elseif ($isDuplicateError) { ?>
                alert("Error: Duplicate venueID");
                window.location.href = "/admin/insert/insertvenue.php"; // Redirect back to insertvenue.php
            <?php } else { ?>
                alert("Error: Record creation failed");
                window.location.href = "/admin/insert/insertvenue.php"; // Redirect back to insertvenue.php
            <?php } ?>
        </script>
    </div>
</body>
</html>
