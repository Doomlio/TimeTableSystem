<?php 

//database connection
include ('db.php');

if(isset($_POST["update123"])){		


$icnumber=$_POST['myicnumber'];
$name=$_POST['myname'];
$age=$_POST['myage'];
$weight=$_POST['myweight'];
$address=$_POST['myaddress'];

	$sqlUpdateRecord = $mysqli->prepare("UPDATE user2 SET name=?, age=?, weight=?, address=? WHERE icnumber=?");
	$sqlUpdateRecord->bind_param('siisi', $name, $age, $weight, $address, $icnumber);
	$sqlUpdateRecord->execute();
	$sqlUpdateRecord->close();
	$notifyUpdate=1;;

	if ($notifyUpdate==1 ){
        header("refresh:1;url=renewal.php");
		echo "<script>alert('Data is successfully saved.')</script>";	
				
	} 
}

?>