<?php
session_start();
require_once("config.php");

if (!isset($_SESSION["lec_id"]) || !isset($_SESSION["name"])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit;
}

$lec_id = $_SESSION["lec_id"];
$showAlert = false; // Initialize showAlert to false

// Delete code
if (isset($_POST["delete"])) {
    $deleteTimetableID = $_POST["delete"];
    $sqlDeleteRecord = $mysqli->prepare("DELETE FROM timetable WHERE timetable_id=?");
    $sqlDeleteRecord->bind_param('s', $deleteTimetableID);
    $sqlDeleteRecord->execute();
    $sqlDeleteRecord->close();

    echo "<script>alert('Record deleted successfully.')</script>";
    header("refresh:1;url=edittimeslot.php");
}

if (isset($_POST["savedata"])) {
    foreach ($_POST['timetableID'] as $key => $timetableID) {
        // Get all data from the form
        $newTimetableID = $_POST['newTimetableID'][$key];
        $lecID = $_POST['lecID'][$key];
        $startTime = $_POST['startTime'][$key];
        $endTime = $_POST['endTime'][$key];
        $day = $_POST['day'][$key];
        $classType = $_POST['classType'][$key];
        $subID = $_POST['subID'][$key];
        $venueID = $_POST['venueID'][$key];

        // Check for clashes with existing timeslots
        $sqlCheckClashes = "SELECT * FROM timetable WHERE lec_id = ? AND day = ? AND ((start_time >= ? AND start_time < ?) OR (end_time > ? AND end_time <= ?) OR (start_time <= ? AND end_time >= ?))";
        $stmtCheckClashes = $mysqli->prepare($sqlCheckClashes);
        $stmtCheckClashes->bind_param('ssssssss', $lecID, $day, $startTime, $endTime, $startTime, $endTime, $startTime, $endTime);
        $stmtCheckClashes->execute();
        $resultClashes = $stmtCheckClashes->get_result();
        $clashesExist = ($resultClashes->num_rows > 0);
        $stmtCheckClashes->close();

        // Prepare and execute the SQL query to update the timetable data
        $sqlUpdateTimetable = "UPDATE timetable SET timetable_id=?, lec_id=?, start_time=?, end_time=?, day=?, classtype=?, subID=?, venueID=? WHERE timetable_id=?";
        $stmtUpdateTimetable = $mysqli->prepare($sqlUpdateTimetable);
        $stmtUpdateTimetable->bind_param('sssssssss', $newTimetableID, $lecID, $startTime, $endTime, $day, $classType, $subID, $venueID, $timetableID);
        $stmtUpdateTimetable->execute();
        $stmtUpdateTimetable->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Timeslots</title>
</head>
<body>
    <h2>Edit Timeslots</h2>
    <form method="post" action="">
        <table id="myTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lecturer ID</th>
                    <th>Lecturer name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Day</th>
                    <th>Class Type</th>
                    <th>Sub ID</th>
                    <th>Venue ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <?php
            $sqlTimetable = "SELECT timetable.*, lecturer.lecname, lecturer.maxhours 
            FROM timetable
            INNER JOIN lecturer ON timetable.lec_id = lecturer.lec_id
            WHERE timetable.lec_id = ? AND timetable.cstatus = 'replacement'
            ORDER BY timetable.lec_id, FIELD(LOWER(day), 'monday', 'tuesday', 'wednesday', 'thursday', 'friday');";
            $stmtTimetable = $mysqli->prepare($sqlTimetable);
            $stmtTimetable->bind_param('s', $lec_id);
            $stmtTimetable->execute();
            $resultTimetable = $stmtTimetable->get_result();
            $no = 1;
            while($row = $resultTimetable->fetch_assoc()) {
                $lecID = $row["lec_id"];
                $lecName = $row["lecname"];
                $startTime = $row["start_time"];
                $endTime = $row["end_time"];
                $day = $row["day"];
                $classType = $row["classtype"];
                $subID = $row["subID"];
                $venueID = $row["venueID"];
                $cstatus = $row["cstatus"];
                $maxHours = $row["maxhours"];
                $totalHoursOfClass = 0;
                $timetableID = $row["timetable_id"];
            ?>
            <tr>
                <td><?php echo $no ?></td>
                <td><?php echo $lecID ?></td>
                <td><?php echo $lecName ?></td>
                <td><input type="text" name="startTime[]" value="<?php echo $startTime ?>"></td>
                <td><input type="text" name="endTime[]" value="<?php echo $endTime ?>"></td>
                <td><input type="text" name="day[]" value="<?php echo $day ?>"></td>
                <td><input type="text" name="classType[]" value="<?php echo $classType ?>"></td>
                <td><input type="text" name="subID[]" value="<?php echo $subID ?>"></td>
                <td><input type="text" name="venueID[]" value="<?php echo $venueID ?>"></td>
                <td><input type="text" name="cstatus[]" value="<?php echo $cstatus ?>"></td>
                <?php if ($totalHoursOfClass > $maxHours): ?>
                    <td colspan="2"><span style="color: red;">Total hours exceed maximum!</span></td>
                <?php else: ?>
                    <td>
                        <button type="submit" name="delete" value="<?php echo $timetableID ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                    </td>
                <?php endif; ?>
            </tr>
            <?php
                $no++;
            }
            ?>
        </table>
        <br>
        <button name="savedata" class="button">Save</button>
    </form>
    <form method="post" action="timetable.php">
        <button type="submit">Back to timetable</button>
    </form>
</body>
</html>
