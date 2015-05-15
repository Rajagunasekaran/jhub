<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
error_reporting(0);
require_once("session.php");
include "connection.php";
$uldquery="SELECT ULD_ID FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$login_session'";
$select = $connection->query($uldquery);
if($record=mysqli_fetch_array($select))
{
    $uld_id= $record['ULD_ID'];
}
if($_REQUEST["Option"]=='EnquiryCheck')
{

    $enquirytitle=$_REQUEST['EnquiryTitle'];
    $Enquiryquery="SELECT ETI_PRODUCT_NAME FROM JP_ENQUIRY_TITLE WHERE ETI_PRODUCT_NAME='$enquirytitle'";
    $select = $connection->query($Enquiryquery);
    if($record=mysqli_fetch_array($select))
    {
        $enquiryname= $record['ETI_PRODUCT_NAME'];
    }
    echo $enquiryname;
}
elseif($_REQUEST["Option"]=='EnquiryInsert')
{
    $enquirytitle=$_REQUEST['EnquiryTitle'];
    $insertQuery="INSERT INTO JP_ENQUIRY_TITLE(ETI_PRODUCT_NAME,ULD_ID) VALUES('$enquirytitle','$uld_id')";
    mysqli_query($connection,$insertQuery);
    $productlist=EnquiryTitleTable();
    echo json_encode($productlist);
}
elseif($_REQUEST["Option"]=='EnquiryUpdate')
{
    $enquirytitle=$_REQUEST['EnquiryTitle'];
    $rowid=$_REQUEST['rowid'];
    $insertQuery="UPDATE JP_ENQUIRY_TITLE SET ETI_PRODUCT_NAME='$enquirytitle',ULD_ID='$uld_id' WHERE ETI_ID='$rowid'";
    mysqli_query($connection,$insertQuery);
    $productlist=EnquiryTitleTable();
    echo json_encode($productlist);
}
elseif($_REQUEST["Option"]=='EnquiryDelete')
{
    $rowid=$_REQUEST['Data'];
    $insertQuery="DELETE FROM JP_ENQUIRY_TITLE WHERE ETI_ID='$rowid'";
    mysqli_query($connection,$insertQuery);
    $productlist=EnquiryTitleTable();
    echo json_encode($productlist);
}
elseif($_REQUEST["Option"]=='ProductList')
{
    $productlist=EnquiryTitleTable();
    echo json_encode($productlist);
}
function EnquiryTitleTable()
{
    global $connection;
    $selectquery="SELECT ETI_ID,ETI_PRODUCT_NAME,UCASE(ULD.ULD_USERNAME),ETI_TIMESTAMP FROM JP_ENQUIRY_TITLE ET,JP_USER_LOGIN_DETAILS ULD WHERE ET.ULD_ID=ULD.ULD_ID ORDER BY ETI_PRODUCT_NAME ASC";
    $enquiryrecord=mysqli_query($connection,$selectquery);
    $record=mysqli_num_rows($enquiryrecord);
    $appendTable ="<table id='Enquiry_titlelist' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head'><th style='text-align:center;max-width:100px'>ACTION</th><th style='text-align:center;max-width:250px'>ENQUIRY TITLE</th><th style='text-align:center;max-width:150px'>USERSTAMP</th><th style='text-align:center;max-width:200px'>TIMESTAMP</th></tr></thead><tbody>";
    $y=$record;
    $i=1;
    while($record = mysqli_fetch_array($enquiryrecord))
    {
        $editrowid="EditRow/".$record[0]."/".$i;
        $deleterowid="DeleterRow/".$record[0];
        $appendTable .="<tr id='$i'>";
        $appendTable .="<td style='text-align:center'><a href='#' id='$editrowid' class='Edit' title='Edit'><span class='glyphicon glyphicon-pencil'></span></a><a href='#' id=$deleterowid class='Delete' title='Delete'><span class='glyphicon glyphicon-trash' style='color:red'></span></a></td>";
        for($y = 1; $y < 4; $y++)
        {
            $appendTable .="<td>".$record[$y]."</td>";
        }
         $appendTable .="</tr>";
        $i++;
    }
    $appendTable .="</tbody></table>";
    return $appendTable;
}
?>