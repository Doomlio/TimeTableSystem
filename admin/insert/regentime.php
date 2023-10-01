<?php
// Include config.php to load configuration settings
require_once('../../config.php');

$query = "SELECT lecname FROM lecturer";
$result = mysqli_query($mysqli, $query);

if (!$result) {
    die("Database query for lecturers failed.");
}

// Query to select all distinct semesters
$semesterQuery = "SELECT DISTINCT sem FROM subject";
$semesterResult = mysqli_query($mysqli, $semesterQuery);

if (!$semesterResult) {
    die("Database query for semesters failed.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Semester Selection</title>
</head>
<body>
    <h2>Select Semester, Lecturer, and Subjects</h2>
    <form method="POST" action="process.php">
        <label for="semester">Select Semester:</label>
        <select name="semester" id="semesterSelect">
            <?php
            while ($row = mysqli_fetch_assoc($semesterResult)) {
                $sem = $row['sem'];
                echo "<option value='$sem'>$sem</option>";
            }
            ?>
        </select>
        <br><br>
        
        <label>Choose Lecturer(s):</label><br>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $lecname = $row['lecname'];
                echo '<input type="checkbox" name="lecturers[]" value="' . $lecname . '" checked> ' . $lecname . '<br>';
            }
            ?>
        
        <label>Choose Subject(s):</label><br>
        <div id="subjectCheckboxes">
            <!-- JavaScript will populate checkboxes here -->
        </div>
        <br>

        <input type="submit" value="Submit">
        <input type="button" value="Check All Subjects" id="checkAllSubjects">

    </form>

    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener("DOMContentLoaded", function () {
            // Get references to the select and subject checkboxes container
            var semesterSelect = document.getElementById('semesterSelect');
            var subjectCheckboxes = document.getElementById('subjectCheckboxes');
            var checkAllButton = document.getElementById('checkAllSubjects');

            // Add an event listener to the semester dropdown
            semesterSelect.addEventListener('change', function () {
                var selectedSemester = semesterSelect.value;

                // Create an AJAX request
                var xhr = new XMLHttpRequest();

                // Define the request method, URL, and make it asynchronous
                xhr.open('GET', 'get_subjects.php?semester=' + selectedSemester, true);

                // Define the callback function to handle the response
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            // Update the subject checkboxes container with the response HTML
                            subjectCheckboxes.innerHTML = xhr.responseText;
                        } else {
                            // Handle HTTP errors here
                            console.error('Error:', xhr.status, xhr.statusText);
                        }
                    }
                };

                // Send the AJAX request
                xhr.send();
            });

            // Add a click event listener to the "Check All Subjects" button
            checkAllButton.addEventListener('click', function () {
                var subjectCheckboxes = document.querySelectorAll('input[type="checkbox"]');
                
                // Loop through all subject checkboxes and check them
                subjectCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = true;
                });
            });
        });
    </script>
</body>
</html>
