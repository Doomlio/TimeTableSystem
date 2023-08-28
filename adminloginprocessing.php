<?php
session_start();

// Database connection
include('config.php');

if (isset($_POST["login"])) {
    $email = $_POST['email']; 
    $userpassword = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE email=? AND password=?"; // Change to 'admin' table
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $email, $userpassword);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->num_rows;

    if ($total == 0) {
        header("refresh:1;url=login.php");
        ?>
        <script language=javascript>alert('ACCESS DENIED!');</script>
        <?php
    } else {
        $row = $result->fetch_assoc();
        $admin_id = $row["admin_id"];
        $admin_name = $row["admin_name"];
        
        $_SESSION["admin_id"] = $admin_id;
        $_SESSION["name"] = $admin_name;

        header("location: timetable.php"); 
        exit; // Make sure to exit after header redirection
    }
}
?>
