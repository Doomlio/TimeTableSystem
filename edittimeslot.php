<?php 
// Database connection
include ('config.php');

$myupdate = 0; // Initialize $myupdate before the loop
$showAlert = false; // Initialize showAlert to false
$reassignedSubjects = []; // Initialize reassignedSubjects array

// Delete code
if (isset($_POST["delete"])) {
    $deleteSubID = $_POST["delete"];
    $sqlDeleteRecord = $mysqli->prepare("DELETE FROM subject WHERE subID=?");
    $sqlDeleteRecord->bind_param('s', $deleteSubID);
    $sqlDeleteRecord->execute();
    $sqlDeleteRecord->close();
    
    echo "<script>alert('Record deleted successfully.')</script>";
    header("refresh:1;url=editsubject.php");
}

// Save the data and reassign constraint violation
if (isset($_POST["savedata"])) {
    foreach ($_POST['subID'] as $key => $subID) {
        $newSubID = $_POST['newSubID'][$key];
        $subname = $_POST['subname'][$key];
        $qualification = $_POST['qualification'][$key];
        $sem = $_POST['sem'][$key];
        $lecid = $_POST['lecid'][$key];
        $course = $_POST['course'][$key];
        
        // Prepare and execute the SQL query to update the subject data
        $sqlUpdateSubject = "UPDATE subject SET subID=?, subname=?, qualification=?, sem=?, lecid=?, course=? WHERE subID=?";
        $stmtUpdateSubject = $mysqli->prepare($sqlUpdateSubject);
        $stmtUpdateSubject->bind_param('sssssss', $newSubID, $subname, $qualification, $sem, $lecid, $course, $subID);
        $stmtUpdateSubject->execute();
        $stmtUpdateSubject->close();
        
        
        // Identify lecturers with more than 3 subjects
        $sqlExceedingSubjects = "SELECT lecid FROM subject GROUP BY lecid HAVING COUNT(subID) > 3";
        $resultExceedingSubjects = $mysqli->query($sqlExceedingSubjects);
        $lecturerExceedingSubjects = [];

        while ($row = $resultExceedingSubjects->fetch_assoc()) {
            $lecturerExceedingSubjects[] = $row['lecid'];
        }

        if (!empty($lecturerExceedingSubjects)) {
            // Reassign subjects from lecturers with more than 3 subjects
            foreach ($lecturerExceedingSubjects as $exceedingLecturer) {
                $sqlReassignSubject = "
                    UPDATE subject
                    SET lecid = (
                        SELECT lecid
                        FROM subject
                        GROUP BY lecid
                        HAVING COUNT(subID) <= 2
                        ORDER BY COUNT(subID) ASC
                        LIMIT 1
                    )
                    WHERE lecid = '$exceedingLecturer'
                    LIMIT 1";

                $mysqli->query($sqlReassignSubject);
                $reassignedSubjects[$subID] = $exceedingLecturer;
            }
        }
    }
    
    // Check lecturer's subjects count
    $sqlLecturerSubjects = "SELECT lecid, COUNT(subID) AS subjectCount FROM subject GROUP BY lecid";
    $resultLecturerSubjects = $mysqli->query($sqlLecturerSubjects);
    $lecturerSubjects = array();

    while ($row = $resultLecturerSubjects->fetch_assoc()) {
        $lecturerSubjects[$row['lecid']] = $row['subjectCount'];
    }
    
    // Set showAlert if any lecturer exceeds the subject count
    foreach ($lecturerSubjects as $lecturerID => $subjectCount) {
        if ($subjectCount > 3) {
            $showAlert = true;
            break;
        }
    }
}


//reassign lec
if (isset($_POST["reassign"])) {
    // Reset all lecturer assignments
    $sqlResetAssignments = "UPDATE subject SET lecid = NULL";
    $mysqli->query($sqlResetAssignments);

    // Get all subject IDs for reassignment
    $sqlAllSubjects = "SELECT subID FROM subject";
    $resultAllSubjects = $mysqli->query($sqlAllSubjects);
    $subjectsForReassignment = [];

    while ($row = $resultAllSubjects->fetch_assoc()) {
        $subjectsForReassignment[] = $row['subID'];
    }

    // Loop through subjects and reassign them
    foreach ($subjectsForReassignment as $subjectID) {
        // Select a random lecturer
        $sqlSelectRandomLecturer = "SELECT lec_id FROM lecturer ORDER BY RAND() LIMIT 1";
        $resultRandomLecturer = $mysqli->query($sqlSelectRandomLecturer);
        $rowRandomLecturer = $resultRandomLecturer->fetch_assoc();
        $randomLecturerID = $rowRandomLecturer['lec_id'];

        // Update subject with the random lecturer
        $sqlReassignToLecturer = "UPDATE subject SET lecid = ? WHERE subID = ?";
        $stmtReassignToLecturer = $mysqli->prepare($sqlReassignToLecturer);
        $stmtReassignToLecturer->bind_param('ss', $randomLecturerID, $subjectID);
        $stmtReassignToLecturer->execute();

        // Add debugging output
        echo "Subject ID: $subjectID - Rows affected: " . $stmtReassignToLecturer->affected_rows . "<br>";

        if ($stmtReassignToLecturer->affected_rows > 0) {
            echo "Lecturer reassigned for subject $subjectID.<br>";
        } else {
            echo "Lecturer not reassigned for subject $subjectID.<br>";
        }

        $stmtReassignToLecturer->close();
    }

    // Refresh the page after reassignment
    header("refresh:1;url=editsubject.php");
    echo "<script>alert('Lecturer reassigned successfully.')</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage subject data</title>
</head>
<body>
    <h2>Edit Timeslots</h2>
    <p></p>

    <table id="myTable">
    <thead>
            <tr>
                <th>#</th>
                <th>Timetable ID</th>
                <th>Subject Name</th>
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
                $subjectName = $row["subject_name"];
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
    <td><input type="text" name="subjectName[]" value="<?php echo $subjectName ?>"></td>
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
    <form method="post" name="updaterenewal" action="editsubject.php">
    <button type="submit" name="reassign" class="button">Reassign Lecturers </button>
</form>

    <?php if ($showAlert) : ?>
        <script>
            alert("Each lecturer may only have 3 subjects this semester");
        </script>
         
    <?php endif; ?>
    <?php if (!empty($reassignedSubjects)) : ?>
    <h3>Reassigned Timeslot</h3>
    <ul>
        <?php foreach ($reassignedSubjects as $subjectID => $lecturerID) : ?>
            <?php
                $sqlGetSubjectName = "SELECT subname FROM subject WHERE subID = ?";
                $stmt = $mysqli->prepare($sqlGetSubjectName);
                $stmt->bind_param('s', $subjectID);
                $stmt->execute();
                $stmt->bind_result($subjectName);
                $stmt->fetch();
                $stmt->close();

                $sqlGetLecturerName = "SELECT lecname FROM lecturer WHERE lec_id = ?";
                $stmt = $mysqli->prepare($sqlGetLecturerName);
                $stmt->bind_param('s', $lecturerID);
                $stmt->execute();
                $stmt->bind_result($lecturerName);
                $stmt->fetch();
                $stmt->close();
            ?>
            <li>Subject "<?php echo $subjectName ?>" reassigned to Lecturer "<?php echo $lecturerName ?>"</li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>
