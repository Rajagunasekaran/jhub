<?php
session_start(); // Starting Session
include "connection.php";
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
// Define $username and $password
$username=$_POST['username'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
// Selecting Database
//$db = mysql_select_db("jprint", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysqli_query($connection,"SELECT * FROM JP_USER_LOGIN_DETAILS UPD,JP_ROLE_CREATION RC WHERE RC.RC_ID=UPD.RC_ID and ULD_PASSWORD='$username' AND ULD_USERNAME='$password'");
$row= mysqli_fetch_array($query);
$rows = mysqli_num_rows($query);
if ($rows == 1)
{
$status=$row["RC_ID"];
$_SESSION['login_user']=$username; // Initializing Session
 if($status==2)
 {
    header("location: usermenu.php"); // Redirecting To Other Page
 }
 if($status==1)
{
header("location: DashBoard.php"); // Redirecting To Other Page
}
}
else
{
$error = "Username or Password is invalid";
}
mysql_close($connection); // Closing Connection
}
}
?>