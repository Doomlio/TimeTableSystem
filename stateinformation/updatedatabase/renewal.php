<?php 
//database connection
include ('db.php');

if(isset($_POST["submitSave"])){		

	foreach ($_POST['studenticnumber'] as $key => $studentic) {
    $icnumber = $studentic;
    $studentname = $_POST['studentname'][$key];
    $studentage = $_POST['studentage'][$key];
    $studentweight = $_POST['studentweight'][$key];
    $studentaddress = $_POST['studentaddress'][$key];
 	

  //Update table
	$sqlUpdateRecord = $mysqli->prepare("UPDATE subject SET name=?, age=?, weight=?, address=? WHERE icnumber=?");
	$sqlUpdateRecord->bind_param('siisi', $studentname, $studentage, $studentweight, $studentaddress, $icnumber);
	$sqlUpdateRecord->execute();
	$sqlUpdateRecord->close();
	$myupdate=1;

	
}// foreach


if ($sqlUpdateRecord ){
          header("refresh:1;url=editsubject.php");
					echo "<script>alert('Data is successfully saved.')</script>";	
				
				} 
		

		}//if(isset($_POST["submitSave"])){

?>


<!DOCTYPE html>

<html>
<head><title>Retrieve All Student Record</title>


</head>
<body>
<h2>Retrieve Student Record</h2>
<p></p>

<table id="myTable">
	<thead>
	<tr class="header">
		<th class="">#</th>
		<th class="">IC Number</th>
		<th class="">Name</th>
		<th class="">Age</th>
		<th class="">Weight</th>
		<th class="">Address</th>
	</tr>
	</thead>
<form method=post name=updaterenewal action=renewal.php>
<?php

  $sqlrenewal="SELECT * FROM user2";
	$resultRenewal=$mysqli->query($sqlrenewal);
	$no=1;
  while($row = $resultRenewal->fetch_assoc()){
  		$icnumber123=$row["icnumber"];
  		$name123=$row["name"];
  		$age123=$row["age"];
  		$weight123=$row["weight"];
  		$address123=$row["address"];
			
?>
	<tr>
		<td class="" style="text-align:center"><?php echo $no ?></td>
		<td class=""><?php echo $icnumber123 ?></td>
		<input type="hidden" name="studenticnumber[]" value="<?php echo $icnumber123 ?>">
		<td class=""><input type=text name=studentname[] value="<?php echo $name123 ?>"></td>
		<td class=""><input type=text name=studentage[] value="<?php echo $age123 ?>"></td>
		<td class=""><input type=text name=studentweight[] value="<?php echo $weight123 ?>"></td>
		<td class=""><input type=text name=studentaddress[] value="<?php echo $address123 ?>"></td>
		
	</tr>
	
<?php
	$no++;
}
 ?>
</table>
<br>
<button name="submitSave" class="button">Save</button>
</form>
<button onclick="window.location.href='updateonebyone.php';">Update One by One</button>

</body>


</html>

