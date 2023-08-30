<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/asset/timetable.css">   
    <!--  <link rel="stylesheet" href="/asset/timetable.css"> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGT Systems</title>
    <style>
        .class-type {
            font-size: 12px;
            font-weight: normal;
            display: block;
        }
        td {
            height: 30px;
            border: 1px solid #000;
            padding: 10px;
            width: 100px;
        }
    </style>
</head>
<body>
<?php
    // Start or resume the session
    session_start();
    
    // Include the configuration file
    require_once("../../config.php");
if (!isset($_SESSION["lec_id"])) {
    header("Location: /lecturer/login/login.php");
    exit;
}







    // Get the lecturer ID from the session
    $lec_id = $_SESSION["lec_id"];

    // Use a prepared statement to prevent SQL injection
    $sql = "SELECT * FROM subject WHERE lecid = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $lec_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class ='custom-table'><tr><th>Subject Code</th><th>Subject Name</th><th>Qualification</th><th>Semester</th><th>Course</th></tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["subID"] . "</td>
                      <td>" . $row["subname"] . "</td>
                      <td>" . $row["qualification"] . "</td>
                      <td>" . $row["sem"] . "</td>
                      <td>" . $row["course"] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $stmt->close();
    $mysqli->close();
?>
  
 <button class="link-button" onclick="window.location.href='/lecturer/view/lectimetable.php';">Back To Timetable</button>

</body>
</html>
