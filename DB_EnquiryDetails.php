<?php
error_reporting(0);
require_once("session.php");
include "connection.php";
require_once('mpdf571/mpdf571/mpdf.php');
require 'PHPMailer-master/PHPMailerAutoload.php';
$session_id=$login_session;
include "DB_Error_Msg.php";
$emailtemplate=emailtempaltedetails();
if($_POST['flag']==1)
{
    $date=$_POST['date'];

    $select_option="SELECT UED_ENQUIRY_ID FROM jp_user_enquiry_details WHERE UED_DATE='$date' AND ULD_ID=(SELECT ULD_ID FROM jp_user_login_details WHERE ULD_USERNAME='$login_session') AND ES_ID=1 ";

    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    while($record=mysqli_fetch_array($sql)){
        $data=$record[0];
    }
    echo $data;

}
if($_POST["Option"]=="Insert")
{
$product=$_POST["EnquiryDetails"];
    $eqdate=$_POST["Date"];
    $product_id;$product_name;$product_desc;
    if($product!='null')
    {
        for($i=0;$i<count($product);$i++)
        {
            if($i==0)
            {
                $product_id=$product[$i][0]; $product_name=$product[$i][1];$product_desc=$product[$i][2];
            }
            else
            {
                $product_id=$product_id.','.$product[$i][0]; $product_name=$product_name.'^~'.$product[$i][1];$product_desc=$product_desc.'^~'.$product[$i][2];
            }
        }
    }
   $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(1,NULL,'$login_session','$eqdate','NEW QUOTATION','$product_name',NULL,'$product_desc',
    NULL,'$login_session',@SUCCESS_FLAG,@ENQUIRYID,@DATE)";
    $result = $connection->query($callquery);
    if(!$result)
    {
        die("CALL failed: (" . $con->errno . ") " . $connection->error);
    }
    $select = $connection->query('SELECT @SUCCESS_FLAG');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    $date = $connection->query('SELECT @DATE');
    $result1 = $date->fetch_assoc();
    $date= $result1['@DATE'];
    $eqid = $connection->query('SELECT @ENQUIRYID');
    $result2 = $eqid->fetch_assoc();
    $eq_id= $result2['@ENQUIRYID'];
    if($flag==1)
    {
    $sessionname=strtoupper($login_session);
    $emailmessage=str_replace("[USERNAME]",$sessionname,$emailtemplate[0][1]);
    $emailmessage=str_replace("[ENQDATE]",$date,$emailmessage);
    $emailmessage=str_replace("[ENQID]",$eq_id,$emailmessage);
    $email_details=emaildetails();
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = $email_details[8][0];
    $mail->SMTPAuth = true;
    $mail->Username = $email_details[5][0];
    $mail->Password = $email_details[6][0];
    $mail->SMTPSecure = $email_details[7][0];
    $mail->From = $email_details[0][0];
    $mail->FromName = 'J-PRINT';
    $mail->addAddress($email_details[1][0]);
    $mail->WordWrap = 50;
    $mail->isHTML(true);
    $mail->Subject =$emailtemplate[0][0];
    $mail->Body =$emailmessage;
    $mail->Send();
    }
    echo $flag;
}
if($_POST["Option"]=="Update")
{
    $product=$_POST["EnquiryDetails"];
    $product_id;$product_name;$product_desc;
    if($product!='null')
    {
        for($i=0;$i<count($product);$i++)
        {
            if($i==0)
            {
                $product_id=$product[$i][0]; $product_name=$product[$i][1];$product_desc=$product[$i][2];
            }
            else
            {
                $product_id=$product_id.','.$product[$i][0]; $product_name=$product_name.'^~'.$product[$i][1];$product_desc=$product_desc.'^~'.$product[$i][2];
            }
        }
    }
    $callquery="call SP_ENQUIRY_INSERT_UPDATE(3,'$product_id, ',NULL,NULL,NULL,'$product_name',NULL,'$product_desc',NULL,'$login_session',@SUCCESS_FLAG,@ENQUIRYID,@DATE)";
    $result = $connection->query($callquery);
    if(!$result)
    {
        die("CALL failed: (" . $con->errno . ") " . $connection->error);
    }
    $select = $connection->query('SELECT @SUCCESS_FLAG');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    $returnvalue=Enquirylist();
    echo $returnvalue;
}
elseif($_POST["Option"]=="EnquiryList")
{

    $returnvalue=Enquirylist();
    echo $returnvalue;
}
elseif($_POST["Option"]=="AdminEnquiryList")
{
   $returnvalue=AdminEnquiryList();
    echo $returnvalue;
}
elseif($_REQUEST["Option"]=="AdminQuotation")
{
    $uedrowid=$_REQUEST["UEDID"];
    $id=$_REQUEST["UEDID"];
    $statusquery="SELECT ES.ES_STATUS FROM JP_USER_ENQUIRY_DETAILS UED,JP_ENQUIRY_STATUS ES WHERE UED_ID='$id' AND UED.ES_ID=ES.ES_ID";
    $select = $connection->query($statusquery);
    if($record=mysqli_fetch_array($select))
    {
        $status= $record['ES_STATUS'];
    }
    $status=strtoupper($status);
    $select_option="SELECT PD_ID,PRODUCT_NAME,PD_DESCRIPTION,PD_PRICE,UED_PRICE FROM jp_user_product_details UPD,JP_USER_ENQUIRY_DETAILS UED WHERE UPD.UED_ID='$uedrowid' AND UPD.UED_ID=UED.UED_ID";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable2 ="<table id='quotation_view' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center;max-width: 125px;'>PRODUCT</th><th style='text-align:center;max-width: 200px;'>DESCRIPTION</th><th style='text-align:center;width:150px'>PRICE($)</th></tr></thead><tbody>";
    $loopid=1;
    while($record=mysqli_fetch_array($sql))
    {
        $rowid="QT_".$loopid;
        $temprowid="QTtemp_".$loopid;
        $rowno=$record[0];
        $price=$record[3];
        $totalprice=$record[4];
        $appendTable2 .="<tr>";
        for($y = 1; $y < 4; $y++)
        {
            if($y==3)
            {
            if($price!="")
            {
           $appendTable2 .="<td><input type='text' value=$price class='Quotationprice decimal' id=$rowid  style='max-width:150px;border:0;text-align:right'>
           <input type='hidden' id=$temprowid value=$rowno style='max-width:50px;border:0;' value=></td>";
            }
            else
            {
           $appendTable2 .="<td><input type='text' class='Quotationprice decimal' id=$rowid  style='max-width:150px;border:0;text-align:right;'>
           <input type='hidden' id=$temprowid value=$rowno style='max-width:50px;border:0;'></td>";
            }
            }
            else
            {
            $appendTable2 .="<td style='text-align:left'>".$record[$y]."</td>";
            }
        }
        $appendTable2 .="</tr>";
        $loopid++;
    }
    $appendTable2 .="<td></td><td style='text-align:right;font-weight: bold;margin-right: 50px'>TOTAL</td><td style='text-align:right;'><label id='quotationtotal' style='font-weight: bold;' >$totalprice</td></tr>";
    $appendTable2 .="</tbody></table>";

    $values=array($appendTable2,$totalprice,$status);
    echo JSON_encode($values);
}
elseif($_POST["Option"]=="AdminQuotationupdation")
{
 $priceupdatedata=$_POST["Data"];
  $status=$_POST["Status"];
     $pd_id;$product_price;
    if($product!='null')
    {
        for($i=0;$i<count($priceupdatedata);$i++)
        {
            if($i==0)
            {
                $pd_id=$priceupdatedata[$i][0]; $product_price=$priceupdatedata[$i][1];
            }
            else
            {
                $pd_id=$pd_id.','.$priceupdatedata[$i][0]; $product_price=$product_price.'^'.$priceupdatedata[$i][1];
            }
        }
    }
    if($status==1)
    {
    $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(2,'$pd_id',' ',NULL,'Conformed Quotation',' ','$product_price',' ',' ','$login_session',@SUCCESS_FLAG,@ENQUIRYID,@ENQUIRYDATE)";
    }
    elseif($status==4)
    {
        $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(4,'$pd_id',' ',' ','Revised Quotation',' ','$product_price',' ',' ','$login_session',@SUCCESS_FLAG,@ENQUIRYID,@ENQUIRYDATE)";
    }
    $result = $connection->query($callquery);
    if(!$result)
    {
        die("CALL failed: (" . $con->errno . ") " . $connection->error);
    }
    $select = $connection->query('SELECT @SUCCESS_FLAG');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    $adminreturnvalue=AdminEnquiryList();
    echo $adminreturnvalue;
}
elseif($_POST["Option"]=="AdminQuotationList")
{
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(UED_DATE,'%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UED.UED_ENQUIRY_ID,QD_QUOTATION_ID,UED_PRICE,ES.ES_STATUS,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP FROM  JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1,JP_ENQUIRY_MAX_RECVER_UEDID VW WHERE  ES.ES_ID=UED.ES_ID AND ULD.ULD_ID=UED.ULD_ID AND ULD1.ULD_ID=UED.UED_USERSTAMP_ID AND QD.UED_ID=UED.UED_ID AND VW.UED_ID=UED.UED_ID  AND UED.ES_ID IN (2,3,5)";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable3 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center'>HISTORY</th><th style='text-align:center;max-width:75px;'>DATE</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center'>QUOTATION ID</th><th style='text-align:center'>PRICE</th><th style='text-align:center'>STATUS</th><th style='text-align:center'>USERSTAMP</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql))
    {
        $appendTable3 .="<tr id='$record[0]'>";
        $QT_view="QT_view/".$record[0];
        $EQ_viewid="Enquiryid/".$record[3];
        $appendTable3.="<td style='text-align:center'><a href='#' id=$EQ_viewid class='showalldetails'><span class='glyphicon glyphicon-plus-sign' title='ShowDetails' style='color:#73c20e;'></a></span></td>";
        for($y = 1; $y <8; $y++)
        {
            if($y==4)
            {
                $appendTable3 .="<td style='text-align:center'><a href='#'id=$QT_view class='QuotationView' >".$record[$y]."</a></td>";
            }
            else
            {
            $appendTable3 .="<td style='text-align:center'>".$record[$y]."</td>";
            }
        }
        $appendTable3 .="</tr>";
    }
    $appendTable3 .="</tbody></table>";
    echo $appendTable3;
}
elseif($_REQUEST["Option"]=="AdminLPQuotationView")
{
    $uedrowid=$_REQUEST["Data"];
    $id=$_REQUEST["Data"];
    $statusquery="SELECT ES.ES_STATUS FROM JP_USER_ENQUIRY_DETAILS UED,JP_ENQUIRY_STATUS ES WHERE UED_ID='$id' AND UED.ES_ID=ES.ES_ID";
    $select = $connection->query($statusquery);
    if($record=mysqli_fetch_array($select))
    {
        $status= $record['ES_STATUS'];
    }
    $status=strtoupper($status);
    $select_option="SELECT PRODUCT_NAME,PD_DESCRIPTION,PD_PRICE,UED_PRICE FROM jp_user_product_details UPD,JP_USER_ENQUIRY_DETAILS UED WHERE UPD.UED_ID='$uedrowid' AND UPD.UED_ID=UED.UED_ID";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable7 ="<table id='Allrecverdetails' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center;max-width: 300px;'>PRODUCT</th><th style='text-align:center;max-width: 400px;'>DESCRIPTION</th><th style='text-align:center;width:75px'>PRICE($)</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql))
    {
        $appendTable7 .="<tr>";
        $price=$record[3];
        for($y = 0; $y < 3; $y++)
        {
            if($y==2)
            {
                $appendTable7 .="<td style='text-align:right'>".$record[$y]."</td>";
            }
            else
            {
                $appendTable7 .="<td>".$record[$y]."</td>";
            }
        }

        $appendTable7 .="</tr>";
    }
    $appendTable7 .="<td></td><td style='text-align:right;font-weight: bold;'>TOTAL</td><td style='background-color:#FFE4B5;color:black;text-align:right;font-weight: bold;'>".$price."</td>";
    $appendTable7 .="</tbody></table>";
    $values=array($appendTable7,$status);
    echo JSON_encode($values);
}
elseif($_REQUEST["Option"]=="AdminQuotationView")
{
    $uedrowid=$_REQUEST["Data"];
    $id=$_REQUEST["Data"];
    $statusquery="SELECT ES.ES_STATUS FROM JP_USER_ENQUIRY_DETAILS UED,JP_ENQUIRY_STATUS ES WHERE UED_ID='$id' AND UED.ES_ID=ES.ES_ID";
    $select = $connection->query($statusquery);
    if($record=mysqli_fetch_array($select))
    {
        $status= $record['ES_STATUS'];
    }
    $status=strtoupper($status);
    $select_option="SELECT PRODUCT_NAME,PD_DESCRIPTION,PD_PRICE,UED_PRICE FROM jp_user_product_details UPD,JP_USER_ENQUIRY_DETAILS UED WHERE UPD.UED_ID='$uedrowid' AND UPD.UED_ID=UED.UED_ID";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable4 ="<table id='quotation_view' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center;max-width: 300px;'>PRODUCT</th><th style='text-align:center;max-width: 400px;'>DESCRIPTION</th><th style='text-align:center;width:75px'>PRICE($)</th></tr></thead><tbody>";
    $appendTable4 .="<tr>";
    while($record=mysqli_fetch_array($sql))
    {
        $price=$record[3];
        for($y = 0; $y < 3; $y++)
        {
           if($y==2)
           {
            $appendTable4 .="<td style='text-align:right'>".$record[$y]."</td>";
           }
            else
            {
            $appendTable4 .="<td>".$record[$y]."</td>";
            }
        }

        $appendTable4 .="</tr>";
    }
    $appendTable4 .="<td></td><td style='text-align:right;font-weight: bold;'>TOTAL</td><td style='background-color:#FFE4B5;color:black;text-align:right;font-weight: bold;'>".$price."</td>";
    $appendTable4 .="</tbody></table>";
    $valuearray=array($appendTable4,$status);
    echo JSON_encode($valuearray);
}
elseif($_POST["Option"]=="AdminROQuotationView")
{
    $uedrowid=$_POST["Data"];
    $select_option="SELECT PRODUCT_NAME,PD_DESCRIPTION,PD_PRICE,UED_PRICE FROM jp_user_product_details UPD,JP_USER_ENQUIRY_DETAILS UED WHERE UPD.UED_ID='$uedrowid' AND UPD.UED_ID=UED.UED_ID";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable4 ="<table id='quotation_reorderview' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center;max-width:300px;'>PRODUCT</th><th style='text-align:center;max-width: 400px;'>DESCRIPTION</th><th style='text-align:center;width:75px'>PRICE($)</th></tr></thead><tbody>";
    $appendTable4 .="<tr>";
    while($record=mysqli_fetch_array($sql))
    {
        $price=$record[3];
        for($y = 0; $y < 3; $y++)
        {
            if($y==2)
            {
                $appendTable4 .="<td style='text-align:right'>".$record[$y]."</td>";
            }
            else
            {
                $appendTable4 .="<td style='text-align:left'>".$record[$y]."</td>";
            }
        }

        $appendTable4 .="</tr>";
    }
    $appendTable4 .="<td></td><td style='text-align:right;font-weight: bold;'>TOTAL</td><td style='background-color:#FFE4B5;color:black;text-align:right;font-weight: bold;'>".$price."</td>";
    $appendTable4 .="</tbody></table>";
    echo $appendTable4;
}
elseif($_REQUEST["option"]=="userenquirysearch")
{
    $uedrowid=$_REQUEST["Data"];
    $oldenquirydetails=mysqli_query($connection,"SELECT PD_ID,PRODUCT_NAME,PD_DESCRIPTION FROM JP_USER_PRODUCT_DETAILS WHERE UED_ID='$uedrowid'");
    while($row=mysqli_fetch_array($oldenquirydetails))
    {
        $oldenquiry_details[]=array($row["PD_ID"],$row["PRODUCT_NAME"],$row["PD_DESCRIPTION"]);
    }
    $values=array($oldenquiry_details);

    echo JSON_encode($oldenquiry_details);
}
elseif($_REQUEST["Option"]=="AdminNotificationList")
{
    $enquiryselect_option="SELECT UED.UED_ID,DATE_FORMAT(UED.UED_DATE,'%d-%m-%Y'), UCASE(ULD.ULD_USERNAME), UED.UED_ENQUIRY_ID, DATE_FORMAT(UED.UED_TIMESTAMP,'%d-%m-%Y') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND ES.ES_ID = 1 ORDER BY UED.UED_TIMESTAMP DESC LIMIT 10";
    $enquirysql=mysqli_query($connection,$enquiryselect_option);
    $enquiryrecord=mysqli_num_rows($enquirysql);
    $y=$enquiryrecord;
    $appendTablenotifi_enquiry ="<table id='example' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th style='text-align:center;'>DATE</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>VIEW</th></tr></thead><tbody>";
    $appendTablenotifi_enquiry .="<tr>";
    while($enquiryrecord=mysqli_fetch_array($enquirysql))
    {
       $rowid="VWEQ_ID/".$enquiryrecord[0];
        for($y = 1; $y < 4; $y++) {
            $appendTablenotifi_enquiry .="<td style='text-align: center'>".$enquiryrecord[$y]."</td>";
        }
        $appendTablenotifi_enquiry .="<td style='text-align: center'><a href='#'><span href='#' id=$rowid class='glyphicon glyphicon-eye-open enquiryviewdetails' title='ViewDetails' style='color:#73c20e;'></a></span></td>";
        $appendTablenotifi_enquiry .="</tr>";
    }
    $appendTablenotifi_enquiry .="</tbody></table>";

    $conformselect_option="SELECT UED.UED_ID,UCASE(ULD.ULD_USERNAME), QD.QD_QUOTATION_ID,DATE_FORMAT((SELECT DATE(UED.UED_TIMESTAMP)),'%d-%m-%Y')FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD, JP_QUOTATION_DETAILS QD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 3 ORDER BY UED.UED_TIMESTAMP DESC LIMIT 10;";
    $conformsql=mysqli_query($connection,$conformselect_option);
    $conformrecord=mysqli_num_rows($conformsql);
    $y=$conformrecord;
    $appendTablenotifi_conform ="<table id='example1' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th>USERNAME</th><th>QUOTATION ID</th><th>QUOTATION DATE</th><th>VIEW</th></tr></thead><tbody>";
    $appendTablenotifi_conform .="<tr>";
    while($conformrecord=mysqli_fetch_array($conformsql))
    {
        $row_id="VWQT_ID/".$conformrecord[0];
        for($y = 1; $y < 4; $y++) {
            $appendTablenotifi_conform .="<td style='text-align: center'>".$conformrecord[$y]."</td>";
        }
        $appendTablenotifi_conform .="<td style='text-align: center'><a href='#'><span href='#' id=$row_id class='glyphicon glyphicon-eye-open conformedviewdetails' title='ViewDetails' style='color:#73c20e;'></a></span></td>";
        $appendTablenotifi_conform .="</tr>";
    }
    $appendTablenotifi_conform .="</tbody></table>";

    $reorderselect_option="SELECT UED.UED_ID,UCASE(ULD.ULD_USERNAME), QD.QD_QUOTATION_ID,DATE_FORMAT((SELECT DATE(UED.UED_TIMESTAMP)),'%d-%m-%Y') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD, JP_QUOTATION_DETAILS QD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 4 ORDER BY UED.UED_TIMESTAMP DESC LIMIT 10;";
    $reordersql=mysqli_query($connection,$reorderselect_option);
    $reorderrecord=mysqli_num_rows($reordersql);
    if($reorderrecord!=0){
    $y=$reorderrecord;

    $appendTablenotifi_reorder ="<table id='example2' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th>USERNAME</th><th>QUOTATION ID</th><th>QUOTATION DATE</th><th>VIEW</th></tr></thead><tbody>";
    $appendTablenotifi_reorder .="<tr>";
    while($reorderrecord=mysqli_fetch_array($reordersql))
    {
        $row_id="VWQT_ID/".$reorderrecord[0];
        for($y = 1; $y < 4; $y++) {
            $appendTablenotifi_reorder .="<td style='text-align: center'>".$reorderrecord[$y]."</td>";
        }
        $appendTablenotifi_reorder .="<td style='text-align: center'><a href='#'><span href='#' id=$row_id class='glyphicon glyphicon-eye-open reorderviewdetails' title='ViewDetails' style='color:#73c20e;'></a></span></td>";
        $appendTablenotifi_reorder .="</tr>";
    }
    $appendTablenotifi_reorder .="</tbody></table>";
    }
    else{
        $appendTablenotifi_reorder="NO REORDERED QUOTATION AVAILABLE";
    }
    $values=array($appendTablenotifi_enquiry,$appendTablenotifi_conform,$appendTablenotifi_reorder);
    echo JSON_encode($values);

}
elseif($_POST["Option"]=="OrderUpdate")
{
$rowid=$_POST["Uedid"];
$callquery="UPDATE JP_USER_ENQUIRY_DETAILS SET ES_ID=3 WHERE UED_ID='$rowid';";
$result = $connection->query($callquery);
$returnvalue=Enquirylist();
echo $returnvalue;
}
elseif($_POST["Option"]=="RevisedQuotation")
{
    $rowid=$_POST["Uedid"];
    $callquery="SELECT UED_ID,ES_ID FROM jp_user_enquiry_details UED,JP_ENQUIRY_MAX_RECVER VW WHERE UED.UED_ENQUIRY_ID=(SELECT UED_ENQUIRY_ID FROM jp_user_enquiry_details WHERE UED_ID='$rowid') AND UED.UED_ENQUIRY_ID=VW.UED_ENQUIRY_ID AND UED.UED_REC_VER=VW.REC_VER";
    $select = $connection->query($callquery);
    if($record=mysqli_fetch_array($select))
    {
        $uedid= $record['UED_ID'];
    }
    $callquery="UPDATE JP_USER_ENQUIRY_DETAILS SET ES_ID=4 WHERE UED_ID='$uedid';";
    $result = $connection->query($callquery);
    $returnvalue=Enquirylist();
    echo $returnvalue;
}
elseif($_POST["Option"]=="AdminRevisedQuotationList")
{
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(UED.UED_DATE,'%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UED.UED_ENQUIRY_ID,QD.QD_QUOTATION_ID,UED.UED_PRICE,ES.ES_STATUS,UCASE(ULD.ULD_USERNAME)FROM JP_USER_ENQUIRY_DETAILS UED, JP_QUOTATION_DETAILS QD, JP_ENQUIRY_STATUS ES, JP_USER_LOGIN_DETAILS ULD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND UED.UED_ID = QD.UED_ID AND UED.QD_ID = QD.QD_ID AND UED.UED_USERSTAMP_ID = ULD.ULD_ID AND ES.ES_ID = 4";

    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable3 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center'>ACTION</th><th style='text-align:center'>DATE</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center'>QUOTATION ID</th><th style='text-align:center'>PRICE</th><th style='text-align:center'>STATUS</th><th style='text-align:center'>USERSTAMP</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql))
    {
        $appendTable3 .="<tr id='$record[0]'>";
        $QT_view="QT_view/".$record[0];
        $appendTable3.="<td style='text-align:center'><a href='#'><span class='glyphicon glyphicon-plus-sign' style='color:#73c20e;'></a></span></td>";
        for($y = 1; $y <8; $y++)
        {
                $appendTable3 .="<td style='text-align:center'>".$record[$y]."</td>";
        }
        $appendTable3 .="</tr>";
    }
    $appendTable3 .="</tbody></table>";
    echo $appendTable3;
}
elseif($_POST["Option"]=="AdminAllLPQuotationList")
{
    $EQ_id=$_POST["Data"];
//    echo $EQ_id;
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(UED_DATE,'%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UED.UED_ENQUIRY_ID,QD_QUOTATION_ID,UED_PRICE,ES.ES_STATUS,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP FROM JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1 WHERE  ES.ES_ID=UED.ES_ID AND  ULD.ULD_ID=UED.ULD_ID AND ULD1.ULD_ID=UED.UED_USERSTAMP_ID AND QD.UED_ID=UED.UED_ID AND UED.UED_ENQUIRY_ID='$EQ_id'";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable3 ="<table id='All_recverdetails' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center'>DATE</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center'>QUOTATION ID</th><th style='text-align:center'>PRICE</th><th style='text-align:center'>STATUS</th><th style='text-align:center'>USERSTAMP</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql))
    {
        $appendTable3 .="<tr id='$record[0]'>";
        $QT_view="QT_view/".$record[0];
        $EQ_viewid="Enquiryid/".$record[3];
//        $appendTable3.="<td style='text-align:center'><a href='#' id=$EQ_viewid class='showalldetails'><span class='glyphicon glyphicon-plus-sign' title='ShowDetails' style='color:#73c20e;'></a></span></td>";
        for($y = 1; $y <8; $y++)
        {
            if($y==4)
            {
                $appendTable3 .="<td style='text-align:center'><a href='#'id=$QT_view class='LPQuotationView' >".$record[$y]."</a></td>";
            }
            else
            {
                $appendTable3 .="<td style='text-align:center'>".$record[$y]."</td>";
            }
        }
        $appendTable3 .="</tr>";
    }
    $appendTable3 .="</tbody></table>";
    echo $appendTable3;
}
function Enquirylist()
{
    global $connection;
    global $session_id;
    $select_option=" SELECT UED.UED_ID,DATE_FORMAT(UED_DATE,'%d-%m-%Y'),UED_ENQUIRY_ID,QD.QD_QUOTATION_ID,ES.ES_STATUS,GROUP_CONCAT(PRODUCT_NAME),GROUP_CONCAT(PD_DESCRIPTION) FROM JP_USER_ENQUIRY_DETAILS UED LEFT JOIN JP_QUOTATION_DETAILS QD ON UED.UED_ID=QD.UED_ID,jp_user_product_details UPD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD WHERE UED.ULD_ID=(SELECT ULD_ID FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$session_id') AND ULD.ULD_ID=UED.ULD_ID AND ES.ES_ID=UED.ES_ID AND UED.UED_ID=UPD.UED_ID GROUP BY UPD. UED_ID";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center;'>DATE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>QUOTATION ID</th><th style='text-align:center;'>STATUS</th><th style='text-align:center;'>PRODUCT</th><th style='text-align:center;'>DESCRIPTION</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        $id="EQ_id/".$record[0];
        for($y = 1; $y <7; $y++)
        {
            if($y==3)
            {
                $appendTable1 .="<td style='text-align:center'><a href='#'id=$id class='userquotationview'>".$record[$y]."</a></td>";
            }
            elseif($y==2)
            {
                if($y==2 && $record[3]=="")
                {
                    $appendTable1 .="<td style='text-align:center;'><a href='#'id=$id class='userenquiryview'>".$record[$y]."</a></td>";
                }
                else
                {
                    $appendTable1 .="<td style='text-align:center;'>".$record[$y]."</td>";
                }
            }
            elseif($y==5 || $y==6)
            {
                if($record[$y]!=null){
                    $body='';
                    $body_msg =explode(',', $record[$y]);
                    for($l=0;$l<count($body_msg);$l++)
                    {
                        $rowid=$l+1;
                        if($l==0){$body=$rowid.'.'.$body_msg[$l].'<br>';}
                        else
                        {$body=$body.''.$rowid.'.'.$body_msg[$l].'<br>';}
                    }
                    $appendTable1 .="<td>".$body."</td>";
                }
            }
            else
            {
                $appendTable1 .="<td style='text-align:center'>".$record[$y]."</td>";
            }
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    return $appendTable1;
}
function AdminEnquiryList()
{
    global $connection;
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(UED_DATE,'%d-%m-%Y'),UCASE(ULD_USERNAME),UED.UED_ENQUIRY_ID,ES.ES_STATUS,GROUP_CONCAT(PRODUCT_NAME),GROUP_CONCAT(PD_DESCRIPTION) FROM JP_USER_ENQUIRY_DETAILS UED,jp_user_product_details UPD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_ENQUIRY_MAX_RECVER_UEDID VW WHERE  ULD.ULD_ID=UED.ULD_ID AND ES.ES_ID=UED.ES_ID AND UED.UED_ID=UPD.UED_ID AND VW.UED_ID=UED.UED_ID  AND UPD.PD_REC_VER=UED.UED_REC_VER AND UED.ES_ID IN(1,4) GROUP BY UPD.UED_ID";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center'>DATE</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center'>STATUS</th><th style='text-align:center'>PRODUCT</th><th style='text-align:center'>DESCRIPTION</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        for($y = 1; $y < 7; $y++)
        {
            if($y==3)
            {
                $Eqid="Enquiryid/".$record[0];
                $appendTable1 .="<td style='text-align:center'><a href='#' id=$Eqid class='Quotationcreation'>".$record[$y]."</a></td>";
            }
            elseif($y==2)
            {
                $appendTable1 .="<td style='text-align:center'>".ucfirst($record[$y])."</td>";
            }
            elseif($y==5 || $y==6)
            {
                if($record[$y]!=null){
                    $body='';
                    $body_msg =explode(',', $record[$y]);
                    for($l=0;$l<count($body_msg);$l++)
                    {
                        $rowid=$l+1;
                        if($l==0){$body=$rowid.'.'.$body_msg[$l].'<br>';}
                        else
                        {$body=$body.''.$rowid.'.'.$body_msg[$l].'<br>';}
                    }
                    $appendTable1 .="<td>".$body."</td>";
                }
            }
            else
            {
                $appendTable1 .="<td style='text-align:center'>".$record[$y]."</td>";
            }
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    return $appendTable1;
}


