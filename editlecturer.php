<?php
// Database connection
include ('config.php');

$myupdate = 0; // Initialize $myupdate before the loop
$showAlert = false; // Initialize showAlert to false

// Delete code
if (isset($_POST["delete"])) {
    $deleteLecID = $_POST["delete"];
    $sqlDeleteRecord = $mysqli->prepare("DELETE FROM lecturer WHERE lec_id=?");
    $sqlDeleteRecord->bind_param('s', $deleteLecID);
    $sqlDeleteRecord->execute();
    $sqlDeleteRecord->close();

    echo "<script>alert('Record deleted successfully.')</script>";
    header("refresh:1;url=editlecturer.php");
}

// Save the data
if (isset($_POST["savedata"])) {
    foreach ($_POST['lec_id'] as $key => $lec_id) {
        $newLecID = $_POST['newLecID'][$key];
        $lecname = $_POST['lecname'][$key];
        $email = $_POST['email'][$key];
        $password = $_POST['password'][$key];
        $maxhours = $_POST['maxhours'][$key];

        // Prepare and execute the SQL query to update the lecturer data
        $sqlUpdateLecturer = "UPDATE lecturer SET lec_id=?, lecname=?, email=?, password=?, maxhours=? WHERE lec_id=?";
        $stmtUpdateLecturer = $mysqli->prepare($sqlUpdateLecturer);
        $stmtUpdateLecturer->bind_param('ssssss', $newLecID, $lecname, $email, $password, $maxhours, $lec_id);
        $stmtUpdateLecturer->execute();
        $stmtUpdateLecturer->close();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Lecturer Data</title>
</head>
<body>
    <h2>Edit Lecturer Record</h2>
    <p></p>

    <table id="myTable">
        <thead>
            <tr class="header">
                <th class="">#</th>
                <th class="">Lecturer ID</th>
                <th class="">Lecturer Name</th>
                <th class="">Email</th>
                <th class="">Password</th>
                <th class="">Max Hours</th>
                <th class="">Actions</th>
            </tr>
        </thead>
        <form method="post" name="updaterenewal" action="editlecturer.php">
        <?php
            $sqlrenewal = "SELECT * FROM lecturer";
            $resultRenewal = $mysqli->query($sqlrenewal);
            $no = 1;
            while($row = $resultRenewal->fetch_assoc()) {
                $lec_id = $row["lec_id"];
                $lecname = $row["lecname"];
                $email = $row["email"];
                $password = $row["password"];
                $maxhours = $row["maxhours"];
        ?>
        <tr>
            <td class="" style="text-align:center"><?php echo $no ?></td>
            <input type="hidden" name="lec_id[]" value="<?php echo $lec_id ?>">
            <td class=""><input type="text" name="newLecID[]" value="<?php echo $lec_id ?>"></td>
            <td class=""><input type="text" name="lecname[]" value="<?php echo $lecname ?>"></td>
            <td class=""><input type="text" name="email[]" value="<?php echo $email ?>"></td>
            <td class=""><input type="text" name="password[]" value="<?php echo $password ?>"></td>
            <td class=""><input type="text" name="maxhours[]" value="<?php echo $maxhours ?>"></td>
            <td class="">
                <button type="submit" name="delete" value="<?php echo $lec_id ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
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
   

         
    
</body>
</html>
