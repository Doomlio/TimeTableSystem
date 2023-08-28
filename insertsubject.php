<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <title>Insert Example</title>
</head>
<body>
    <h1>Add new subjects</h1>
    <div class="formboxsub2">
    <form method="post" action="insertdbsubject.php">
    <label class="subID">Subject ID:</label>
       <input type="text"  class="subIDtext" placeholder="ECXXXX" name="subID"><br>

       <label class="subname">Subject name:</label>
       <input type="text" class="subnametext" placeholder="Intro to abc" name="subname"><br>

        <label for="qual" class="qual">Qualification:</label>
        <select name="qual" class="qualsel" id="qual">
            <option value="diploma">Diploma</option>
            <option value="degree">Degree</option>
        </select>
        <br>

     
        

        <label for="sem" class="sem">Sem:</label>
        <select name="sem" id="sem" class="sembox">
            <option value="Jan">January</option>
            <option value="May">May</option>
            <option value="Oct">October</option>
</select>
        <br>

        <label for="course" class="course">Course:</label>
        <select name="sem" id="sem" class="coursebox">

            <option value="May">DIT</option>
            <option value="Oct">DCS</option>
        </select>
        <input type="submit"class ="submit"name="submit" value="Submit">
        <a href="viewsubject.php" class="back2">Back</a>
    </form>
    </div>
</body>
</html>