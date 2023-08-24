<?php

session_start();
unset($_SESSION);
  
session_destroy();
header("refresh:1;url=loginwithsession.html");

?>