<?php
session_start();

// Database connection
include('config.php');

if (isset($_POST["mylogsession"])) {
    $email = $_POST['email']; 
    $userpassword = $_POST['password'];
    $userpassword = md5($userpassword);

    $sql = "SELECT * FROM lecturer WHERE email=? AND password=?"; 
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $email, $userpassword);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->num_rows;

    if ($total == 0) {
        header("refresh:1;url=loginwithsession.html");
?>
        <script language=javascript>alert('ACCESS DENIED!');</script>
<?php
    }

    if ($total != 0) {
        while ($row = $result->fetch_assoc()) {
            $lec_id = $row["lec_id"]; 
            $password = $row["password"];
            $lecname = $row["lecname"]; 
            $email = $row["email"];
        }

        header("refresh:1;url=landingsession.php");

        $_SESSION["lec_id"] = $lec_id;
        $_SESSION["password"] = $password;
        $_SESSION["lecname"] = $lecname;
        $_SESSION["email"] = $email;
    }
}
?>