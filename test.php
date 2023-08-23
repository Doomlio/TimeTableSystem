<?php
require_once('config.php');

// Assuming you've already retrieved the lecturer ID from the user or another source
$lecturerId = 1; // Replace with the actual lecturer ID

// Retrieve lecturer information
$query = "SELECT lecname FROM lecturer WHERE lec_id = $lecturerId";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$lecname = $row['lecname'];

// Retrieve lecturer's timetable
$query = "SELECT * FROM timetable WHERE lec_id = $lecturerId";
$result = $mysqli->query($query);
$timetableData = $result->fetch_all(MYSQLI_ASSOC);

$timeSlots = array(
    '08:00:00', '09:59:00', '10:00:00',
    '11:59:00', '12:00:00', '13:00:00',
    '14:59:00', '15:00:00', '16:59:00',
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Timetable</title>
    <style>
        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1><?php echo $lecname; ?>'s Timetable</h1>

    <table border="1">
        <tr>
            <th></th>
            <?php foreach ($timeSlots as $timeSlot) {
                echo "<th>$timeSlot</th>";
            } ?>
        </tr>

        <?php
        foreach (array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday') as $day) {
            echo "<tr>";
            echo "<td>$day</td>";

            foreach ($timeSlots as $timeSlot) {
                $classData = '';
                $colspan = 1;

                foreach ($timetableData as $index => $class) {
                    if ($class['start_time'] <= $timeSlot && $class['end_time'] > $timeSlot && $class['day'] == strtolower($day)) {
                        $classData = $class['subject_name'];
                        $colspan = 1;

                        // Check for continuous classes
                        while ($index + $colspan < count($timetableData) &&
                               $timetableData[$index + $colspan]['start_time'] < $timeSlot &&
                               $timetableData[$index + $colspan]['end_time'] > $timeSlot &&
                               $timetableData[$index + $colspan]['day'] == strtolower($day)) {
                            $colspan++;
                        }

                        break;
                    }
                }

                if ($classData) {
                    echo "<td colspan=\"$colspan\">$classData<br>$lecname</td>";
                } else {
                    echo "<td></td>";
                }
            }

            echo "</tr>";
        }
        ?>

    </table>
</body>
</html>
