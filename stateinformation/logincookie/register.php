<?php 
//database connection
include ('db.php');


if(isset($_POST["register"])){		

$matric=$_POST["matric"];
$name=$_POST["name"];
$email=$_POST["email"];
$password=$_POST["password"];
$encryptpwd=md5($password);

//i - integer
//s = string
//d = double
//b = blob

// prepare and bind
$stmt = $mysqli->prepare("INSERT INTO login (matric, name, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $matric, $name, $email, $encryptpwd);
$stmt->execute();
$stmt->close();
$notifyRegister=1;

if ($notifyRegister==1){
        $notifyRegister=0;
        header("refresh:1;url=register.html");
	echo "<script>alert('Data is successfully saved.')</script>";	
				
	} 
}

?>

