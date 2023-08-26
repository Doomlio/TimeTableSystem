<?php
require_once('config.php');
session_start();

$lecid = $_SESSION["lec_id"];

$query = "SELECT `subID`, `subname` FROM `subject` WHERE `lecid` = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $lecid);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo "Query Error: " . $mysqli->error;
} else {
    if ($result->num_rows > 0) {
        echo '<html><h1>Insert Replacement class</h1>';
        echo '<form method="post" action="insertdbrepclass.php">';
        echo 'subject code:  <select name="subID">';
        
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["subID"] . "'>" . $row["subname"] . "</option>";
        }
        
        echo '</select><br>';
        echo 'start time:<input type="time" id="appt" name="starttime" value="00:00" required /><br>';
        echo 'end time: <input type="time" id="appt" name="endtime" value="00:00" required /><br>';
        echo 'day: <select name="day">';
        echo '<option value="monday">monday</option>';
        echo '<option value="tuesday">tuesday</option>';
        echo '<option value="wednesday">wednesday</option>';
        echo '<option value="thursday">thursday</option>';
        echo '<option value="friday">friday</option>';
        echo '</select><br>';
        echo 'class type:  <select name="type">';
        echo '<option>lecture</option>';
        echo '<option>lab</option>';
        echo '</select><br>';
        
        // Display venue options
        $venueQuery = "SELECT `venueID`, `venuetype` FROM `venue`";
        $venueResult = $mysqli->query($venueQuery);
        if ($venueResult && $venueResult->num_rows > 0) {
            echo 'venue: <select name="venueID">';
            while ($venueRow = $venueResult->fetch_assoc()) {
                echo "<option value='" . $venueRow["venueID"] . "'>" . $venueRow["venueID"] . " - " . $venueRow["venuetype"] . "</option>";
            }
            echo '</select><br>';
        } else {
            echo 'No venues available.';
        }

        echo '<input type="submit">';
        echo '</form></html>';
    } else {
        echo "No subjects assigned to this lecturer.";
    }
}

$stmt->close();
$mysqli->close();
?>
