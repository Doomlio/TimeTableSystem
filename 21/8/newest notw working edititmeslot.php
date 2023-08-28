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
            $classtype = $_POST['classtype'][$key];
            $subID = $_POST['subID'][$key];
            $venueID = $_POST['venueID'][$key];
            $hours = $_POST['hours'][$key]; // Get the updated class hours

            // Check for clashes with existing timeslots
            $sqlCheckClashes = "SELECT * FROM timetable WHERE lec_id = ? AND day = ? AND ((start_time >= ? AND start_time < ?) OR (end_time > ? AND end_time <= ?) OR (start_time <= ? AND end_time >= ?))";
            $stmtCheckClashes = $mysqli->prepare($sqlCheckClashes);
            $stmtCheckClashes->bind_param('ssssssss', $lecID, $day, $startTime, $endTime, $startTime, $endTime, $startTime, $endTime);
            $stmtCheckClashes->execute();
            $resultClashes = $stmtCheckClashes->get_result();
            $clashesExist = ($resultClashes->num_rows > 0);
            $stmtCheckClashes->close();

            // Prepare and execute the SQL query to update the timetable data
            $sqlUpdateTimetable = "UPDATE timetable SET  lec_id=?, start_time=?, 
            end_time=?, day=?, classtype=?, subID=?, venueID=?, hours=? WHERE timetable_id=?";
        $stmtUpdateTimetable = $mysqli->prepare($sqlUpdateTimetable);
        $stmtUpdateTimetable->bind_param('ssssssssss', $newTimetableID, $lecID, $startTime, $endTime, 
        $day, $classtype, $subID, $venueID, $hours, $timetableID);
        $stmtUpdateTimetable->execute();
        $stmtUpdateTimetable->close();
        }
    }

    if (isset($_POST["reassign"])) {
        // Get distinct lecturer IDs
        $sqlDistinctLecturers = "SELECT DISTINCT lec_id FROM timetable";
        $resultDistinctLecturers = $mysqli->query($sqlDistinctLecturers);
        $lecturerIDs = [];
    
        while ($row = $resultDistinctLecturers->fetch_assoc()) {
            $lecturerIDs[] = $row['lec_id'];
        }
    
        // Function to reassign timeslots for a specific lecturer
        function reassignTimeslots($lecID) {
            global $mysqli;
    
            $sqlExistingTimeslots = "SELECT * FROM timetable WHERE lec_id = ?";
            $stmtExistingTimeslots = $mysqli->prepare($sqlExistingTimeslots);
            $stmtExistingTimeslots->bind_param('s', $lecID);
            $stmtExistingTimeslots->execute();
            $resultExistingTimeslots = $stmtExistingTimeslots->get_result();
    
            $newTimeslots = [];
    
            while ($row = $resultExistingTimeslots->fetch_assoc()) {
                // Get the class duration (hours) from the database
                $classDuration = $row['hours'];
    
                // Generate a random hour between 8 and 15 for the start time
                $randomHour = rand(8, 15);
    
                // Calculate end time based on class duration
                $newStartTime = sprintf("%02d:%02d:%02d", $randomHour, 0, 0);
                $newEndTime = date("H:i:s", strtotime($newStartTime) + ($classDuration * 60 * 60));
    
                // Check for clashes with existing timeslots
                $clashExists = false;
    
                foreach ($newTimeslots as $existingTimeslot) {
                    if (($newStartTime >= $existingTimeslot['start_time'] && $newStartTime < $existingTimeslot['end_time']) ||
                        ($newEndTime > $existingTimeslot['start_time'] && $newEndTime <= $existingTimeslot['end_time'])) {
                        $clashExists = true;
                        break;
                    }
                }
    
                if (!$clashExists) {
                    $newTimeslots[] = [
                        'start_time' => $newStartTime,
                        'end_time' => $newEndTime
                    ];
    
                    // Update timeslot in the database
                    $sqlUpdateTimeslot = "UPDATE timetable SET start_time = ?, end_time = ? WHERE timetable_id = ?";
                    $stmtUpdateTimeslot = $mysqli->prepare($sqlUpdateTimeslot);
                    $stmtUpdateTimeslot->bind_param('sss', $newStartTime, $newEndTime, $row['timetable_id']);
                    $stmtUpdateTimeslot->execute();
                    $stmtUpdateTimeslot->close();
                }
            }
        }
    
        // Loop through lecturers and reassign timeslots
        foreach ($lecturerIDs as $lecturerID) {
            reassignTimeslots($lecturerID);
        }
    
        // Refresh the page after reassignment
        header("refresh:1;url=edittimeslot.php");
        echo "<script>alert('Timeslots reassigned successfully.')</script>";
    }
    

    ?>
 <!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <title>Manage Timeslots</title>
    <script>
        function updateHoursAndEndTime(index) {
            var startTime = document.getElementsByName('startTime[]')[index].value;
            var endTime = document.getElementsByName('endTime[]')[index].value;
            var hoursInput = document.getElementsByName('hours[]')[index];

            var startTimestamp = new Date('1970-01-01 ' + startTime).getTime();
            var endTimestamp = new Date('1970-01-01 ' + endTime).getTime();

            var diffInMillisecs = Math.abs(endTimestamp - startTimestamp);
            var hours = Math.floor(diffInMillisecs / (1000 * 60 * 60));

            hoursInput.value = hours;
        }

        // Attach event listeners to start time and end time inputs
        var startTimeInputs = document.getElementsByName('startTime[]');
        var endTimeInputs = document.getElementsByName('endTime[]');

        for (var i = 0; i < startTimeInputs.length; i++) {
            startTimeInputs[i].addEventListener('input', function () {
                updateHoursAndEndTime(i);
            });

            endTimeInputs[i].addEventListener('input', function () {
                updateHoursAndEndTime(i);
            });
        }
    </script>
