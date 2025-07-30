<?php 
  session_start();
  // $_SESSION['mid'] = 1125;
  // $_SESSION['uname'] = "Raj";
  
  echo "<pre>";
  print($_SESSION['mid']);
  print($_SESSION['uname']);
  die;
?>