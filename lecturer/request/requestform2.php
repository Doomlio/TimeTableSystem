
<?php
session_start();

require_once("../../config.php");

if (!isset($_SESSION["lec_id"]) || !isset($_SESSION["name"])) {
    // Redirect the user to the login page if not logged in
    header("Location: /lecturer/login/login.php");
    exit;
}

$lec_id = $_SESSION["lec_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form has been submitted, process the data
    $newStartTime = $_POST["start_time"];
    $newEndTime = $_POST["end_time"];
    $newClassType = $_POST["classtype"];
    $newDay = $_POST["day"];
    $newVenueID = $_POST["venueID"];
    $TimetableID = $_GET["timetableID"]; // Use $_GET here
    
    // Check for clashes in timetable
    $timetableClashesExist = false;
    $sqlCheckTimetableClashes = "SELECT * FROM timetable WHERE lec_id = ? AND day = ? AND venueID = ? AND ((start_time >= ? AND start_time < ?) OR 
    (end_time > ? AND end_time <= ?) OR (start_time <= ? AND end_time >= ?))";
    $stmtCheckTimetableClashes = $mysqli->prepare($sqlCheckTimetableClashes);
    $stmtCheckTimetableClashes->bind_param('sssssssss', $lec_id, $newDay, $newVenueID, $newStartTime, $newEndTime,
     $newStartTime, $newEndTime, $newStartTime, $newEndTime);
    $stmtCheckTimetableClashes->execute();
    $resultTimetableClashes = $stmtCheckTimetableClashes->get_result();
    $timetableClashesExist = ($resultTimetableClashes->num_rows > 0);
    $stmtCheckTimetableClashes->close();

    // Check for clashes in pending requests (time and venue)
    $requestClashesExist = false;
    $sqlCheckRequestClashes = "SELECT * FROM request WHERE lecid = ? AND new_day = ? AND new_venue_id = ? AND 
    ((new_start_time >= ? AND new_start_time < ?) OR (new_end_time > ? AND new_end_time <= ?) OR (new_start_time <= ? AND new_end_time >= ?)) AND status = 'pending'";
    $stmtCheckRequestClashes = $mysqli->prepare($sqlCheckRequestClashes);
    $stmtCheckRequestClashes->bind_param('sssssssss', $lec_id, $newDay, $newVenueID, $newStartTime, 
    $newEndTime, $newStartTime, $newEndTime, $newStartTime, $newEndTime);
    $stmtCheckRequestClashes->execute();
    $resultRequestClashes = $stmtCheckRequestClashes->get_result();
    $requestClashesExist = ($resultRequestClashes->num_rows > 0);
    $stmtCheckRequestClashes->close();

    // Check if there's already a pending or approved request for the same timetable entry
    $existingRequestExist = false;
    $sqlCheckExistingRequest = "SELECT * FROM request WHERE lecid = ? AND timetable_id = ? AND status IN ('pending')";
    $stmtCheckExistingRequest = $mysqli->prepare($sqlCheckExistingRequest);
    $stmtCheckExistingRequest->bind_param('ii', $lec_id, $TimetableID);
    $stmtCheckExistingRequest->execute();
    $resultExistingRequest = $stmtCheckExistingRequest->get_result();
    $existingRequestExist = ($resultExistingRequest->num_rows > 0);
    $stmtCheckExistingRequest->close();

    // Check if clashes exist and handle accordingly
    if ($existingRequestExist) {
        // Request already exists for the same timetable entry
        echo '<script>
            alert("A request for this timetable entry already exists.");
            window.location.href = "requestform.php"; // Redirect to the form
        </script>';
        exit;
    } elseif (!$timetableClashesExist && !$requestClashesExist) {
        // No clashes found, proceed to insert request
        $status = "pending";
        $insertSql = "INSERT INTO request (lecid, timetable_id, new_start_time, new_end_time, new_day, new_class_type, new_venue_id, status)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insertSql);
        $stmt->bind_param("iissssss", $lec_id, $TimetableID, $newStartTime, $newEndTime, $newDay, $newClassType, $newVenueID, $status);
        $result = $stmt->execute(); // Execute the statement and store the result
        
        if ($result) {
            // Successful insertion
            echo '<script>
                alert("Successful request.");
                window.location.href = "requestform.php"; // Redirect to the form
            </script>';
        } else {
            // Error in insertion
            echo '<script>
                alert("Error occurred while submitting the request.");
                window.location.href = "requestform.php"; // Redirect to the form
            </script>';
        }
        
        $stmt->close();
        exit;
    } else {
        // Clashes found, show an alert using JavaScript
        echo '<script>
            alert("There is a clash of time and venue with another timeslot. Please change your request.");
            window.location.href = "requestform.php"; // Redirect to the form
        </script>';
        exit; // Ensure the script execution stops after the alert
    }
}

