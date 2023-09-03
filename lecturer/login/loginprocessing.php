<?php
session_start();

// Database connection
include('../../config.php');

if (isset($_POST["login"])) { //if login is pressed
    $email = $_POST['email']; 
    $userpassword = $_POST['password'];

    $sql = "SELECT * FROM lecturer WHERE email=? AND password=?";  //check db for details
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $email, $userpassword);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->num_rows; //get the matching login details

    if ($total == 0) { //if not found, redirect back to login.php
        header("refresh:1;url=/lecturer/login/login.php");
        ?>
<script language=javascript>
alert('ACCESS DENIED!');
</script>
<?php
    } else { // get lecturer id and name and then put as session
        $row = $result->fetch_assoc();
        $lec_id = $row["lec_id"];
        $lecname = $row["lecname"];
        
        $_SESSION["lec_id"] = $lec_id; //intialize session
        $_SESSION["name"] = $lecname;

        header("location: /lecturer/view/lectimetable.php"); //land in timetable.php
        exit; // Make sure to exit after header redirection
    }
}
?>