<?php
session_start();

// Database connection
include('config.php');

if (isset($_POST["login"])) {
    $email = $_POST['email']; 
    $userpassword = $_POST['password'];

    $sql = "SELECT * FROM lecturer WHERE email=? AND password=?"; 
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
        $lec_id = $row["lec_id"];
        $lecname = $row["lecname"];
        
        $_SESSION["lec_id"] = $lec_id;
        $_SESSION["name"] = $lecname;

        header("location: lectimetable.php"); 
        exit; // Make sure to exit after header redirection
    }
}
?>
