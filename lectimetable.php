    <?php
    session_start();
    require_once("config.php");

    if (!isset($_SESSION["lec_id"]) || !isset($_SESSION["name"])) {
        // Redirect the user to the login page if not logged in
        header("Location: login.php");
        exit;
    }

    $lec_id = $_SESSION["lec_id"];
    $lecname = $_SESSION["name"];

    $result = $mysqli->query("
        SELECT t.*, s.subname, l.lecname
        FROM timetable t
        JOIN lecturer l ON t.lec_id = l.lec_id
        JOIN subject s ON t.subID = s.subID
        WHERE t.lec_id = '$lec_id'
        ORDER BY FIELD(t.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')
    ");

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>   
    <link rel="stylesheet" href="timetable.css">
    <form method="post" action="viewreplacementclass.php">
            <button type="submit">Manage Replacement Class</button>
        </form>
    </head>
    <body>
    <div class="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGT Systems</title>


        <?php
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
                $day = $firstRow["day"]; //fetching all data
                $startHour = $firstRow["start_time"];
                $endHour = $firstRow["end_time"];
                $subjectName = $firstRow["subname"];
                $lecname = $firstRow["lecname"];
                $type = $firstRow["classtype"]; 
                $venueID = $firstRow["venueID"];
                $cstatus = $firstRow["cstatus"];

                $daysOfWeek[$day][] = array( //inputting the data into array
                    "start_time" => $startHour,
                    "end_time" => $endHour,
                    "subject_name" => $subjectName,
                    "lecname" => $lecname,
                    "type" => $type,
                    "venueID" => $venueID,
                    "cstatus" => $cstatus );
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
                        $cellColor = ''; // Default cell color class for occupied (active) status
                        $cstatus = '';
            
                        foreach ($daySlots as $slot) {
                            $startTimeStamp = strtotime($slot["start_time"]);
                            $endTimeStamp = strtotime($slot["end_time"]);
            
                            $classHours = date('H', $endTimeStamp) - date('H', $startTimeStamp);
                            $classStartHour = date('H', $startTimeStamp);
            
                            if ($hour >= $classStartHour && $hour < ($classStartHour + $classHours)) {
                                // Add the slot's content to the cell content
                                $cellContent .= "{$slot["subject_name"]}<br>{$slot["lecname"]}<br>{$slot["start_time"]}-{$slot["end_time"]}<br>
                                {$slot["venueID"]}<br>";

            
            
                                // Calculate colspan to span multiple cells based on class hours
                                if ($classHours > 1) {
                                    $colspan = $classHours;
                                    $cellContent .= "<span class='class-type'>{$slot["type"]}</span>";
                                }
            
                                // Check if cstatus exists in the slot data
                                if (isset($slot["cstatus"])) {
                                    $cstatus = $slot["cstatus"];
            
                                    // Set cell color based on cstatus
                                    if ($cstatus === "active") {
                                        $cellColor = 'occupied';
                                    } elseif ($cstatus === "replacement") {
                                        $cellColor = 'replacement'; // CSS class for replacement color
                                    } elseif ($cstatus === "cancelled") {
                                        $cellColor = 'cancelled'; // CSS class for cancelled color
                                    }
                                    
                                }
            
                                // Check if the slot is occupied
                                if ($occupiedClass !== 'occupied') {
                                    $occupiedClass = 'occupied'; // Set occupied class
                                }
                            }
                        }
            
                        if ($occupiedClass === 'occupied') {
                            // Apply the cell color class based on cstatus
                            echo "<td class='$occupiedClass $cellColor' colspan='$colspan'>$cellContent</td>";
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
    // Close the loop for through $LEC_ID array

    ?>
    <footer>
    </footer>
    </body>
    </html>
