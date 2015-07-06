<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("session.php");
include "connection.php";
require_once('mpdf571/mpdf571/mpdf.php');
$ued_rowid=$_GET["inputValOne"];

$dateselectcallquery="SELECT DATE_FORMAT(UED_DATE,'%d-%m-%Y')as UEDDATE ,QD_QUOTATION_ID,UED.ULD_ID,JED.ES_STATUS,UED.UED_PURCHASE_ORDER_NO FROM JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD,JP_ENQUIRY_STATUS JED WHERE UED.QD_ID = QD.QD_ID AND JED.ES_ID=UED.ES_ID AND UED.UED_ID=".$ued_rowid;
$select = $connection->query($dateselectcallquery);
if($record=mysqli_fetch_array($select))
{
    $q_status=strtoupper($record['ES_STATUS']);
    $date= $record['UEDDATE'];
    $eq_id=$record['QD_QUOTATION_ID'];
    $uld_id=$record['ULD_ID'];
    $POS_NO=$record['UED_PURCHASE_ORDER_NO'];
}
$userselectcallquery="SELECT UCASE(ULD_USERNAME)AS USER,ULD_COMPANY_NAME,ULD_CONTACT_PERSON FROM JP_USER_LOGIN_DETAILS WHERE ULD_ID='$uld_id'";
$select1 = $connection->query($userselectcallquery);
if($record1=mysqli_fetch_array($select1))
{
    $companyname= $record1['ULD_COMPANY_NAME'];
    $contactperson=$record1['ULD_CONTACT_PERSON'];
    $user_name=$record1['USER'];
}
$clientselectcallquery="SELECT *FROM JP_CLIENT_DETAILS WHERE CD_ID=1";
$select2 = $connection->query($clientselectcallquery);
if($record2=mysqli_fetch_array($select2))
{
    $companyaddress= $record2['CD_COMPANY_ADDRESS'];
    $contact_no=$record2['CD_CONTACT_NO'];
    $quotationheader=strtoupper($record2['CD_QUOTATION_HEADER']);
}
if($q_status=='CANCELLED')
{
    $q_status='CANCELLED ORDER';
}
elseif($q_status=='DELIVERED')
{
    $q_status='DELIVERED ORDER';
}
else
{
    $q_status=$q_status;
}
if($q_status=='CANCELLED ORDER' || $q_status=='QUOTATION UPDATED')
{
   $PON='';
   $POS_NO='';
   $space='';
}
else
{
    $space=':';
    $PON='PURCHASE ORDER NO';
    $POS_NO=$POS_NO;
}
$uedrowid=$_GET["inputValOne"];
$select_option="SELECT *FROM VW_USER_PRODUCT_DETAILS WHERE UED_ID=".$ued_rowid;
$sql=mysqli_query($connection,$select_option);
if($record3=mysqli_fetch_array($sql))
{
  $jobtitle=$record3['PD_JOB_TITLE'];
  $typeofprint=$record3['PRODUCT_NAME'];if($typeofprint=='' || $typeofprint==null){$typeofprint='-';}
  $Size=$record3['SIZE'];if($Size=='' || $Size==null){$Size='-';}
  $Papertype=$record3['PAPER_TYPE'];if($Papertype=='' || $Papertype==null){$Papertype='-';}
  $Paperweight=$record3['PAPER_WEIGHT'];if($Paperweight=='' || $Paperweight==null){$Paperweight='-';}
  $Printingmethod=$record3['PRINTING_METHOD'];if($Printingmethod=='' || $Printingmethod==null){$Printingmethod='-';}
  $Printingprocess=$record3['PRINTING_PROCESS'];if($Printingprocess=='' || $Printingprocess==null){$Printingprocess='-';}
  $Treatmentprocess=$record3['TREATMENT_PROCESS'];if($Treatmentprocess=='' || $Treatmentprocess==null){$Treatmentprocess='-';}
  $Finishingprocess=$record3['FINISHING_PROCESS'];if($Finishingprocess=='' || $Finishingprocess==null){$Finishingprocess='-';}
  $Bindingprocess=$record3['BINDING_PROCESS'];if($Bindingprocess=='' || $Bindingprocess==null){$Bindingprocess='-';}
  $Quantity=$record3['PD_QUANTITY'];if($Quantity=='' || $Quantity==null){$Quantity='-';}
  $Location=$record3['PD_DELIVERY_LOC'];if($Location=='' || $Location==null){$Location='-';}
  $Requireddate=$record3['PD_REQUIRED_DATE'];if($Requireddate=='' || $Requireddate=='0000-00-00'){$Requireddate='-';}
  $Description=$record3['PD_DESCRIPTION'];if($Description=='' || $Description==null){$Description='-';}
  $Price=$record3['PD_PRICE'];if($Price=='' || $Price==null){$Price='-';}
}

