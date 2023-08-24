<?php 
// Database connection
include ('config.php');

$showAlert = false; // Initialize showAlert to false

// Delete code
if (isset($_POST["delete"])) {
    $deleteTimetableID = $_POST["delete"];
    $sqlDeleteRecord = $mysqli->prepare("DELETE FROM timetable WHERE timetable_id=?");
    $sqlDeleteRecord->bind_param('s', $deleteTimetableID);
    $sqlDeleteRecord->execute();
    $sqlDeleteRecord->close();
    
    echo "<script>alert('Record deleted successfully.')</script>";
    header("refresh:1;url=editsubject.php");
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

        if ($clashesExist) {
            // Handle the error case here (e.g., show an error message)
        } else {
            // Prepare and execute the SQL query to update the timetable data
            $sqlUpdateTimetable = "UPDATE timetable SET timetable_id=?, lec_id=?, start_time=?, end_time=?, day=?, classtype=?, subID=?, venueID=? WHERE timetable_id=?";
            $stmtUpdateTimetable = $mysqli->prepare($sqlUpdateTimetable);
            $stmtUpdateTimetable->bind_param('sssssssss', $newTimetableID, $lecID, $startTime, $endTime, $day, $classType, $subID, $venueID, $timetableID);
            $stmtUpdateTimetable->execute();
            $stmtUpdateTimetable->close();
        }
    }
}

// Reassign timeslots
if (isset($_POST["reassign"])) {
    // Get distinct lecturer IDs
    $sqlDistinctLecturers = "SELECT DISTINCT lec_id FROM timetable";
    $resultDistinctLecturers = $mysqli->query($sqlDistinctLecturers);
    $lecturerIDs = [];

    while ($row = $resultDistinctLecturers->fetch_assoc()) {
        $lecturerIDs[] = $row['lec_id'];
    }

    // Loop through lecturers and reassign timeslots
    foreach ($lecturerIDs as $lecturerID) {
        // Get the current date
        $currentDate = date("Y-m-d");

        // Generate random start time between 8 am and 3 pm
        $startTime = date("H:i:s", rand(strtotime("08:00:00"), strtotime("15:00:00")));

        // Calculate end time as start time plus two hours
        $endTime = date("H:i:s", strtotime($startTime) + (2 * 60 * 60));

        // Check for clashes with existing timeslots
        $sqlCheckClashes = "SELECT * FROM timetable WHERE lec_id = ? AND day = ? AND ((start_time >= ? AND start_time < ?) OR (end_time > ? AND end_time <= ?))";
        $stmtCheckClashes = $mysqli->prepare($sqlCheckClashes);
        $stmtCheckClashes->bind_param('ssssss', $lecturerID, $currentDate, $startTime, $endTime, $startTime, $endTime);
        $stmtCheckClashes->execute();
        $resultClashes = $stmtCheckClashes->get_result();
        $clashesExist = ($resultClashes->num_rows > 0);
        $stmtCheckClashes->close();

        // If clashes exist, regenerate times
        while ($clashesExist) {
            $startTime = date("H:i:s", rand(strtotime("08:00:00"), strtotime("15:00:00")));
            $endTime = date("H:i:s", strtotime($startTime) + (2 * 60 * 60));

            $stmtCheckClashes = $mysqli->prepare($sqlCheckClashes);
            $stmtCheckClashes->bind_param('ssssss', $lecturerID, $currentDate, $startTime, $endTime, $startTime, $endTime);
            $stmtCheckClashes->execute();
            $resultClashes = $stmtCheckClashes->get_result();
            $clashesExist = ($resultClashes->num_rows > 0);
            $stmtCheckClashes->close();
        }

        // Update timeslots for the lecturer
        $sqlUpdateTimeslots = "UPDATE timetable SET start_time = ?, end_time = ? WHERE lec_id = ? AND day = ?";
        $stmtUpdateTimeslots = $mysqli->prepare($sqlUpdateTimeslots);
        $stmtUpdateTimeslots->bind_param('ssss', $startTime, $endTime, $lecturerID, $currentDate);
        $stmtUpdateTimeslots->execute();
        $stmtUpdateTimeslots->close();
    }

    // Refresh the page after reassignment
    header("refresh:1;url=editsubject.php");
    echo "<script>alert('Timeslots reassigned successfully.')</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Timeslots</title>
</head>
<body>
    <h2>Edit Timeslots</h2>
    <table id="myTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Timetable ID</th>
                <th>Lecturer ID</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Day</th>
                <th>Class Type</th>
                <th>Sub ID</th>
                <th>Venue ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <form method="post" action="editsubject.php">
        <?php
            $sqlTimetable = "SELECT * FROM timetable";
            $resultTimetable = $mysqli->query($sqlTimetable);
            $no = 1;
            while($row = $resultTimetable->fetch_assoc()) {
                $timetableID = $row["timetable_id"];
                $lecID = $row["lec_id"];
                $startTime = $row["start_time"];
                $endTime = $row["end_time"];
                $day = $row["day"];
                $classType = $row["classtype"];
                $subID = $row["subID"];
                $venueID = $row["venueID"];
        ?>
        
        <tr>
            <td><?php echo $no ?></td>
            <input type="hidden" name="timetableID[]" value="<?php echo $timetableID ?>">
            <td><input type="text" name="newTimetableID[]" value="<?php echo $timetableID ?>"></td>
            <td><input type="text" name="lecID[]" value="<?php echo $lecID ?>"></td>
            <td><input type="text" name="startTime[]" value="<?php echo $startTime ?>"></td>
            <td><input type="text" name="endTime[]" value="<?php echo $endTime ?>"></td>
            <td><input type="text" name="day[]" value="<?php echo $day ?>"></td>
            <td><input type="text" name="classType[]" value="<?php echo $classType ?>"></td>
            <td><input type="text" name="subID[]" value="<?php echo $subID ?>"></td>
            <td><input type="text" name="venueID[]" value="<?php echo $venueID ?>"></td>
            <td>
                <button type="submit" name="delete" value="<?php echo $timetableID ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
            </td>
        </tr>
        <?php
            $no++;
        }
        ?>
        </form>
    </table>
    <br>
    <button name="savedata" class="button">Save</button>
    </form>
    <form method="post" action="timetable.php">
        <button type="submit">Back to timetable</button>
    </form>
    <form method="post" name="reassign" action="edittimeslot.php">
        <button type="submit" name="reassign" class="button">Reassign Timeslots</button>
    </form>
</body>
</html>
