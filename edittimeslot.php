<?php
session_start();
require_once("config.php");
$showAlert = false; 
if (!isset($_SESSION["lec_id"]) || !isset($_SESSION["name"])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit;
}

$lec_id = $_SESSION["lec_id"];
$lecname = $_SESSION["name"];



// Delete timeslot record
if (isset($_POST["delete"])) {
    $deleteTimetableID = $_POST["delete"];
    $sqlDeleteRecord = $mysqli->prepare("DELETE FROM timetable WHERE timetable_id = ?");
    $sqlDeleteRecord->bind_param('s', $deleteTimetableID);
    $sqlDeleteRecord->execute();
    $sqlDeleteRecord->close();
    $showAlert = true;
}

// Update timeslot records
if (isset($_POST["savedata"])) {
    $timetableIDs = $_POST['timetableID'];
    $newTimetableIDs = $_POST['newTimetableID'];
    $startTimes = $_POST['startTime'];
    $endTimes = $_POST['endTime'];
    $days = $_POST['day'];
    $classTypes = $_POST['classType'];
    $subIDs = $_POST['subID'];
    $venueIDs = $_POST['venueID'];

    foreach ($timetableIDs as $key => $timetableID) {
        $newTimetableID = $newTimetableIDs[$key];
        $startTime = $startTimes[$key];
        $endTime = $endTimes[$key];
        $day = $days[$key];
        $classType = $classTypes[$key];
        $subID = $subIDs[$key];
        $venueID = $venueIDs[$key];

        // Check for clashes with existing timeslots
        $sqlCheckClashes = "SELECT * FROM timetable WHERE lec_id = ? AND day = ? 
                            AND ((start_time >= ? AND start_time < ?) OR (end_time > ? AND end_time <= ?)) 
                            AND timetable_id != ?";
        $stmtCheckClashes = $mysqli->prepare($sqlCheckClashes);
        $stmtCheckClashes->bind_param('sssssss', $lec_id, $day, $startTime, $endTime, $startTime, $endTime, $timetableID);
        $stmtCheckClashes->execute();
        $resultClashes = $stmtCheckClashes->get_result();
        $clashesExist = ($resultClashes->num_rows > 0);
        $stmtCheckClashes->close();

        if (!$clashesExist) {
            $sqlUpdateTimetable = "UPDATE timetable SET timetable_id = ?, lec_id = ?, start_time = ?, 
                                   end_time = ?, day = ?, classtype = ?, subID = ?, venueID = ? 
                                   WHERE timetable_id = ?";
            $stmtUpdateTimetable = $mysqli->prepare($sqlUpdateTimetable);
            $stmtUpdateTimetable->bind_param('sssssssss', $newTimetableID, $lec_id, $startTime, 
                                             $endTime, $day, $classType, $subID, $venueID, $timetableID);
            $stmtUpdateTimetable->execute();
            $stmtUpdateTimetable->close();
            $showAlert = true;
        }
    }
}

// Fetch replacement class timeslots
$sqlTimetable = "SELECT * FROM timetable WHERE lec_id = ? AND cstatus = 'replacement'";
$stmt = $mysqli->prepare($sqlTimetable);
$stmt->bind_param("s", $lec_id);
$stmt->execute();
$resultTimetable = $stmt->get_result();
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Replacement Timeslots</title>
</head>
<body>
    <h2>Edit Replacement Timeslots</h2>
    <div class="alert">
        <?php if ($showAlert) : ?>
            <p>Changes have been saved successfully.</p>
        <?php endif; ?>
    </div>
    <form method="post">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Day</th>
                    <th>Class Type</th>
                    <th>Sub ID</th>
                    <th>Venue ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = $resultTimetable->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><input type="text" name="startTime[]" value="<?php echo $row['start_time']; ?>"></td>
                        <td><input type="text" name="endTime[]" value="<?php echo $row['end_time']; ?>"></td>
                        <td><input type="text" name="day[]" value="<?php echo $row['day']; ?>"></td>
                        <td><?php echo $row['classtype']; ?></td>
                        <td><input type="text" name="subID[]" value="<?php echo $row['subID']; ?>"></td>
                        <td><input type="text" name="venueID[]" value="<?php echo $row['venueID']; ?>"></td>
                        <td>
                            <button type="submit" name="delete" value="<?php echo $row['timetable_id']; ?>" 
                                    onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" name="savedata">Save Changes</button>
    </form>
    <form method="post" action="timetable.php">
        <button type="submit">Back to Timetable</button>
    </form>
</body>
</html>