$pdfname='JHUB-'.$q_status.'-'.$eq_id;
$appendTable='<html><body><table width="1000px"><tr><td style="text-align: center"><img src="images/JHUB.png" height="70px" width="250px"/></tr>
<tr><td style="text-align: center">'.$companyaddress.'</td></tr>
<tr><td style="text-align: center">Contact No : '.$contact_no.'</td></tr>
<hr/></table>';

$appendTable.='<table cellpadding="10" style="padding-left:5px;width:1000px">
<tr><td style="width:200px;color:green;"><h4><b><u>'.$q_status.'</u></b></h4></td>
<tr><td style="width:200px;"><b>USER NAME</b></td><td>:</td><td style="width:200px;">'.$user_name.'</td><td style="width:175px;"><b>QUOTATION ID</b></td><td>:</td><td style="width:150px;">'.$eq_id.'</td></tr>
<tr><td style="width:200px;"><b>DATE</b></td><td>:</td><td style="width:200px;">'.$date.'</td><td style="width:175px;"><b>COMPANY NAME</b></td><td>:</td><td style="width:150px;">'.$companyname.'</td></tr>
<tr><td style="width:200px;"><b>CONTACT PERSON</b></td><td>:</td><td style="width:200px;">'.$contactperson.'</td><td style="width:175px;"><b>'.$PON.'</b></td><td>'.$space.'</td><td style="width:150px;">'.$POS_NO.'</td></tr>
<tr><td style="width:250px;color:green;"><h4><b><u>'.$quotationheader.'</u></b></h4></td>
<tr><td style="width:250px;"><b>PROJECT TITLE</td><td style="width:50px">:</td><td>'.$jobtitle.'</td></tr>
<tr><td style="width:250px;"><b>TYPE OF PRINT</td><td style="width:50px">:</td><td>'.$typeofprint.'</td></tr>
<tr><td style="width:250px;"><b>SIZE</td><td style="width:50px">:</td><td>'.$Size.'</td></tr>
<tr><td style="width:250px;"><b>PAPER TYPE</td><td style="width:50px">:</td><td>'.$Papertype.'</td></tr>
<tr><td style="width:250px;"><b>PAPER WEIGHT</td><td style="width:50px">:</td><td>'.$Paperweight.'</td></tr>
<tr><td style="width:250px;"><b>PRINTING METHOD</td><td style="width:50px">:</td><td>'.$Printingmethod.'</td></tr>
<tr><td style="width:250px;"><b>PRINTING PROCESS</td><td style="width:50px">:</td><td>'.$Printingprocess.'</td></tr>
<tr><td style="width:250px;"><b>TREATMENT PROCESS</td><td style="width:50px">:</td><td>'.$Treatmentprocess.'</td></tr>
<tr><td style="width:250px;"><b>FINISHING PROCESS</td><td style="width:50px">:</td><td>'.$Finishingprocess.'</td></tr>
<tr><td style="width:250px;"><b>BINDING PROCESS</td><td style="width:50px">:</td><td>'.$Bindingprocess.'</td></tr>
<tr><td style="width:250px;"><b>QUANTITY</td><td style="width:50px">:</td><td>'.$Quantity.'</td></tr>
<tr><td style="width:250px;"><b>DATE REQUIRED</td><td style="width:50px">:</td><td>'.$Requireddate.'</td></tr>
<tr><td style="width:250px;"><b>DELIVERY LOCATION</td><td style="width:50px">:</td><td>'.$Location.'</td></tr>
<tr><td style="width:250px;"><b>REMARKS/SPECIAL REQUEST</td><td style="width:50px">:</td><td>'.$Description.'</td></tr>
<tr><td style="width:250px;"><b>PRICE</td><td style="width:50px">:</td><td>'.$Price.'</td></tr>
</table></body></html>';
$mpdf=new mPDF('utf-8','A4');
$mpdf->WriteHTML($appendTable);
$mpdf->debug=true;
$mpdf->SetHTMLFooter('<div style="text-align: right;padding-bottom:60px;padding-right:100px"><b>SIGNATURE</b></div>');
$mpdf->Output($pdfname.'.pdf','D');
?>
