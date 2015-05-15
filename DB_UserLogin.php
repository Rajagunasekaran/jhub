<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
error_reporting(0);
require_once("session.php");
include "connection.php";
$dir=dirname(__FILE__).DIRECTORY_SEPARATOR;
$uldquery="SELECT ULD_ID FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$login_session'";
$select = $connection->query($uldquery);
if($record=mysqli_fetch_array($select))
{
    $uld_id= $record['ULD_ID'];
}

if($_REQUEST["Option"]=='Role')
{
    $select_option="SELECT *FROM JP_ROLE_CREATION ORDER BY RC_NAME ASC";
    $sql=mysqli_query($connection,$select_option);
    while($row = mysqli_fetch_array($sql)) {
        $sid=$row['RC_ID'];
        $sname=$row['RC_NAME'];
        $data[]=array($sid,$sname);
}
    $tablerecords=UserTable();
    $values=array($tablerecords,$data);
    echo json_encode($values);
}
elseif($_REQUEST["Option"]=='UserInsert')
{
    $username=$_REQUEST['username'];
    $password=$_REQUEST['password'];
    $useremail=$_REQUEST['useremail'];
    $userstatus1=$_REQUEST['userrole'];
    $companyname=$_REQUEST['companyname'];
    $contactperson=$_REQUEST['contactperson'];
    $nricno=$_REQUEST['nric_no'];
    $tempuld=$_REQUEST['uid'];
    $attach_file_name=$_FILES['fileToUpload']['name'];
    $uploadpath=$dir.'images'.DIRECTORY_SEPARATOR;
    move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$uploadpath.$attach_file_name);
   if($tempuld=="")
   {
       $insertquery="INSERT INTO JP_USER_LOGIN_DETAILS(ULD_USERNAME,ULD_PASSWORD,ULD_COMPANY_NAME,ULD_CONTACT_PERSON,ULD_EMAIL,ULD_NRICNO,RC_ID,ULD_USERSTAMP_ID,ULD_IMAGE_NAME) VALUES('$username','$password','$companyname','$contactperson','$useremail','$nricno',(SELECT RC_ID FROM JP_ROLE_CREATION WHERE RC_NAME='$userstatus1'),'$uld_id','$attach_file_name')";
       mysqli_query($connection,$insertquery);
   }
    else
    {
        if($attach_file_name=="")
        {
        $updatequery="UPDATE JP_USER_LOGIN_DETAILS SET ULD_USERNAME='$username',ULD_PASSWORD='$password',ULD_COMPANY_NAME='$companyname',ULD_CONTACT_PERSON='$contactperson',ULD_EMAIL='$useremail',RC_ID=(SELECT RC_ID FROM JP_ROLE_CREATION WHERE RC_NAME='$userstatus1'),ULD_USERSTAMP_ID='$uld_id',ULD_NRICNO='$nricno' WHERE ULD_ID='$tempuld'";
        }
        else
        {
        $updatequery="UPDATE JP_USER_LOGIN_DETAILS SET ULD_USERNAME='$username',ULD_PASSWORD='$password',ULD_COMPANY_NAME='$companyname',ULD_CONTACT_PERSON='$contactperson',ULD_EMAIL='$useremail',RC_ID=(SELECT RC_ID FROM JP_ROLE_CREATION WHERE RC_NAME='$userstatus1'),ULD_USERSTAMP_ID='$uld_id',ULD_IMAGE_NAME='$attach_file_name',ULD_NRICNO='$nricno' WHERE ULD_ID='$tempuld'";
        }
        mysqli_query($connection,$updatequery);
    }
    $tablerecords=UserTable();
    echo JSON_encode($tablerecords);
}
elseif($_REQUEST["Option"]=='Delete')
{
    $Rowid=$_REQUEST['Data'];
    $deletequery="DELETE FROM JP_USER_LOGIN_DETAILS WHERE ULD_ID='$Rowid'";
    mysqli_query($connection,$deletequery);
    $tablerecords=UserTable();
    echo JSON_encode($tablerecords);
}
elseif($_REQUEST["Option"]=='Usercheck')
{
    $username=$_REQUEST["User"];
    $ulduserquery="SELECT ULD_USERNAME FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME=BINARY('$username')";
    $select = $connection->query($ulduserquery);
    $uldusername='';
    if($record=mysqli_fetch_array($select))
    {
        $uldusername= $record['ULD_USERNAME'];
    }
    echo $uldusername;
}
function UserTable()
{
    global $connection;
    $selectquery="SELECT ULD_ID,ULD_USERNAME,ULD_PASSWORD,ULD_COMPANY_NAME,ULD_CONTACT_PERSON,ULD_EMAIL,ULD_NRICNO,RC.RC_NAME,ULD_IMAGE_NAME FROM JP_USER_LOGIN_DETAILS ULD,JP_ROLE_CREATION RC WHERE ULD.RC_ID=RC.RC_ID ORDER BY ULD_USERNAME ASC";
    $userrecord=mysqli_query($connection,$selectquery);
    $record=mysqli_num_rows($userrecord);
    $appendTable ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head'><th style='text-align:center'>ACTION</th><th style='text-align:center'>USERNAME</th><th style='text-align:center'>PASSWORD</th><th style='text-align:center'>COMPANY NAME</th><th style='text-align:center'>CONTACT PERSON</th><th style='text-align:center'>EMAIL</th><th style='text-align:center'>NRIC NO</th><th style='text-align:center'>ROLE</th><th style='text-align:center'>IMAGE</th></tr></thead><tbody>";
    $y=$record;
    while($record = mysqli_fetch_array($userrecord))
    {
        $editrowid="EditRow/".$record[0].'/'.$record[8];
        $deleterowid="DeleterRow/".$record[0];
        $appendTable .="<tr id='$record[0]'>";
        $appendTable .="<td style='text-align:center'><a href='#' id='$editrowid' class='Edit' title='Edit'><span class='glyphicon glyphicon-pencil'></span></a><a href='#' id=$deleterowid class='Delete' title='Delete'><span class='glyphicon glyphicon-trash' style='color:red'></span></a></td>";
        for($y = 1; $y < 8; $y++)
        {
            $appendTable .="<td style='text-align:center;font-size: 14px !important;'>".$record[$y]."</td>";
        }
        $imageurl="images/".$record[8];
        if($record[8]!="")
        {
        $appendTable .="<td style='text-align:center;font-size: 12px !important;'><img src=$imageurl style='max-width: 50px;max-height: 50px'></td>";
        }
        else
        {
            $appendTable .="<td style='text-align:center;font-size: 12px !important;'></td>";
        }
        $appendTable .="</tr>";
    }
    $appendTable .="</tbody></table>";
    return $appendTable;
}