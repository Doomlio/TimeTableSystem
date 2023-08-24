<?php 
session_start();
error_reporting(0);
//database connection
include ('db.php');

//bring the session
$matric_no = $_SESSION['matric_no'];
$password = $_SESSION['password'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];

//check access
$checkaccess="SELECT * from login where matric=? and password=?";
$stmt = $mysqli->prepare($checkaccess); 
$stmt->bind_param("ss", $matric, $password);
$stmt->execute();
$resultcheckaccess = $stmt->get_result(); // get the mysqli result
$total=$resultcheckaccess->num_rows;

	if($password=="" && $matric=="") {
  		header("refresh:1;url=loginwithsession.html");
  		echo "<script language=javascript>alert('Access Denied!');</script>";
  	
  	} else {


?>

<html>
<body>
<h1>My Landing Page</h1>

Hi <?php echo $name ?>, How are you? <br>
Your email: <?php echo $email ?> <br>
Your matric number: <?php echo $matric_no ?> <br>

<button onclick="window.location.href='signoutsession.php';">Signout</button>

</body>
</html>

<?php 
}

?>