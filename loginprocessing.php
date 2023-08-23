<?php
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM lecturer WHERE email = '$email'";
    $result = $mysqli->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['lec_id'];
            header("Location: timetable.php"); // Redirect to the dashboard after successful login
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Email not found.";
    }
}

$mysqli->close();
?>
