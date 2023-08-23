<html>
<?php
    require_once('config.php');
?>

<h1>Insert Example</h1>

<form method="post" action="insertdbtimeslot.php">
    subject code:  <select name="subID">
        <?php
            $query = "SELECT `subID`, `subname` FROM `subject`";
            $result = $mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["subID"] . "'>" . $row["subname"] . "</option>";
        ?>
    </select><br>
    lecturer id:  
    <select name="lecID">
        <?php
            $query = "SELECT `lec_id`, `lecname` FROM `lecturer`";
            $result = $mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["lec_id"] . "'>" . $row["lecname"] . "</option>";
            }
        ?>
    </select><br>
    start time: <input type="text" name="starttime"><br>
    end time: <input type="text" name="endtime"><br>
    day: <input type="text" name="day"><br>
    class type:  
    <select name="type">
        <?php
            $query = "SELECT DISTINCT `classtype` FROM `timetable`"; // Use DISTINCT keyword here
            $result = $mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["classtype"] . "'>" . $row["classtype"] . "</option>";
            }
        ?>
    </select><br>
    <input type="submit">
</form>

</html>