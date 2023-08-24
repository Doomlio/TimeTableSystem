<?php
require_once("config.php");

$LEC_ID = array();
$LECQUERY = $mysqli->query("SELECT DISTINCT lec_id FROM timetable");
while ($row = $LECQUERY->fetch_assoc()) {
    $LEC_ID[] = $row["lec_id"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>   
    <!--  <link rel="stylesheet" href="timetable.css"> -->
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
        .occupied {
            background-color: #03ad12;
        }
        .timetable-section {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }
        .draggable {
            position: absolute;
        }
    </style>
</head>
<body>
<?php
// Generate timetables for each lecturer
foreach ($LEC_ID as $lecturerId) {
    // Query to fetch data from the timetable table for the current lecturer
    $result = $mysqli->query("
    SELECT t.*, s.subname, l.lecname
    FROM timetable t
    JOIN lecturer l ON t.lec_id = l.lec_id
    JOIN subject s ON t.subID = s.subID
    WHERE t.lec_id = '$lecturerId'
    ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')
");

    // Check if the query was successful
    if ($result->num_rows > 0) {
        echo "<div class='timetable-section'>";
        // Fetch the lecturer's name from the first row
        $firstRow = $result->fetch_assoc();
        $lecname = $firstRow['lecname'];
        echo "<h2>Lecturer name: $lecname</h2>";

        // Initialize daysOfWeek array for the current lecturer
        $daysOfWeek = array(
            "monday" => array(),
            "tuesday" => array(),
            "wednesday" => array(),
            "thursday" => array(),
            "friday" => array()
        );

        // Loop through the fetched data and populate the daysOfWeek array
        do {
            $day = $firstRow["day"];
            $startHour = $firstRow["start_time"];
            $endHour = $firstRow["end_time"];
            $subjectName = $firstRow["subname"];
            $lecname = $firstRow["lecname"];
            $type = $firstRow["classtype"]; // Fetch the 'type' field from the database

            $daysOfWeek[$day][] = array(
                "start_time" => $startHour,
                "end_time" => $endHour,
                "subject_name" => $subjectName,
                "lecname" => $lecname,
                "type" => $type // Include 'type' in the array
            );
        } while ($firstRow = $result->fetch_assoc());

        // Display the timetable in a separate table for each lecturer
        echo "<table border='5' cellspacing='0'>";
        // Generate header row for the hours
        echo "<tr>";
        echo "<th></th>";

        for ($hour = 8; $hour <= 17; $hour++) {
            $formattedHour = ($hour % 12 == 0) ? 12 : $hour % 12;
            $nextHour = ($hour + 1) % 24;
            $amPm = ($hour < 12) ? "AM" : "PM";
            $nextAmPm = ($nextHour < 12) ? "AM" : "PM";

            // Special case for 11 AM to 12 PM
            if ($formattedHour === 11 && $amPm === "AM" && $nextHour === 0) {
                $formattedNextHour = 12;
                $nextAmPm = "PM";
            } else {
                $formattedNextHour = ($nextHour % 12 == 0) ? 12 : $nextHour % 12;
            }

            echo "<th>{$formattedHour} {$amPm} - {$formattedNextHour} {$nextAmPm}</th>";
        }
        echo "</tr>";
        // Loop through the days of the week and generate timetable cells
        foreach ($daysOfWeek as $dayName => $daySlots) {
            if (!empty($daySlots)) {
                echo "<tr>";
                echo "<td>$dayName</td>";

                for ($hour = 8; $hour <= 17; $hour++) {
                    $occupiedClass = '';
                    $cellContent = '';
                    $colspan = 1; // Default colspan value

                    foreach ($daySlots as $slot) {
                        $startTimeStamp = strtotime($slot["start_time"]);
                        $endTimeStamp = strtotime($slot["end_time"]);

                        $classHours = date('H', $endTimeStamp) - date('H', $startTimeStamp);
                        $classStartHour = date('H', $startTimeStamp);

                        if ($hour >= $classStartHour && $hour < ($classStartHour + $classHours)) {
                            $occupiedClass = 'occupied';

                            // Add the slot's content to the cell content
                            $cellContent .= "{$slot["subject_name"]}<br>{$slot["lecname"]}<br>";

                            // Calculate colspan to span multiple cells based on class hours
                            if ($classHours > 1) {
                                $colspan = $classHours;
                                $cellContent .= "<span class='class-type'>{$slot["type"]}</span>";
                            }
                        }
                    }

                    if ($occupiedClass === 'occupied') {
                        echo "<td class='$occupiedClass' colspan='$colspan'>$cellContent</td>";
                        $hour += $colspan - 1; // Skip additional hours covered by colspan
                    } else {
                        echo "<td></td>"; // Empty cell for unoccupied slots
                    }
                }

                echo "</tr>";
            } else {
                // Generate a row of empty slots for days without timeslots
                echo "<tr>";
                echo "<td>$dayName</td>";
                for ($hour = 8; $hour <= 17; $hour++) {
                    echo "<td></td>"; // Empty cell for unoccupied slots
                }
                echo "</tr>";
            }
        }

        // Close the table for this lecturer
        echo "</table>";
        echo "</div>";
    } // Close the if statement for checking if the query was successful
} // Close the loop for through $LEC_ID array

?>
    <footer>
        <form method="post" action="inserttimeslot.php">
            <button type="submit">Add Timeslot</button>
        </form>
        <form method="post" action="viewsubject.php">
            <button type="submit">Manage Subject</button>
        </form>
    </footer>
</body>
</html>