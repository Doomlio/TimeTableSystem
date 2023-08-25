<html>
<?php
    session_start();
    require_once('config.php');
    $lecID = $_SESSION["lec_id"]; // Get lecturer ID from the session
?>

<h1>Insert Example</h1>

<form method="post" action="insertdbtimeslot.php">
    subject code:  
    <select name="subID">
        <?php
            $query = "SELECT `subID`, `subname` FROM `subject` WHERE `lec_id` = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $lecID);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["subID"] . "'>" . $row["subname"] . "</option>";
            }
        ?>
    </select><br>
    start time: <input type="text" name="starttime"><br>
    end time: <input type="text" name="endtime"><br>
    day: <input type="text" name="day"><br>
    class type:  
    <select name="type">
        <option value="practical">Practical</option>
        <option value="lab">Lab</option>
        <option value="lecture">Lecture</option>
    </select><br>
    <input type="submit">
</form>

</html>
