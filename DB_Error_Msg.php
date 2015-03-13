<?php
error_reporting(0);
require_once("session.php");
include "connection.php";
require_once('mpdf571/mpdf571/mpdf.php');
function emailtempaltedetails()
{
 global $connection;
$select_query=mysqli_query($connection,"Select ETD_EMAIL_SUBJECT,ETD_EMAIL_BODY from jp_email_template_details WHERE ETD_ID=1");
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
    $select_query=mysqli_query($connection,"Select URC_DATA from JP_USER_RIGHTS_CONFIGURATION ORDER BY URC_ID ASC");
    while($row=mysqli_fetch_array($select_query))
    {
        $emaildetails[]=array($row['URC_DATA']);
    }
    return $emaildetails;
}
if($_POST["Option"]=="ERROR")
{
    $select_query=mysqli_query($connection,"Select EMC_DATA from jp_error_message_configuration");
    while($row=mysqli_fetch_array($select_query)){
        $errormessage[]=$row['EMC_DATA'];
    }
//   echo $errormessage;
   echo json_encode($errormessage);
//    print_r($errormessage); exit;

}

