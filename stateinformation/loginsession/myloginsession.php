<?php
session_start();

//database connection
include ('db.php');

if(isset($_POST["mylogsession"])){ 

$matric=$_POST['matric'];
$userpassword=$_POST['password'];
$userpassword=md5($userpassword);

}

$sql="SELECT * from login where matric=? and password=?";
$stmt = $mysqli->prepare($sql); 
$stmt->bind_param("ss", $matric, $userpassword);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$total=$result->num_rows;

            if($total==0){

                    header("refresh:1;url=loginwithsession.html");
?>
                    <script language=javascript>alert('ACCESS DENIED!');</script>

<?php 
            
            }

            if($total!=0){

                while($row = $result->fetch_assoc())
                    {
                        $matric=$row["matric"];
                        $password=$row["password"];
                        $name=$row["name"];
                        $email=$row["email"];
                    }

                header("refresh:1;url=landingsession.php");

                $_SESSION["matric_no"] = $matric;
                $_SESSION["password"] = $password;
                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;
            
            }

?>
