<html>
<?php
    require_once('config.php');
?>
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <title>Insert Timeslot</title>
</head>
<body>

<h1>Insert Timetableslot</h1>
<div class="formboxsub">
<form method="post" action="insertdbtimeslot.php">
<label class="subID">Subject ID:</label>
 <select name="subID" class="subidsel">
        <?php
            $query = "SELECT `subID`, `subname` FROM `subject`";
            $result = $mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["subID"] . "'>" . $row["subname"] . "</option>";
            }
        ?>
    </select><br>
    <label class="lecID">Lecturer id:  </label>
    <select class="lecIDsel" name="lecID">
        <?php
            $query = "SELECT `lec_id`, `lecname` FROM `lecturer`";
            $result = $mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["lec_id"] . "'>" . $row["lecname"] . "</option>";
            }
        ?>
    </select><br>
    <label class="sttime">Start time:</label> 
    <input type="time" class="sttimesel" name="starttime"><br>

    <label class="etime">End time:</label> 
    <input type="time" class="etimesel" name="endtime"><br>
        <label class="day">Day:</label>
         <select name="day" class="daysel">
        <option value="monday">monday</option>
        <option value="tuesday">tuesday</option>
        <option value="wednesday">wednesday</option>
        <option value="thursday">thursday</option>
        <option value="friday">friday</option>
        </select><br>
    <label class="ctype">Class type:</label>  
    <select name="type" class="csell">
        <?php
            $query = "SELECT DISTINCT `classtype` FROM `timetable`"; // Use DISTINCT keyword here
            $result = $mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["classtype"] . "'>" . $row["classtype"] . "</option>";
            }
        ?>
    </select>
    <input type="submit"class ="submit"name="submit" value="Submit">
        
<a class="link-button"

 href="viewsubject.php" class="back2">Back</a>
    <input type="submit" class="submit">

</form>

</div>
</body>
</html>