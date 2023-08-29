<?php
// Start the session at the very beginning of the file
session_start();

require_once("../../config.php");

if (!isset($_SESSION["lec_id"]) || !isset($_SESSION["name"])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit;
}

$lec_id = $_SESSION["lec_id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/asset/timetable.css">
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

    <script>
        function redirectToRequestForm(timetableID, subID, subName, startTime, endTime, day, classtype, venueID) {
            var url = "requestform2.php" +
                    "?timetableID=" + timetableID +
                    "&subID=" + subID +
                    "&subName=" + encodeURIComponent(subName) +
                    "&startTime=" + startTime +
                    "&endTime=" + endTime +
                    "&day=" + day +
                    "&classtype=" + classtype +
                    "&venueID=" + venueID;
            window.location.href = url;
        }
    </script>
</head>
<body>
    <?php
    $result = $mysqli->query("
        SELECT t.*, s.subname, l.lecname
        FROM timetable t
        JOIN lecturer l ON t.lec_id = l.lec_id
        JOIN subject s ON t.subID = s.subID
        WHERE t.lec_id = '$lec_id'
        ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')
    ");

    if ($result->num_rows > 0) {
        echo "<table class ='custom-table'>
        <tr>
            <th>Timetable ID</th>
            <th>Subject ID</th>
            <th>Subject Name</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Day</th>
            <th>Class Type</th>
            <th>Venue ID</th>
            <th>Actions</th>
        </tr>";

        while ($row = $result->fetch_assoc()) {
            $timetableID = $row["timetable_id"];
            $subName = $row["subname"];
            $startTime = $row["start_time"];
            $endTime = $row["end_time"];
            $day = $row["day"];
            $classtype = $row["classtype"];
            $subID = $row["subID"];
            $venueID = $row["venueID"];

            echo "<tr>
                <td>$timetableID</td>
                <td>$subID</td>
                <td>$subName</td>
                <td>$startTime</td>
                <td>$endTime</td>
                <td>$day</td>
                <td>$classtype</td>
                <td>$venueID</td>
                <td>
                    <button type='button' class='link-button'onclick='redirectToRequestForm(\"$timetableID\", \"$subID\", \"$subName\", \"$startTime\", \"$endTime\", 
                    \"$day\", \"$classtype\", \"$venueID\")'>Request changes</button>
                </td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }
   
    ?>

    <h2>Your Requests</h2>
    <table class ="custom-table" border="1">
        <tr>
            <th>Timetable ID</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Day</th>
            <th>Class Type</th>
            <th>Venue</th>
            <th>Status</th>
        </tr>
        <?php
        // Fetch and display the user's requests
        $sqlGetUserRequests = "SELECT * FROM request WHERE lecid = ?";
        $stmtGetUserRequests = $mysqli->prepare($sqlGetUserRequests);
        $stmtGetUserRequests->bind_param('i', $lec_id);
        $stmtGetUserRequests->execute();
        $resultUserRequests = $stmtGetUserRequests->get_result();

        while ($rowUserRequest = $resultUserRequests->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$rowUserRequest['timetable_id']}</td>";
           
            echo "<td>{$rowUserRequest['new_start_time']}</td>";
            echo "<td>{$rowUserRequest['new_end_time']}</td>";
            echo "<td>{$rowUserRequest['new_day']}</td>";
            echo "<td>{$rowUserRequest['new_class_type']}</td>";
            echo "<td>{$rowUserRequest['new_venue_id']}</td>";
            echo "<td>{$rowUserRequest['status']}</td>";
            echo "</tr>";
        }

        $stmtGetUserRequests->close();
        $mysqli->close();
        ?>
    </table>

  
 <button class="link-button" onclick="window.location.href='/lecturer/view/lectimetable.php';">Back To Timetable</button>

</body>
</html>