<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
error_reporting(0);
require_once("session.php");
include "connection.php";
require_once('mpdf571/mpdf571/mpdf.php');
function emailtempaltedetails()
{
 global $connection;
$select_query=mysqli_query($connection,"SELECT ETD_EMAIL_SUBJECT,ETD_EMAIL_BODY FROM JP_EMAIL_TEMPLATE_DETAILS WHERE ETD_ID=1");
while($row=mysqli_fetch_array($select_query))
{
    $templatedetails[]=array($row['ETD_EMAIL_SUBJECT'],$row['ETD_EMAIL_BODY']);
}
return $templatedetails;
}
//$emaildetails=array();
function emaildetails()
{
    global $connection;
    $select_query=mysqli_query($connection,"SELECT URC_DATA FROM JP_USER_RIGHTS_CONFIGURATION ORDER BY URC_ID ASC");
    while($row=mysqli_fetch_array($select_query))
    {
        $emaildetails[]=array($row['URC_DATA']);
    }
    return $emaildetails;
}
if($_POST["Option"]=="ERROR")
{
    $select_query=mysqli_query($connection,"SELECT EMC_DATA FROM JP_ERROR_MESSAGE_CONFIGURATION");
    while($row=mysqli_fetch_array($select_query)){
        $errormessage[]=$row['EMC_DATA'];
    }
//   echo $errormessage;
   echo json_encode($errormessage);
//    print_r($errormessage); exit;

}

