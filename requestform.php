<html>
<h1>Insert Example</h1>

<form method="post" action="insertdbrequest.php">
    Subject ID:
    <select name="lecID">
        <?php
        require_once('config.php');

        $query = "SELECT `subID` FROM `subject`"; 
        $result = $mysqli->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["subID"] . "'>" . $row["subID"] . "</option>";
        }
        ?>
    </select>
    <br>
    <textarea rows="4" placeholder="Enter request here..." cols="50" name="reqtext">
   
    </textarea>
    <br>
    <input type="submit" value="Submit">
</form>

</html>