<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <title>Insert New Venue</title>
</head>
<body>
    <h1>Add New Venue</h1>
    <div class="formbox3">
    <form method="post" action="insertdbvenue.php">
        <label class="subID">Venue ID:</label>
        <input type="text" class="subIDtext" placeholder="ABCD" name="venueID"><br>

        <label class="subname">type of class:</label>
        <input type="text" class="subnametext" 
        placeholder="lab/lecture" name="type"><br>
        <input type="submit" class="submit">
    </form>
    <a class="back2" href="/admin/view/viewvenue.php" >Back</a>
    </div>
</body>
</html>