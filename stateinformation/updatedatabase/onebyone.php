<?php 
//database connection
include ('db.php');


if(isset($_POST["delete"])){	

$icnumber=$_POST['icnumber'];
$showpage=0;

$sqldelete="DELETE FROM user2 WHERE icnumber='$icnumber'";
$resultDELETE=$mysqli->query($sqldelete);
$notifyDelete=1;

if ($notifyDelete==1 ){
        header("refresh:1;url=updateonebyone.php");
		echo "<script>alert('Data is successfully saved.')</script>";	
				
	} 
}

if(isset($_POST["update"])){		

$icnumber=$_POST['icnumber'];
$showpage=1;
$sqlrenewal1="SELECT * FROM user2 WHERE icnumber='$icnumber'";
$resultRenewal1=$mysqli->query($sqlrenewal1);
	
  while($row = $resultRenewal1->fetch_assoc()){
  		$icnumber123=$row["icnumber"];
  		$name123=$row["name"];
  		$age123=$row["age"];
  		$weight123=$row["weight"];
  		$address123=$row["address"];
	}

}

?>

<?php 

if ($showpage==1) {

?>
<!DOCTYPE html>

<html>
<head><title>One Name</title>


</head>
<body>
<h2>Retrieve One Student Record</h2>
<p></p>
<table>
	<thead>
	<tr>
		<th>IC Number</th>
		<th>Name</th>
		<th>Age</th>
		<th>Weight</th>
		<th>Address</th>
		<th>Action</th>
	</tr>
	</thead>
<form method=post action=oneoneone.php>
	<tr>
		<td><?php echo $icnumber123 ?></td>
		<td><input type=text name=myname value="<?php echo $name123 ?>"> </td>
		<td><input type=text name=myage value="<?php echo $age123 ?>"> </td>
		<td><input type=text name=myweight value="<?php echo $weight123 ?>"> </td>
		<td><input type=text name=myaddress value="<?php echo $address123 ?>"> </td>
			
		<td><button name="update123" >Update</button></td>

			<input type=hidden name=myicnumber value=<?php echo $icnumber123 ?> >
		</form>			
	</tr>


</table>


</body>
</html>

<?php 
}
?>
