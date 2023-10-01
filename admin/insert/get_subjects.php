<?php
// Include config.php to load configuration settings
require_once('../../config.php');

if (isset($_GET['semester'])) {
    $selectedSemester = $_GET['semester'];
    
    
    // Query to select subject IDs based on the selected semester
    $subjectQuery = "SELECT subID FROM subject WHERE sem = '$selectedSemester'";
    $subjectResult = mysqli_query($mysqli, $subjectQuery);

    if (!$subjectResult) {
        die("Database query for subject IDs failed.");
    }

    // Generate subject checkboxes with subject IDs
    while ($row = mysqli_fetch_assoc($subjectResult)) {
        $subID = $row['subID'];
        echo "<input type='checkbox' name='subjects[]' value='$subID'> $subID<br>";
    }
   
}
?>