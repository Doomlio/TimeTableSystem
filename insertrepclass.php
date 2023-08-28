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
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/asset/timetable.css">
<link rel="stylesheet" href="/asset/repclass.css">
    <title>Insert Replacement class</title>
</head>
<body>
    <h1>Insert Replacement class</h1>
    <div class="formboxsub2">
    <form method="post" action="insertdbrepclass.php">
       <label class="subID"> subject code:</label> 
        <select class="subIDtext"name="subID">
<?php
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["subID"] . "'>" . $row["subname"] . "</option>";
        }
?>
        </select><br>
        <label class="stime">start time:</label>
        <input type="time" class="starttime"name="starttime" value="00:00" required>

        <label class="endtimetext">end time:</label>
        <input type="time" class="endtime"name="endtime" value="00:00" required>
        <label class="day2">Day:</label>
        <select  class="daysel" name="day">
            <option value="monday">Monday</option>
            <option value="tuesday">Tuesday</option>
            <option value="wednesday">Wednesday</option>
            <option value="thursday">Thursday</option>
            <option value="friday">Friday</option>
        </select>
        <label class="type">type</label>
        <select class="typesel" name="type">
            <option value="lecture">Lecture</option>
            <option value="lab">Lab</option>
        </select>
<?php
        // Display venue options
        $venueQuery = "SELECT `venueID`, `venuetype` FROM `venue`";
        $venueResult = $mysqli->query($venueQuery);
        if ($venueResult && $venueResult->num_rows > 0) {
            echo '<label class="venueID">Venue</label>';
            echo ' <select class="venuesel" name="venueID">';
            while ($venueRow = $venueResult->fetch_assoc()) {
                echo "<option value='" . $venueRow["venueID"] . "'>" . $venueRow["venueID"] . " - " . $venueRow["venuetype"] . "</option>";
            }
            echo '</select><br>';
        } else {
            echo 'No venues available.';
        }
?>
        <input type="submit">
    </form>
</body>
</html>

<?php
    } else {
        echo "No subjects assigned to this lecturer.";
    }
}

$stmt->close();
$mysqli->close();
?>
