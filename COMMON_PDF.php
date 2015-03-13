<?php
require_once("session.php");
include "connection.php";
require_once('mpdf571/mpdf571/mpdf.php');
$ued_rowid=$_GET["inputValOne"];
$dateselectcallquery="SELECT DATE_FORMAT(UED_DATE,'%d-%m-%Y')as UEDDATE ,UED_ENQUIRY_ID FROM JP_USER_ENQUIRY_DETAILS WHERE UED_ID='$ued_rowid'";
$select = $connection->query($dateselectcallquery);
if($record=mysqli_fetch_array($select))
{
    $date= $record['UEDDATE'];
    $eq_id=$record['UED_ENQUIRY_ID'];
}
$uedrowid=$_GET["inputValOne"];
$select_option="SELECT PRODUCT_NAME,PD_DESCRIPTION,PD_PRICE FROM jp_user_product_details WHERE UED_ID='$uedrowid'";
$sql=mysqli_query($connection,$select_option);
$record=mysqli_num_rows($sql);
$y=$record;
$appendTablepdf ="<table style='width:700px;border-collapse: collapse;border: 1px solid black;'><thead style='table-layout: fixed; word-wrap: break-word ; color:white;background-color:#FF8C00;'><tr><th style='text-align:center;max-width: 125px;color:white;background-color:#FF8C00;border: 1px solid black;'>PRODUCT</th><th style='text-align:center;max-width: 200px;color:white;background-color:#FF8C00;border: 1px solid black;'>DESCRIPTION</th><th style='text-align:center;width:150px;color:white;background-color:#FF8C00;border: 1px solid black;'>PRICE($)</th></tr></thead><tbody>";
while($record=mysqli_fetch_array($sql))
{
    $appendTablepdf .="<tr>";
    for($y = 0; $y < 3; $y++) {

        $appendTablepdf .="<td style='text-align:center;border: 1px solid black;'>".$record[$y]."</td>";
    }

    $appendTablepdf .="</tr>";
}
$appendTablepdf .="</tr>";
$appendTablepdf .="</tbody></table>";
$id="<table style='width:700px;'><tr><td><img src='http://localhost/JPRINT/images/logo.png'/></td><td align='right'><label>ENQUIRY ID:</label><label>$eq_id</label><br/><label>DATE:</label><label>$date</label></td></tr></table>";
$mpdf=new mPDF('utf-8','A4');
$mpdf->WriteHTML($image);
$mpdf->WriteHTML($id);
$mpdf->debug=true;
$mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
$mpdf->WriteHTML($appendTablepdf);
$mpdf->Output('Quotation.pdf','D');
?>