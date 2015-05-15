<?php
include "connection.php";
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
// Selecting Database
//$db = mysql_select_db("jprint", $connection);
session_start();// Starting Session
// Storing Session
$user_check=$_SESSION['login_user'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysqli_query($connection,"SELECT *FROM JP_USER_LOGIN_DETAILS UPD,JP_ROLE_CREATION RC WHERE RC.RC_ID=UPD.RC_ID and UPD.ULD_USERNAME='$user_check'");
$row = mysqli_fetch_assoc($ses_sql);
$login_session =$row['ULD_USERNAME'];
$login_status =$row['RC_NAME'];
$img_sql=mysqli_query($connection,"SELECT *FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$user_check'");
$imgrow = mysqli_fetch_assoc($img_sql);
$imgname=$imgrow['ULD_IMAGE_NAME'];
$img_url="images/".$imgname;
if(!isset($login_session)){
mysqli_close($connection); // Closing Connection
header('Location: index.php'); // Redirecting To Home Page
}
?>