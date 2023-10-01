<?php
// Include config.php to load configuration settings
require_once('../../config.php');

// Clear existing timetable data
$clearTimetableQuery = "DELETE FROM timetable";
$clearResult = mysqli_query($mysqli, $clearTimetableQuery);

if (!$clearResult) {
    die("Failed to clear existing timetable data: " . mysqli_error($mysqli));
}

// Get selected subjects from the form
$selectedSubjects = $_POST['subjects'];

// Query to select lecturers
$query = "SELECT lecname FROM lecturer";
$result = mysqli_query($mysqli, $query);

if (!$result) {
    die("Database query for lecturers failed.");
}

// Initialize an array to track subjects assigned to each lecturer
$lecturerSubjects = [];

// Assign three subjects to each lecturer
while ($row = mysqli_fetch_assoc($result)) {
    $lecname = $row['lecname'];
    $lecturerSubjects[$lecname] = array_splice($selectedSubjects, 0, 3);
}

// Function to check for clashes in the timetable
function hasClash($timetable, $day, $startTime, $endTime) {
    foreach ($timetable as $entry) {
        if (
            $entry['day'] === $day &&
            (
                ($entry['start_time'] <= $startTime && $startTime < $entry['end_time']) ||
                ($entry['start_time'] < $endTime && $endTime <= $entry['end_time']) ||
                ($startTime <= $entry['start_time'] && $endTime >= $entry['end_time'])
            )
        ) {
            return true; // Clash detected
        }
    }
    return false; // No clash
}

// Function to get a random venue ID based on classtype and venuetype
function getRandomVenueId($mysqli, $classType, $venueType) {
    $getVenueIdQuery = "SELECT venueID FROM venue WHERE venuetype = '$classType' ORDER BY RAND() LIMIT 1";
    $venueIdResult = mysqli_query($mysqli, $getVenueIdQuery);

    if (!$venueIdResult) {
        die("Database query for venue ID failed.");
    }

    $venueIdRow = mysqli_fetch_assoc($venueIdResult);
    $venueId = $venueIdRow['venueID'];

    return $venueId;
}

// Generate and insert timetable information for each subject
foreach ($lecturerSubjects as $lecturer => $subjects) {
    $timetable = []; // Initialize timetable for this lecturer

    foreach ($subjects as $subject) {
        // Generate and insert lecture entry
        generateTimetableEntry($mysqli, $lecturer, $subject, 'lecture', 'lecture_venue', $timetable);

        // Generate and insert lab entry
        generateTimetableEntry($mysqli, $lecturer, $subject, 'lab', 'lab_venue', $timetable);
    }
}

// Function to generate and insert timetable entry
function generateTimetableEntry($mysqli, $lecturer, $subject, $classType, $venueType, &$timetable) {
    $clash = false;

    do {
        // Generate random day (Monday to Friday)
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday"];
        $randomDay = $days[array_rand($days)];

        // Generate random time (between 8 am and 3 pm)
        $randomStartTime = mt_rand(8, 15); // Hours
        $randomEndTime = $randomStartTime + 2; // Plus two hours for duration

        // Get a random venue ID based on classtype and venuetype
        $venueId = getRandomVenueId($mysqli, $classType, $venueType);

        // Check for clashes with existing timetable entries
        $clash = hasClash($timetable, $randomDay, $randomStartTime, $randomEndTime);

    } while ($clash); // Repeat until no clash is found

    // Query to get lecturer ID (lec_id) based on lecturer name (lecname)
    $getLecturerIdQuery = "SELECT lec_id FROM lecturer WHERE lecname = '$lecturer'";
    $lecturerIdResult = mysqli_query($mysqli, $getLecturerIdQuery);

    if (!$lecturerIdResult) {
        die("Database query for lecturer ID failed.");
    }

    $lecturerIdRow = mysqli_fetch_assoc($lecturerIdResult);
    $lecturerId = $lecturerIdRow['lec_id'];

    // Insert the lecture or lab entry into the timetable
    $insertQuery = "INSERT INTO timetable (lec_id, subID, day, start_time, end_time, classtype, venueID, cstatus) VALUES
        ('$lecturerId', '$subject', '$randomDay', '$randomStartTime:00:00', '$randomEndTime:00:00', '$classType', '$venueId', 'active')";

    $insertResult = mysqli_query($mysqli, $insertQuery);

    if (!$insertResult) {
        die("Insertion into timetable table ($classType) failed: " . mysqli_error($mysqli));
    }

    // Update the timetable for this lecturer
    $timetable[] = [
        'day' => $randomDay,
        'start_time' => $randomStartTime,
        'end_time' => $randomEndTime,
    ];
}

// Redirect to the timetable page
header("Location: /admin/view/timetable.php");
exit; // Make sure to exit to prevent further script execution
?>
