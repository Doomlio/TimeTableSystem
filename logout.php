<?php

session_start();
unset($_SESSION);
  
session_destroy();
header("refresh:1;url=/lecturer/login/login.php");

?>