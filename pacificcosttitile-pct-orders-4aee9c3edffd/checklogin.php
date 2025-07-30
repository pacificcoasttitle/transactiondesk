<?php
$host="propprofile.db.8460164.hostedresource.com"; // Host name
$username="propprofile"; // username
$password="locusV1!"; // password
$db_name="propprofile"; // Database name
$tbl_name="login"; // Table name
 
$connection = mysqli_connect("$host", "$username", "$password", "$db_name");

$username= $_POST['username'];
$password= $_POST['password'];
$result = mysqli_query($connection,"SELECT * FROM $tbl_name WHERE username='$username' and password='$password'");

$count=mysqli_num_rows($result);

if($count==1){
  // Register $myusername, $mypassword and redirect to pma 
  session_start();
  $_SESSION['username'] = $username;
  $_SESSION['password'] = $password;
  header("location: PMA.php");
} else {
  header("location:pma-login.php?msg=Incorrect Login.  Please try again.");
}