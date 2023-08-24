<!DOCTYPE html>
<html>
<head>
    <title>Insert Example</title>
</head>
<body>
    <h1>Insert Example</h1>

    <form method="post" action="insertdbsubject.php">
        Subject ID: <input type="text" placeholder="ECXXXX" name="subID"><br>
        subjectname: <input type="text" placeholder="Intro to abc" name="subname"><br>

        <label for="qual">Qualification:</label>
        <select name="qual" id="qual">
            <option value="diploma">Diploma</option>
            <option value="degree">Degree</option>
        </select>
        <br>

        <label for="subtype">Type of class (theory/practical):</label>
        <select name="subtype" id="subtype">
            <option value="theory">Theory</option>
            <option value="practical">Practical</option>
        </select>
        <br>

        <label for="sem">Sem:</label>
        <select name="sem" id="sem">
            <option value="Jan">January</option>
            <option value="May">May</option>
            <option value="Oct">October</option>
</select>
        <br>

        Course: <input type="text" placeholder="DCS/DIT" name="course"><br>
        
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>