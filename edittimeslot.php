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





    if (isset($_POST["submitSave"])) {
        foreach ($_POST['timetable_id'] as $key => $timetable_id) {
            $start_time = $_POST['start_time'][$key];
            $end_time = $_POST['end_time'][$key];
            $day = $_POST['day'][$key];
            $classtype = $_POST['classtype'][$key];
            $subID = $_POST['subID'][$key];
            $venueID = $_POST['venueID'][$key];
            $cstatus = $_POST['cstatus'][$key];
            $hours = $_POST['hours'][$key];
    
            // Calculate total hours of the class if cstatus is "active"
            $totalHoursOfClass = 0;
            if ($cstatus === "active") {
                // Calculate the total hours based on the class duration
                $totalHoursOfClass = $hours;
            }
    
            // Check if the total hours exceed the maximum hours (16 hours)
            if ($totalHoursOfClass > 16) {
                // Fetch the lecturer's name
                $sqlGetLecturerName = "SELECT lecname FROM lecturer WHERE lec_id = ?";
                $stmtGetLecturerName = $mysqli->prepare($sqlGetLecturerName);
                $stmtGetLecturerName->bind_param('s', $lecID);
                $stmtGetLecturerName->execute();
                $resultLecturerName = $stmtGetLecturerName->get_result();
                $lecturerName = ($resultLecturerName->num_rows > 0) ? $resultLecturerName->fetch_assoc()['lecname'] : '';
    
                echo "<script>alert('Lecturer $lecturerName has exceeded the weekly hours limit.');</script>";
            } else {
                // Check for clashes with existing timeslots
                $sqlCheckClashes = "SELECT * FROM timetable WHERE lec_id = ? AND day = ? AND ((start_time >= ? AND start_time < ?) OR (end_time > ? AND end_time <= ?) OR (start_time <= ? AND end_time >= ?))";
                $stmtCheckClashes = $mysqli->prepare($sqlCheckClashes);
                $stmtCheckClashes->bind_param('ssssssss', $lecID, $day, $start_time, $end_time, $start_time, $end_time, $start_time, $end_time);
                $stmtCheckClashes->execute();
                $resultClashes = $stmtCheckClashes->get_result();
                $clashesExist = ($resultClashes->num_rows > 0);
                $stmtCheckClashes->close();
    
                if (!$clashesExist) {
                    // Update record
                    $sqlUpdateRecord = $mysqli->prepare("UPDATE timetable SET start_time=?, end_time=?, day=?, classtype=?, subID=?, venueID=?, cstatus=?, hours=? WHERE timetable_id=?");
                    $sqlUpdateRecord->bind_param('ssssssssi', $start_time, $end_time, $day, $classtype, $subID, $venueID, $cstatus, $hours, $timetable_id);
                    $sqlUpdateRecord->execute();
                    $sqlUpdateRecord->close();
                }
            }
        }

	header("refresh:1;url=edittimeslot.php");
	echo "<script>alert('Data is successfully saved.')</script>";	
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
	<title>Edit Timetable</title>

   
    <script>
        function updateHoursAndEndTime(index) {
            var startTime = document.getElementsByName('start_time[]')[index].value;
            var endTime = document.getElementsByName('end_time[]')[index].value;
            var hoursInput = document.getElementsByName('hours[]')[index];

            if (startTime && endTime) {
                var startTimestamp = new Date('1970-01-01 ' + startTime).getTime();
                var endTimestamp = new Date('1970-01-01 ' + endTime).getTime();

                var diffInMillisecs = Math.abs(endTimestamp - startTimestamp);
                var hours = Math.floor(diffInMillisecs / (1000 * 60 * 60));

                hoursInput.value = hours;
            }
        }

        function updateEndTime(index) {
            var startTime = document.getElementsByName('start_time[]')[index].value;
            var hours = document.getElementsByName('hours[]')[index].value;
            var endTimeInput = document.getElementsByName('end_time[]')[index];

            if (startTime && hours) {
                var startTimestamp = new Date('1970-01-01 ' + startTime).getTime();
                var endTimestamp = startTimestamp + (hours * 60 * 60 * 1000);
                var endDate = new Date(endTimestamp);

                var endHours = String(endDate.getHours()).padStart(2, '0');
                var endMinutes = String(endDate.getMinutes()).padStart(2, '0');

                var endTime = endHours + ':' + endMinutes;
                endTimeInput.value = endTime;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var startTimeInputs = document.getElementsByName('start_time[]');
            var endTimeInputs = document.getElementsByName('end_time[]');
            var hoursInputs = document.getElementsByName('hours[]');

            for (var i = 0; i < startTimeInputs.length; i++) {
                (function(index) {
                    startTimeInputs[index].addEventListener('input', function () {
                        updateHoursAndEndTime(index);
                    });

                    endTimeInputs[index].addEventListener('input', function () {
                        updateHoursAndEndTime(index);
                    });

                    hoursInputs[index].addEventListener('input', function () {
                        updateEndTime(index);
                    });
                })(i); // Pass the current value of 'i' to the IIFE
            }
        });
    </script>


</head>
<body>
    <h2>Edit Timetable</h2>

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
                <th>Hours</th>
                <th>Actions</th>
            </tr>
            <form method="post" name="updateTimetable" action="edittimeslot.php">
            <?php
                $sqlTimetable = "SELECT timetable.*, lecturer.lecname, lecturer.maxhours 
                                FROM timetable
                                INNER JOIN lecturer ON timetable.lec_id = lecturer.lec_id
                                ORDER BY timetable.lec_id, FIELD(LOWER(day), 'monday', 'tuesday', 'wednesday', 'thursday', 'friday');";
                $resultTimetable = $mysqli->query($sqlTimetable);
                $no = 1;
                while ($row = $resultTimetable->fetch_assoc()) {
                    $timetable_id = $row["timetable_id"];
                    $lecID = $row["lec_id"];
                    $lecName = $row["lecname"];
                    $start_time = $row["start_time"];
                    $end_time = $row["end_time"];
                    $day = $row["day"];
                    $classtype = $row["classtype"];
                    $subID = $row["subID"];
                    $venueID = $row["venueID"];
                    $cstatus = $row["cstatus"];
                    $hours = $row["hours"];
                    $totalHoursOfClass = 0;
            ?>
            <tr>
                <td><?php echo $no ?></td>
                <td><?php echo $timetable_id ?></td>
                <input type="hidden" name="timetable_id[]" value="<?php echo $timetable_id ?>">
                <td><?php echo $lecID ?></td>
                <td><?php echo $lecName ?></td>
                <td><input type="time" name="start_time[]" value="<?php echo $start_time ?>"></td>
                <td><input type="time" name="end_time[]" value="<?php echo $end_time ?>"></td>
                <td>
                    <select name="day[]">
                    <?php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                        $selectedDay = $day; // Value from the database

                        // Display the current day first
                        echo "<option value=\"$selectedDay\">$selectedDay</option>";

                        // Display the other days
                        foreach ($days as $dayOption) {
                            if ($dayOption !== $selectedDay) {
                                echo "<option value=\"$dayOption\">$dayOption</option>";
                            }
                        }
                    ?>
                    </select>
                </td>
                <td>
                    <select name="classtype[]">
                    <?php
                        $classTypes = ['lecture', 'lab'];
                        $selectedClassType = $classtype; // Value from the database

                        // Display the current class type first
                        echo "<option value=\"$selectedClassType\">$selectedClassType</option>";

                        // Display the other class types
                        foreach ($classTypes as $classTypeOption) {
                            if ($classTypeOption !== $selectedClassType) {
                                echo "<option value=\"$classTypeOption\">$classTypeOption</option>";
                            }
                        }
                    ?>
                    </select>
                </td>

                <td><input type="text" name="subID[]" value="<?php echo $subID ?>"></td>
                <td>
                    <select name="venueID[]">
                        <?php
                            // Get the class type from the current timetable entry
                            $currentClassType = $classtype;

                            // Fetch venues that match the current class type
                            $sqlMatchingVenues = "SELECT * FROM venue WHERE venuetype = ?";
                            $stmtMatchingVenues = $mysqli->prepare($sqlMatchingVenues);
                            $stmtMatchingVenues->bind_param('s', $currentClassType);
                            $stmtMatchingVenues->execute();
                            $resultMatchingVenues = $stmtMatchingVenues->get_result();

                            while ($rowVenue = $resultMatchingVenues->fetch_assoc()) {
                                $venueIDOption = $rowVenue['venueid'];
                                $selected = ($venueID === $venueIDOption) ? 'selected' : '';

                                echo "<option value=\"$venueIDOption\" $selected>$venueIDOption</option>";
                            }

                            $stmtMatchingVenues->close();
                        ?>
                    </select>
                </td>
                <td>
                    <select name="cstatus[]">
                    <?php
                        $classStatusOptions = ['active', 'replacement', 'cancelled'];
                        $selectedClassStatus = $cstatus; // Value from the database

                        // Display the current class status first
                        echo "<option value=\"$selectedClassStatus\">$selectedClassStatus</option>";

                        // Display the other class status options
                        foreach ($classStatusOptions as $classStatusOption) {
                            if ($classStatusOption !== $selectedClassStatus) {
                                echo "<option value=\"$classStatusOption\">$classStatusOption</option>";
                            }
                        }
                    ?>
                    </select>
                </td>

                <td><input type="number" name="hours[]" value="<?php echo $row['hours'] ?>" min="1" max="24"></td>
                <td>
                    <button type="submit" name="delete" value="<?php echo $timetable_id ?>"
                     onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                </td>
            </tr>
            <?php
                $no++;
                }
            ?>
        </thead>
    </table>
    <br>
    <button name="submitSave" class="button">Save</button>
    </form>
    <form method="post" action="timetable.php">
        <button type="submit">Back to timetable</button>
    </form>
    <form method="post" name="reassign" action="edittimeslot.php">
        <button type="submit" name="reassign" class="button">Reassign Timeslots</button>
    </form>
</body>
</html>