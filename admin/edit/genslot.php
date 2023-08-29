<?php
include ('../../config.php');

// Function to generate random venue based on type (Lab or Lecture)
function generateRandomVenue($type) {
    global $mysqli;


    $sqlVenues = "SELECT venueid FROM venue WHERE venuetype = ?";
    $stmtVenues = $mysqli->prepare($sqlVenues);
    $stmtVenues->bind_param('s', $type);
    $stmtVenues->execute();
    $resultVenues = $stmtVenues->get_result();

    $availableVenues = array();

    while ($row = $resultVenues->fetch_assoc()) {
        $availableVenues[] = $row['venueid'];
    }

    if (count($availableVenues) > 0) {
        $randomIndex = array_rand($availableVenues);
        return $availableVenues[$randomIndex];
    }

    return null;
}

// Function to check for missing subjects and generate timeslots
function checkMissingSubjectsAndGenerateTimeslots($lecID,$subID, $classtype) {
    global $mysqli;

    $sqlAssignedSubjects = "SELECT DISTINCT subID FROM timetable WHERE lec_id = ?";
    $stmtAssignedSubjects = $mysqli->prepare($sqlAssignedSubjects);
    $stmtAssignedSubjects->bind_param('s', $lecID);
    $stmtAssignedSubjects->execute();
    $resultAssignedSubjects = $stmtAssignedSubjects->get_result();

    $assignedSubjects = [];

    while ($row = $resultAssignedSubjects->fetch_assoc()) {
        $assignedSubjects[] = $row['subID'];
    }

    $sqlMissingSubjects = "SELECT DISTINCT subID FROM subject WHERE subID NOT IN (SELECT DISTINCT subID FROM timetable WHERE lec_id = ?)";
    $stmtMissingSubjects = $mysqli->prepare($sqlMissingSubjects);
    $stmtMissingSubjects->bind_param('s', $lecID);
    $stmtMissingSubjects->execute();
    $resultMissingSubjects = $stmtMissingSubjects->get_result();



    // Initialize separate timeslot arrays for lectures and labs
$newLectureTimeslots = array();
$newLabTimeslots = array();

    while ($row = $resultMissingSubjects->fetch_assoc()) {
        $classDuration = 2;
        $randomHour = rand(8, 15);
        $newStartTime = sprintf("%02d:%02d:%02d", $randomHour, 0, 0);
        $newEndTime = date("H:i:s", strtotime($newStartTime) + ($classDuration * 60 * 60));

        // Assuming you have an array of days to choose from
        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday');
        $randomDay = $days[array_rand($days)];

        $clashExists = false;

        // Check for clashes in lecture timeslots
        foreach ($newLectureTimeslots as $existingTimeslot) {
            if (($newStartTime >= $existingTimeslot['start_time'] && $newStartTime < $existingTimeslot['end_time']) ||
                ($newEndTime > $existingTimeslot['start_time'] && $newEndTime <= $existingTimeslot['end_time'])) {
                $clashExists = true;
                break;
            }
        }

        // Check for clashes in lab timeslots
        foreach ($newLabTimeslots as $existingTimeslot) {
            if (($newStartTime >= $existingTimeslot['start_time'] && $newStartTime < $existingTimeslot['end_time']) ||
                ($newEndTime > $existingTimeslot['start_time'] && $newEndTime <= $existingTimeslot['end_time'])) {
                $clashExists = true;
                break;
            }
        }

        if (!$clashExists) {
            // Update the appropriate timeslot array based on class type
            if ($classtype === 'lecture') { // Use the parameter $classtype here
                $newLectureTimeslots[] = [
                    'start_time' => $newStartTime,
                    'end_time' => $newEndTime
                ];
        
                $randomLectureVenue = generateRandomVenue('Lecture');
                if ($randomLectureVenue !== null) {
                    $sqlInsertLectureTimeslot = "INSERT INTO timetable (lec_id, subID, start_time, end_time, day, venueID, classtype, cstatus, hours) VALUES (?, ?, ?, ?, ?, ?, 'lecture', 'active', '2')";
                    $stmtInsertLectureTimeslot = $mysqli->prepare($sqlInsertLectureTimeslot);
                    $stmtInsertLectureTimeslot->bind_param('ssssss', $lecID, $subID, $newStartTime, $newEndTime, $randomDay, $randomLectureVenue);
                    $stmtInsertLectureTimeslot->execute();
                    $stmtInsertLectureTimeslot->close();
                }
            } elseif ($classtype === 'lab') { // Use the parameter $classtype here
                $newLabTimeslots[] = [
                    'start_time' => $newStartTime,
                    'end_time' => $newEndTime
                ];
        
                $randomLabVenue = generateRandomVenue('Lab');
                if ($randomLabVenue !== null) {
                    $sqlInsertLabTimeslot = "INSERT INTO timetable (lec_id, subID, start_time, end_time, day, venueID, classtype, cstatus, hours) VALUES (?, ?, ?, ?, ?, ?, 'lab', 'active', '2')";
                    $stmtInsertLabTimeslot = $mysqli->prepare($sqlInsertLabTimeslot);
                    $stmtInsertLabTimeslot->bind_param('ssssss', $lecID, $subID, $newStartTime, $newEndTime, $randomDay, $randomLabVenue);
                    $stmtInsertLabTimeslot->execute();
                    $stmtInsertLabTimeslot->close();
                }
            }
        }
    }
}

 //get dinsitnct lecID
$sqlDistinctLecturers = "SELECT DISTINCT lec_id FROM timetable";
$resultDistinctLecturers = $mysqli->query($sqlDistinctLecturers);
$lecturerIDs = [];

while ($row = $resultDistinctLecturers->fetch_assoc()) {
    $lecturerIDs[] = $row['lec_id'];
}


foreach ($lecturerIDs as $lecturerID) {
    $sqlAssignedSubjects = "SELECT DISTINCT subID, classtype FROM timetable WHERE lec_id = ?";
    $stmtAssignedSubjects = $mysqli->prepare($sqlAssignedSubjects);
    $stmtAssignedSubjects->bind_param('s', $lecturerID);
    $stmtAssignedSubjects->execute();
    $resultAssignedSubjects = $stmtAssignedSubjects->get_result();

    while ($row = $resultAssignedSubjects->fetch_assoc()) {
        $classtype = $row['classtype'];
        $subID = $row['subID'];
        checkMissingSubjectsAndGenerateTimeslots($lecturerID, $subID, $classtype);
    }
}

// Refresh the page after generating timeslots
header("refresh:1;url=edittimeslot.php");
echo "<script>alert('Timeslots generated for assigned subjects.')</script>";
?>