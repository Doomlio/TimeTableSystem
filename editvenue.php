<?php
// Database connection
include ('config.php');

$showAlert = false; // Initialize showAlert to false

// Delete code
if (isset($_POST["delete"])) {
    $deleteVenueID = $_POST["delete"];
    $sqlDeleteRecord = $mysqli->prepare("DELETE FROM venue WHERE venueid=?");
    $sqlDeleteRecord->bind_param('s', $deleteVenueID);
    $sqlDeleteRecord->execute();
    $sqlDeleteRecord->close();

    echo "<script>alert('Record deleted successfully.')</script>";
    header("refresh:1;url=editvenue.php");
}

// Save the data
if (isset($_POST["savedata"])) {
    foreach ($_POST['venueid'] as $key => $venueid) {
        $newVenueID = $_POST['newVenueID'][$key];
        $venuetype = $_POST['venuetype'][$key];

        // Prepare and execute the SQL query to update the venue data
        $sqlUpdateVenue = "UPDATE venue SET venueid=?, venuetype=? WHERE venueid=?";
        $stmtUpdateVenue = $mysqli->prepare($sqlUpdateVenue);
        $stmtUpdateVenue->bind_param('sss', $newVenueID, $venuetype,  $venueid);
        $stmtUpdateVenue->execute();
        $stmtUpdateVenue->close();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <title>Manage Venue Data</title>
</head>
<body>
    <h2>Edit Venue Record</h2>
    <p></p>

    <table id="myTable">
        <thead>
            <tr class="header">
                <th class="">#</th>
                <th class="">Venue ID</th>
                <th class="">Venue Type</th>
                <th class="">Actions</th>
            </tr>
        </thead>
        <form method="post" name="updatevenue" action="editvenue.php">
        <?php
            $sqlVenue = "SELECT * FROM venue";
            $resultVenue = $mysqli->query($sqlVenue);
            $no = 1;
            while($row = $resultVenue->fetch_assoc()) {
                $venueid = $row["venueid"];
                $venuetype = $row["venuetype"];
        ?>
        <tr>
            <td class="" style="text-align:center"><?php echo $no ?></td>
            <input type="hidden" name="venueid[]" value="<?php echo $venueid ?>">
            <td class=""><input type="text" name="newVenueID[]" value="<?php echo $venueid ?>"></td>
            <td class=""><input type="text" name="venuetype[]" value="<?php echo $venuetype ?>"></td>
            <td class="">
                <button type="submit" name="delete" value="<?php echo $venueid ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
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
