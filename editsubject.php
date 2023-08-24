<?php 
// Database connection
include ('config.php');

$myupdate = 0; // Initialize $myupdate before the loop
$showAlert = false; // Initialize showAlert to false

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

// Save the data
if (isset($_POST["savedata"])) {		
    foreach ($_POST['subID'] as $key => $subID) {
        $newSubID = $_POST['newSubID'][$key];
        $subname = $_POST['subname'][$key];
        $qualification = $_POST['qualification'][$key];
        $sem = $_POST['sem'][$key];
        $lecid = $_POST['lecid'][$key];
        $course = $_POST['course'][$key];

        // Check if lecturer already has 3 subjects
        if (isset($lecturerSubjects[$lecid]) && $lecturerSubjects[$lecid] >= 3) {
            $showAlert = true;

            // Reassign subject to another lecturer with less than 3 subjects
            foreach ($lecturerSubjects as $lecturerID => $subjectCount) {
                if ($subjectCount < 3) {
                    $lecid = $lecturerID;
                    $lecturerSubjects[$lecid]++;
                    break;
                }
            }
        }

        // Update table
        $sqlUpdateRecord = $mysqli->prepare("UPDATE subject SET subID=?, subname=?, qualification=?, sem=?, lecid=?, course=? WHERE subID=?");
        $sqlUpdateRecord->bind_param('sssssss', $newSubID, $subname, $qualification, $sem, $lecid, $course, $subID);
        $sqlUpdateRecord->execute();
        $sqlUpdateRecord->close();
        $myupdate = 1;
    } 

    if ($myupdate == 1) { // Refresh
        header("refresh:1;url=editsubject.php");
        echo "<script>alert('Data is successfully saved.')</script>";	
    } 
}

// Check lecturer's subjects count
$sqlLecturerSubjects = "SELECT lecid, COUNT(subID) AS subjectCount FROM subject GROUP BY lecid";
$resultLecturerSubjects = $mysqli->query($sqlLecturerSubjects);
$lecturerSubjects = array();

while ($row = $resultLecturerSubjects->fetch_assoc()) {
    $lecturerSubjects[$row['lecid']] = $row['subjectCount'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage subject data</title>
</head>
<body>
    <h2>Edit Subject Record</h2>
    <p></p>

    <table id="myTable">
        <thead>
            <tr class="header">
                <th class="">#</th>
                <th class="">Subject ID</th>
                <th class="">Subject Name</th>
                <th class="">Qualification</th>
                <th class="">Semester</th>
                <th class="">Lecturer ID</th>
                <th class="">Course</th>
                <th class="">Actions</th>
            </tr>
        </thead>
        <form method="post" name="updaterenewal" action="editsubject.php">
        <?php
            $sqlrenewal = "SELECT * FROM subject";
            $resultRenewal = $mysqli->query($sqlrenewal);
            $no = 1;
            while($row = $resultRenewal->fetch_assoc()) {
                $subID = $row["subID"];
                $subname = $row["subname"];
                $qualification = $row["qualification"];
                $sem = $row["sem"];
                $lecid = $row["lecid"];
                $course = $row["course"];

                // Check if lecturer already has 3 subjects
                if (isset($lecturerSubjects[$lecid]) && $lecturerSubjects[$lecid] >3) {
                    $showAlert = true;
                    
                    // Reassign subject to another lecturer with less than 3 subjects
                    foreach ($lecturerSubjects as $lecturerID => $subjectCount) {
                        if ($subjectCount < 3) {
                            $lecid = $lecturerID;
                            $lecturerSubjects[$lecid]++;
                            break;
                        }
                    }
                }
        ?>
        <tr>
            <td class="" style="text-align:center"><?php echo $no ?></td>
            <input type="hidden" name="subID[]" value="<?php echo $subID ?>">
            <td class=""><input type="text" name="newSubID[]" value="<?php echo $subID ?>"></td>
            <td class=""><input type="text" name="subname[]" value="<?php echo $subname ?>"></td>
            <td class=""><input type="text" name="qualification[]" value="<?php echo $qualification ?>"></td>
            <td class=""><input type="text" name="sem[]" value="<?php echo $sem ?>"></td>
            <td class=""><input type="text" name="lecid[]" value="<?php echo $lecid ?>"></td>
            <td class=""><input type="text" name="course[]" value="<?php echo $course ?>"></td>
            <td class="">
                <button type="submit" name="delete" value="<?php echo $subID ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
            </td>
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

    <?php if ($showAlert) : ?>
        <script>
            alert("Each lecturer may only have 3 subjects this semester");
        </script>
    <?php endif; ?>
</body>
</html>
