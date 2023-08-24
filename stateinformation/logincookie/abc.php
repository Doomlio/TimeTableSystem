<?php 
error_reporting(0);
//database connection
include ('db.php');

//bring the cookie
$matric=$_COOKIE["matric"];
$password=$_COOKIE["password"];
$name=$_COOKIE["name"];
$email=$_COOKIE["email"];


//check access
$checkaccess="SELECT * from login where matric=? and password=?";
$stmt = $mysqli->prepare($checkaccess); 
$stmt->bind_param("ss", $matric, $password);
$stmt->execute();
$resultcheckaccess = $stmt->get_result(); // get the mysqli result
$total=$resultcheckaccess->num_rows;

	if($password=="" && $matric=="") {
  		header("refresh:1;url=login.html");
  		echo "<script language=javascript>alert('Access Denied!');</script>";
  	
  	} 

    else {


?>

<html>
<body>
<h1>Test Page</h1>


<a href='landing.php'>Visit Next Page</a>

<button onclick="window.location.href='signout.php';">Signout</button>

</body>
</html>

<?php 
}

?>