</head>
<h2>Edit Timeslots</h2>
    <form method="post" action="edittimeslot.php">
        <table id="myTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Timetable ID</th>
                    <th>Lecturer ID</th>
                    <th>Lecturer name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Day</th>
                    <th>Class Type</th>
                    <th>Sub ID</th>
                    <th>Venue ID</th>
                    <th>Class Status</th>
                    <th>hours</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlTimetable = "SELECT timetable.*, lecturer.lecname, lecturer.maxhours 
                FROM timetable
                INNER JOIN lecturer ON timetable.lec_id = lecturer.lec_id
                ORDER BY timetable.lec_id, FIELD(LOWER(day), 'monday', 'tuesday', 'wednesday', 'thursday', 'friday');";
                $resultTimetable = $mysqli->query($sqlTimetable);
                $no = 1;
                while ($row = $resultTimetable->fetch_assoc()) {
                    $timetableID = $row["timetable_id"];
                    $lecID = $row["lec_id"];
                    $lecName = $row["lecname"]; // Added lecturer name
                    $startTime = $row["start_time"];
                    $endTime = $row["end_time"];
                    $day = $row["day"];
                    $classtype = $row["classtype"];
                    $subID = $row["subID"];
                    $venueID = $row["venueID"];
                    $cstatus = $row["cstatus"];
                    $totalHoursOfClass = 0;
                ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td><?php echo $timetable_id ?></td>
                    <td><input type="hidden" name="timetableID[]" value="<?php echo $timetableID ?>"></td>
                        <td><?php echo $lecID ?></td>
                        <td><?php echo $lecName ?></td>
                    <td><input type="text" name="startTime[]" value="<?php echo $startTime ?>"></td>
                    <td><input type="text" name="endTime[]" value="<?php echo $endTime ?>"></td>
                    <td>
                        <select name="day[]">
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                        </select>
                    </td>
                    <td>
                        <select name="classtype[]">
                            <option value="lecture">Lecture</option>
                            <option value="lab">Lab</option>
                        </select>
                    </td>
                    <td><input type="text" name="subID[]" value="<?php echo $subID ?>"></td>
                    <td><input type="text" name="venueID[]" value="<?php echo $venueID ?>"></td>
                    <td>
                        <select name="cstatus[]">
                            <option value="active">Active</option>
                            <option value="replacement">Replacement</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </td>
                    <td><input type="number" name="hours[]" value="<?php echo $row['hours'] ?>" min="1" max="24"></td>
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
            </tbody>
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