// Use $_GET to access the data passed in the URL
$timetableID = $_GET["timetableID"];
$subID = $_GET["subID"];
$subName = $_GET["subName"];
$startTime = $_GET["startTime"];
$endTime = $_GET["endTime"];
$day = $_GET["day"];
$classtype = $_GET["classtype"];
$venueID = $_GET["venueID"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/asset/timetable.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Changes for Timetable Entry</title>
     <script>
        function showSuccessAlert() {
            alert("Request submitted successfully!");
        }
    </script>
</head>
<body>
    <h2>Request Changes for Timetable Entry</h2>
    
    <form method="post" action="requestform2.php?timetableID=<?php echo $timetableID; ?>&subID=<?php echo $subID; ?>
    &subName=<?php echo $subName; ?>&startTime=<?php echo $startTime; ?>&endTime=<?php echo $endTime; ?>
    &day=<?php echo $day; ?>&classtype=<?php echo $classtype; ?>&venueID=<?php echo $venueID; ?>"> <!-- get data from view request page -->

        <table class ="custom-table" border="1">
        <tr>
    <td> Timetable ID:</td> <!-- show data -->
    <td> <input type="hidden" name="timetableID" value="<?php echo $timetableID; ?>"><?php echo $timetableID; ?></td>
</tr>
            <tr>
                <td> Subject ID:</td> <!-- show data -->
                <td><?php echo $subID; ?></td>
            </tr>
            <tr>
                <td> Subject Name:</td> <!-- show data -->
                <td><?php echo $subName; ?></td>
            </tr>
            <tr>
            <td>Old Start Time:</td> <!-- show data -->
            <td><?php echo $startTime; ?></td>
            <td>New Start Time:</td>
            <td><input type="time" name="start_time" value="<?php echo substr($startTime, 0, 5); ?>"></td>
            <?php echo "Received start time: $startTime"; ?> <!-- user input  data -->
            </tr>
            <tr>
                <td>Old End Time:</td>
                <td><?php echo $endTime; ?></td>
                <td>New End Time:</td>
                <td><input type="time" name="end_time" value="<?php echo $endTime; ?>"></td> <!-- user input  data -->
            </tr>
            <tr>
                <td>Old Day:</td>
                <td><?php echo $day; ?></td>
                <td>New Day:</td>
                <td>
                    <select name="day"> <!-- user input  data -->
                        <?php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                        foreach ($days as $dayOption) {
                            $selected = ($dayOption === $day) ? 'selected' : '';
                            echo "<option value=\"$dayOption\" $selected>$dayOption</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Old Class Type:</td>
                <td><?php echo $classtype; ?></td>
                <td> Class Type:</td>
                <td>
                
        <select name="classtype"> <!-- User input for new class type -->
        <?php
        $classTypes = ['Lab', 'Lecture'];
        $currentClassType = $classtype; // Assuming $classtype contains the current selected value

        // Display the current selected option first
        echo "<option value=\"$currentClassType\" selected>$currentClassType</option>";

        // Loop through other options
        foreach ($classTypes as $classTypeOption) {
            if ($classTypeOption !== $currentClassType) {
                echo "<option value=\"$classTypeOption\">$classTypeOption</option>";
            }
        }
        ?>

        </select>
    </td>
                </td>
            </tr>
            <tr>
                <td>Old Venue:</td>
                <td><?php echo $venueID; ?></td>
                <td>New Venue:</td> <!-- user input  data -->
<td>
    <select name="venueID">
        <?php
        // Fetch venues that match the current class type
        $sqlMatchingVenues = "SELECT * FROM venue WHERE venuetype = ?";
        $stmtMatchingVenues = $mysqli->prepare($sqlMatchingVenues);
        $stmtMatchingVenues->bind_param('s', $classtype);
        $stmtMatchingVenues->execute();
        $resultMatchingVenues = $stmtMatchingVenues->get_result();

        while ($rowVenue = $resultMatchingVenues->fetch_assoc()) {
            $venueIDOption = $rowVenue['venueid'];
            $selected = ($venueIDOption === $venueID) ? 'selected' : '';

            echo "<option value=\"$venueIDOption\" $selected>$venueIDOption</option>";
        }

        $stmtMatchingVenues->close();
        ?>
    </select>
</td>
            </tr>
        </table>
        
        <button class="link-button" onclick="window.location.href='requestform.php';">Back To request</button>
        <button class="link-button "type="submit">Submit Request</button>
        
    </form>
    <script>
    function validateChanges() {
        // Get new data
        var newStartTime = document.getElementsByName("start_time")[0].value;
        var newEndTime = document.getElementsByName("end_time")[0].value;
        var newDay = document.getElementsByName("day")[0].value;
        var newClassType = document.getElementsByName("classtype")[0].value;
        var newVenueID = document.getElementsByName("venueID")[0].value;

        // Compare with old data
        if (
            newStartTime === "<?php echo $startTime; ?>" &&
            newEndTime === "<?php echo $endTime; ?>" &&
            newDay === "<?php echo $day; ?>" &&
            newClassType === "<?php echo $classtype; ?>" &&
            newVenueID === "<?php echo $venueID; ?>"
        ) {
            alert("Please change at least one detail.");
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }
</script>
    
</body>
</html>