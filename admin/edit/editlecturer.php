<?php
// Database connection
include ('../../config.php');

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
    header("refresh:1;url=/admin/edit/editlecturer.php");
}

// Save the data
if (isset($_POST["savedata"])) {
    foreach ($_POST['lec_id'] as $key => $lec_id) {
        $newLecID = $_POST['newLecID'][$key];
        $lecname = $_POST['lecname'][$key];
        $email = $_POST['lecemail'][$key];
        $password = $_POST['lecpassword'][$key];
        $maxhours = $_POST['maxhours'][$key];

        // Prepare and execute the SQL query to update the lecturer data
        $sqlUpdateLecturer = "UPDATE lecturer SET lec_id=?, lecname=?, lecemail=?, lecpassword=?, maxhours=? WHERE lec_id=?";
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
<link rel="stylesheet" href="/asset/timetable.css">
    <title>Manage Lecturer Data</title>
</head>
<body>
    <h2>Edit Lecturer Record</h2>
    <p></p>

    <table class ="custom-table" id="myTable">
        <thead>
            <tr class="header2">
                <th class="">#</th>
                <th class="">Lecturer ID</th>
                <th class="">Lecturer Name</th>
                <th class="">Email</th>
                <th class="">Password</th>
                <th class="">Max Hours</th>
                <th class="">Actions</th>
            </tr>
        </thead>
        <form method="post" name="updaterenewal" action="/admin/edit/editlecturer.php">
        <?php
            $sqlrenewal = "SELECT * FROM lecturer";
            $resultRenewal = $mysqli->query($sqlrenewal);
            $no = 1;
            while($row = $resultRenewal->fetch_assoc()) {
                $lec_id = $row["lec_id"];
                $lecname = $row["lecname"];
                $email = $row["lecemail"];
                $password = $row["lecpassword"];
                $maxhours = $row["maxhours"];
        ?>
        <tr>
            <td class="" style="text-align:center"><?php echo $no ?></td>
            <input type="hidden" name="lec_id[]" value="<?php echo $lec_id ?>">
            <td class=""><input type="text" name="newLecID[]" value="<?php echo $lec_id ?>"></td>
            <td class=""><input type="text" name="lecname[]" value="<?php echo $lecname ?>"></td>
            <td class=""><input type="text" name="lecemail[]" value="<?php echo $email ?>"></td>
            <td class=""><input type="text" name="lecpassword[]" value="<?php echo $password ?>"></td>
            <td class=""><input type="text" name="maxhours[]" value="<?php echo $maxhours ?>"></td>
            <td class="">
                <button type="submit" class="back" name="delete" value="<?php echo $lec_id ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
            </td>
        </tr>
        <?php
            $no++;
        }
        ?>
        </table>
        <br>
        <button name="savedata" class="link-button">Save</button>
    </form>
    <button class="link-button" onclick="window.location.href='/admin/view/timetable.php';">Back To Timetable</button>
   

         
    
</body>
</html>
