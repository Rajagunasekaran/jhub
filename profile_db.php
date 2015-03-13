<?php
error_reporting(0);
require_once("session.php");
include "connection.php";

if($_POST['flag']==4)
{
    $select_option="SELECT RC_ID,RC_NAME FROM jp_role_creation";
    $sql=mysqli_query($connection,$select_option);
    while($row = mysqli_fetch_array($sql)) {
        $sid=$row['RC_ID'];
        $sname=$row['RC_NAME'];
        $data[]=array($sid,$sname);
    }
    echo json_encode($data);
}
if($_POST['flag']==1)
{
    $select_option="SELECT ULD.ULD_ID,ULD.ULD_USERNAME,ULD.ULD_PASSWORD,ULD.ULD_EMAIL,RC.RC_NAME FROM jp_user_login_details ULD, jp_role_creation RC WHERE RC.RC_ID=ULD.RC_ID";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor' ><tr class='head'><th style='width: 5px'>ACTION</th><th>USERNAME</th><th>PASSWORD</th><th>EMAIL</th><th>ROLE</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        $appendTable1 .="<td style='max-width: 150px' id='$record[0]' class='ajaxedit'><div class='col-lg-1'><span style='display: block' class='glyphicon glyphicon-edit product_editbutton' id='editid'></div></td>";
        for($y = 1; $y < 5; $y++) {
            $appendTable1 .="<td>".$record[$y]."</td>";
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    echo $appendTable1;
}
if($_POST['flag']==2)
{
    $username=$_POST['username'];
    $password=$_POST['password'];
    $useremail=$_POST['useremail'];
    $userstatus=$_POST['userstatus'];
    // Selecting Database
    $userstamp="SELECT ULD_USERSTAMP_ID FROM jp_user_login_details WHERE ULD_USERNAME='kumar'";
    $userstamp_query=mysqli_query($connection,$userstamp);
    $record1=mysqli_num_rows($userstamp_query);
    $x=$record1;
    while($record1=mysqli_fetch_array($userstamp_query)){
        $us=$record1[0];
    }
    $insert="INSERT INTO jp_user_login_details (ULD_USERNAME,ULD_PASSWORD,ULD_EMAIL,RC_ID,ULD_USERSTAMP_ID)  VALUES ('$username','$password','$useremail',(select RC_ID from jp_role_creation where RC_NAME='$userstatus'),'$us')";
    $insert_query= mysqli_query($connection,$insert);
    $select = "SELECT ULD.ULD_ID,ULD.ULD_USERNAME,ULD.ULD_PASSWORD,ULD.ULD_EMAIL,RC.RC_NAME FROM jp_user_login_details ULD, jp_role_creation RC WHERE RC.RC_ID=ULD.RC_ID";
    $select_query=mysqli_query($connection,$select);
    $record=mysqli_num_rows($select_query);
    $y=$record;
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor' ><tr class='head'><th style='width: 5px'>ACTION</th><th>USERNAME</th><th>PASSWORD</th><th>EMAIL</th><th>ROLE</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($select_query)){
        $appendTable1 .="<tr id='$record[0]'>";
        $appendTable1 .="<td style='max-width: 150px' id='$record[0]' class='ajaxedit'><div class='col-lg-1'><span style='display: block' class='glyphicon glyphicon-edit product_editbutton' id='editid'></div></td>";
        for($y = 1; $y < 5; $y++) {
            $appendTable1 .="<td>".$record[$y]."</td>";
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    echo $appendTable1;
}
if($_POST['flag']==3)
{
    $uid=$_POST['uid'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $useremail=$_POST['useremail'];
    $userstatus=$_POST['userstatus'];
    $insert="UPDATE jp_user_login_details SET ULD_USERNAME= '$username', ULD_PASSWORD='$password', ULD_EMAIL='$useremail', RC_ID=(select RC_ID from jp_role_creation where RC_NAME='$userstatus') WHERE ULD_ID=$uid";
//    echo($insert); exit;
    $insert_query= mysqli_query($connection,$insert);
    $select = "SELECT ULD.ULD_ID,ULD.ULD_USERNAME,ULD.ULD_PASSWORD,ULD.ULD_EMAIL,RC.RC_NAME FROM jp_user_login_details ULD, jp_role_creation RC WHERE RC.RC_ID=ULD.RC_ID";
    $select_query=mysqli_query($connection,$select);
    $record=mysqli_num_rows($select_query);
    $y=$record;
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor' ><tr class='head'><th style='width: 5px'>ACTION</th><th>USERNAME</th><th>PASSWORD</th><th>EMAIL</th><th>ROLE</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($select_query)){
        $appendTable1 .="<tr id='$record[0]'>";
        $appendTable1 .="<td style='max-width: 150px' id='$record[0]' class='ajaxedit'><div class='col-lg-1'><span style='display: block' class='glyphicon glyphicon-edit product_editbutton' id='editid'></div></td>";
        for($y = 1; $y < 5; $y++) {
            $appendTable1 .="<td>".$record[$y]."</td>";
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    echo $appendTable1;
}