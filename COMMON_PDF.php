<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("session.php");
include "connection.php";
require_once('mpdf571/mpdf571/mpdf.php');
$ued_rowid=$_GET["inputValOne"];

$dateselectcallquery="SELECT DATE_FORMAT(UED_DATE,'%d-%m-%Y')as UEDDATE ,QD_QUOTATION_ID,UED.ULD_ID FROM JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD WHERE UED.UED_ID='$ued_rowid' AND UED.QD_ID = QD.QD_ID";
$select = $connection->query($dateselectcallquery);
if($record=mysqli_fetch_array($select))
{
    $date= $record['UEDDATE'];
    $eq_id=$record['QD_QUOTATION_ID'];
    $uld_id=$record['ULD_ID'];
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
    $quotationheader=$record2['CD_QUOTATION_HEADER'];
}
$uedrowid=$_GET["inputValOne"];
$select_option="SELECT *FROM VW_USER_PRODUCT_DETAILS WHERE UED_ID='$uedrowid'";
//$select_option="SELECT ET.ETI_PRODUCT_NAME,PD_DIMENSION,PD_DESCRIPTION,PD_PRICE,UED_PRICE FROM jp_user_product_details UPD,JP_USER_ENQUIRY_DETAILS UED,JP_ENQUIRY_TITLE ET WHERE UPD.UED_ID='$uedrowid' AND UPD.UED_ID=UED.UED_ID AND ET.ETI_ID=UPD.ETI_ID";
$sql=mysqli_query($connection,$select_option);
$record=mysqli_num_rows($sql);
$y=$record;
$appendTablepdf ="<table cellspacing='3px' cellpadding='15px' style='width:700px;border-collapse: collapse;border: 1px solid black;'>
<thead style='table-layout: fixed; word-wrap: break-word ; color:#000000;' class='headercolor'>
<tr>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>JOB TILTE</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>ITEM</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>SIZE</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>PAPER TYPE</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>PAPER METHOD</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>PRINTING METHOD</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>PRINTING PROCESS</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>TREATMENT PROCESS</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>FINISHING PROCESS</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>BINDING PROCESS</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>QUANTITY</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>DATE REQUIRED</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>DELIVERY LOCATION</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>REMARKS</th>
<th style='text-align:center;color:#000000;background-color:#d3d3d3;border: 1px solid black;'>PRICE($)</th>
</tr>
</thead>
<tbody>";
while($record=mysqli_fetch_array($sql))
{
    $price=$record[17];
    $appendTablepdf .="<tr>";
    for($y = 2; $y <=16; $y++) {
        if($record[$y]=='0000-00-00')
        {
        $appendTablepdf .="<td style='text-align:center;border: 1px solid black;'></td>";
        }
        else{
        $appendTablepdf .="<td style='text-align:center;border: 1px solid black;'>".$record[$y]."</td>";
        }
     }

    $appendTablepdf .="</tr>";
}
$appendTablepdf .="<tr ><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td style='text-align:right;font-weight:bold'>TOTAL</td><td style='text-align:center;border: 1px solid black;font-weight:bold;background-color:#d3d3d3;'>$".$price."</td></tr>";

$appendTablepdf .="</tbody></table>";
$id="<table style='width:700px;' cellspacing='3px' cellpadding='15px' style='padding-left:-20px;'>
<tr ><td style='width:300px;'></td><td style='width:400px;padding-left: 200px;'><label> Contact  :".$contact_no."</label></td></tr>
<tr>
<td style='width:300px;border-bottom: 1px solid black'><img img src='images/JHUB.png' style='max-height:250px;max-width:250px;'/></td>
<td align='left' style='width:400px;padding-left:100px;font-size: 16px;font-weight: bold;border-bottom: 1px solid black'>
<label>".$companyaddress."</label>
</td></tr>
 <tr><td border-bottom: 1px spolid #000;></td>
  </tr>
</table><br><br>";
$header="<table><tr><td style='color:green;font-weight: bold;font-size: 15px'><u>".$quotationheader."</u></td></tr></table>";
$details="<table><tr>
<td><label color='green'>USER NAME</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;&nbsp;&nbsp;<label>$user_name</label></td>
</tr><br><br>
<tr>
<td><label  color='green'>QUOTATION ID</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;&nbsp;&nbsp;<label>$eq_id</label></td>
</tr><br><br>
<tr>
<td><label  color='green'>DATE</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;&nbsp;&nbsp;<label>$date</label></td>
</tr><br><br>
<tr>
<td><label color='green'>COMPANY NAME</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;&nbsp;&nbsp;<label>$companyname</label></td>
</tr><br><br>
<tr>
<td><label color='green'>CONTACT PERSON</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>:</label>&nbsp;&nbsp;&nbsp;&nbsp;<label>$contactperson</label></td>
</tr>
</table><br>";
$mpdf=new mPDF('utf-8','A4');
$mpdf->WriteHTML($image);
$mpdf->WriteHTML($id);
$mpdf->WriteHTML($details);
$mpdf->WriteHTML($header);
$mpdf->debug=true;
$mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
$mpdf->WriteHTML($appendTablepdf);
$mpdf->Output('Quotation.pdf','D');
?>