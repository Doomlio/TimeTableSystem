<?php 
//database connection
include ('db.php');
?>

<!DOCTYPE html>

<html>
<head><title>Update One by One</title>

</head>
<body>
<h2>Retrieve Student Record</h2>
<p></p>

<table>
	<thead>
	<tr>
		<th>#</th>
		<th>IC Number</th>
		<th>Name</th>
		<th>Age</th>
		<th>Weight</th>
		<th>Address</th>
		<th>Action</th>
	</tr>
	</thead>

<?php

  $sqlrenewal="SELECT * FROM user2";
	$resultRenewal=$mysqli->query($sqlrenewal);
	$no=1;
  while($row = $resultRenewal->fetch_assoc()){
  		$icnumber=$row["icnumber"];
  		$name=$row["name"];
  		$age=$row["age"];
  		$weight=$row["weight"];
  		$address=$row["address"];
								
?>
			
  <tr>
		<td style="text-align:center"><?php echo $no ?></td>
		<td><?php echo $icnumber ?></td>
		<td><?php echo $name ?></td>
		<td><?php echo $age ?></td>
		<td><?php echo $weight ?></td>
		<td><?php echo $address ?></td>
		<td><form method=post action=onebyone.php> 
			<input type=hidden name=icnumber value=<?php echo $icnumber ?> >
			<button name="update">Update</button>
			<button name="delete">Delete</button>
		</form>

		</td>
		
	</tr>
	
<?php
	$no++;
}
 ?>
</table>

<button onclick="window.location.href='renewal.php';">Main Page</button>
<button onclick="window.location.href='insert.html';">Insert Page</button>

</body>


</html>

