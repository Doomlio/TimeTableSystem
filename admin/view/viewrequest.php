<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Requests</title>
    <style>
        td {
            border: 1px solid #000;
            padding: 10px;
            width: 200px;
        }
    </style>
</head>
<body>
    <h2>View Requests</h2>

    <?php
    require_once("../../config.php");


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['approve'])) {
            $approvedRequestID = $_POST['approve'];
    
            // Fetch the new data from the request table
            $newDataQuery = "SELECT new_start_time, new_end_time, new_day, new_class_type, new_venue_id
                             FROM request
                             WHERE timetable_id = ?";
            $stmtNewData = $mysqli->prepare($newDataQuery);
            $stmtNewData->bind_param("s", $approvedRequestID);
            $stmtNewData->execute();
            $resultNewData = $stmtNewData->get_result();
            $rowNewData = $resultNewData->fetch_assoc();
            $stmtNewData->close();
    
            // Update the timetable table with the new data from the request table
            $updateQuery = "UPDATE timetable 
                            SET start_time = ?, end_time = ?, day = ?, classtype = ?, venueID = ?
                            WHERE timetable_id = ?";
            $stmtUpdate = $mysqli->prepare($updateQuery);
            $stmtUpdate->bind_param("ssssss",
                $rowNewData['new_start_time'],
                $rowNewData['new_end_time'],
                $rowNewData['new_day'],
                $rowNewData['new_class_type'],
                $rowNewData['new_venue_id'],
                $approvedRequestID
            );
            $stmtUpdate->execute();
            $stmtUpdate->close();
              // Change the request status to 'approved'
            $updateStatusQuery = "UPDATE request SET status = 'approved' WHERE timetable_id = ?";
            $stmtUpdateStatus = $mysqli->prepare($updateStatusQuery);
            $stmtUpdateStatus->bind_param("s", $approvedRequestID);
            $stmtUpdateStatus->execute();
            $stmtUpdateStatus->close();
            echo '<p style="color: green;">Request approved successfully.</p>';
        } elseif (isset($_POST['deny'])) {
            $deniedRequestID = $_POST['deny'];
    
            // Update the request status to "deny"
            $updateStatusQuery = "UPDATE request SET status = 'deny' WHERE timetable_id = ?";
            $stmtUpdateStatus = $mysqli->prepare($updateStatusQuery);
            $stmtUpdateStatus->bind_param("s", $deniedRequestID);
            $stmtUpdateStatus->execute();
            $stmtUpdateStatus->close();
            echo '<p style="color: red;">Request denied successfully.</p>';
        }
    }




    // Retrieve existing data from the timetable table based on the request timetable ID
    $sql = "SELECT t.*, r.timetable_id AS request_timetable_id,
    r.new_start_time, r.new_end_time, r.new_day, r.new_class_type, r.new_venue_id
    FROM timetable t
    JOIN request r ON t.timetable_id = r.timetable_id
    WHERE r.status != 'deny' AND r.status != 'approved'";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data was fetched successfully
    if ($result && $result->num_rows > 0) {
        echo '<table class ="custom-table" border="1">
                <tr>
                    <th></th>
                    <th>Timetable Data</th>
                    <th>New Data</th>
                </tr>';

        // Loop through the rows and output the data
        while ($row = $result->fetch_assoc()) {
            // Assign new data from the request table to variables
            $newStartTime = $row["new_start_time"];
            $newEndTime = $row["new_end_time"];
            $newDay = $row["new_day"];
            $newClassType = $row["new_class_type"];
            $newVenueID = $row["new_venue_id"];
            $requestTimetableID = $row["request_timetable_id"]; // This is the timetable_id from the request table

            // Get timetable data from the timetable table
            $timetableStartTime = $row["start_time"];
            $timetableEndTime = $row["end_time"];
            $timetableDay = $row["day"];
            $timetableClassType = $row["classtype"];
            $timetableVenueID = $row["venueID"];

            // Output the data for each row
            echo '<tr>
                    <td>Timetable ID (Request)</td>
                    <td>' . $requestTimetableID . '</td>
                    <td>-</td>
                </tr>';

                echo '<tr>
                    <td>Timetable ID (timetable)</td>
                    <td>' . $requestTimetableID . '</td>
                    <td>-</td>
                    </td>
                </tr>';

            // Check if there are differences and display them
            if ($timetableStartTime !== $newStartTime) {
                echo '<tr>
                        <td>Start Time</td>
                        <td>' . $timetableStartTime . '</td>
                        <td>' . $newStartTime . '</td>
                    </tr>';
            }
            if ($timetableEndTime !== $newEndTime) {
                echo '<tr>
                        <td>End Time</td>
                        <td>' . $timetableEndTime . '</td>
                        <td>' . $newEndTime . '</td>
                    </tr>';
            }
            if ($timetableDay !== $newDay) {
                echo '<tr>
                        <td>Day</td>
                        <td>' . $timetableDay . '</td>
                        <td>' . $newDay . '</td>
                    </tr>';
            }
            if ($timetableClassType !== $newClassType) {
                echo '<tr>
                        <td>Class Type</td>
                        <td>' . $timetableClassType . '</td>
                        <td>' . $newClassType . '</td>
                    </tr>';
            }
            if ($timetableVenueID !== $newVenueID) {
                echo '<tr>
                        <td>Venue ID</td>
                        <td>' . $timetableVenueID . '</td>
                        <td>' . $newVenueID . '</td>
                    </tr>';
            }
            echo'<td>';
            echo' <td class="action-buttons">
            <form method="post" action="">
                <button type="submit" class="link-button"name="approve" value="' . $requestTimetableID . '">Approve</button>
                <button type="submit" class="back"name="deny" value="' . $requestTimetableID . '">Deny</button>
            </form>';
        }
        
        echo'<td>';
     
        echo '</table>';
    } else {
        echo "No data found.";
    }


    $stmt->close();
    ?>
    <a href="/admin/view/timetable.php" class="link-button">Back to Timetable</a>
</body>
</html>
