<!DOCTYPE html>
<html>
<head>
    <title>Request Form</title>
</head>
<body>
    <h1>Request Form</h1>
    
    <!-- Select Lecturer ID -->
    <label for="lecturerId">Lecturer ID:</label>
    <select id="lecturerId">
        <option value="1">Lecturer 1</option>
        <option value="2">Lecturer 2</option>
        <!-- ... Other lecturer options ... -->
    </select>
    
    <!-- Select Timeslot -->
    <label for="timeslot">Select Timeslot:</label>
    <select id="timeslot">
        <!-- Timeslot options will be dynamically populated using JavaScript -->
    </select>
    
    <!-- Display Selected Timeslot Details -->
    <h2>Selected Timeslot Details:</h2>
    <p>Subject: <span id="subject"></span></p>
    <p>Start Time: <span id="startTime"></span></p>
    <p>End Time: <span id="endTime"></span></p>
    <p>Venue: <span id="venue"></span></p>
    
    <!-- Edit Start Time, End Time, and Venue -->
    <label for="editedStartTime">Edit Start Time:</label>
    <input type="text" id="editedStartTime">
    
    <label for="editedEndTime">Edit End Time:</label>
    <input type="text" id="editedEndTime">
    
    <label for="editedVenue">Edit Venue:</label>
    <input type="text" id="editedVenue">
    
    <!-- Submit Request -->
    <button id="submitRequest">Submit Request</button>
    
    <script>
        // Your JavaScript code to fetch timeslots, populate fields, and handle interactions
    </script>
</body>
</html>
