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
        <label class="subID">Lecturer Name:</label>
        <input type="text" class="subIDtext" placeholder="John Doe" name="lecname"><br>

        <label class="subname">Email:</label>
        <input type="email" class="subnametext" 
        placeholder="abcd@example.com" name="email"><br>
        <input type="submit" class="submit">
    </form>
    </div>
</body>
</html>