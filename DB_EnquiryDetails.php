
<?php
 //ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
error_reporting(0);
require_once("session.php");
include "connection.php";
require_once('mpdf571/mpdf571/mpdf.php');
require 'PHPMailer-master/PHPMailerAutoload.php';
$session_id=$login_session;
include "DB_Error_Msg.php";
$dir=dirname(__FILE__).DIRECTORY_SEPARATOR;
if($_REQUEST['Option']=='UserDetails')
{
    $select_option="SELECT ULD_COMPANY_NAME,ULD_CONTACT_PERSON FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$session_id'";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    if($record=mysqli_fetch_array($sql)){
        $data=array($record['ULD_COMPANY_NAME'],$record['ULD_CONTACT_PERSON']);
    }
    $enquirydata=productnamelist();
    $values=array($data,$enquirydata);
    echo json_encode($values);
}
if($_POST["Option"]=="Insert")
{
    $job_title=$connection->real_escape_string($_POST["Job_tilte"]);
    $item=$_POST["Item"];    if($item=='SELECT'){$item='';} if($item=='Others'){$item=$connection->real_escape_string($_POST["Item_others"]);}
    $size=$_POST["Size"];if($size=='SELECT'){$size='';} if($size=='Custom'){$size=$connection->real_escape_string($_POST["Size_others"]);}
    $type=$_POST["Papertype"];if($type=='SELECT'){$type='';} if($type=='Others'){$type=$connection->real_escape_string($_POST["Papertype_others"]);}
    $weight=$_POST["Paperweight"];if($weight=='SELECT'){$weight='';};
    $method=$_POST["Printingmethod"];if($method=='SELECT'){$method='';};
    $printingprocess=$_POST["Printingprocess"];if($printingprocess=='SELECT'){$printingprocess='';} if($printingprocess=='Others'){$printingprocess=$connection->real_escape_string($_POST["Printingprocess_others"]);}
    $treatment=$_POST["Treatmentprocess"];if($treatment=='SELECT'){$treatment='';} if($treatment=='Others'){$treatment=$connection->real_escape_string($_POST["Treatmentprocess_others"]);}
    $finish_process=$_POST["Finishingprocess"];if($finish_process=='SELECT'){$finish_process='';}if($finish_process=='Others'){$finish_process=$connection->real_escape_string($_POST["Finishingprocess_others"]);}
    $binding=$_POST["Bindingprocess"];if($binding=='SELECT'){$binding='';} if($binding=='Others'){$binding=$connection->real_escape_string($_POST["Bindingprocess_others"]);}
    $Quantity=$_POST["Quantity"];
    $date=$_POST["EnquiryDate"];
    $Location=$connection->real_escape_string($_POST["DeliveryLocation"]);
    $Remarks=$connection->real_escape_string($_POST["Remarks"]);
    $uploadfiles=$_POST["files"];

    $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(1,NULL,'$login_session',curdate(),1,'$item',NULL,'$Remarks','$uploadfiles',NULL,'$job_title','$Location','$size','$type','$weight','$method',
    '$printingprocess','$treatment','$finish_process','$binding','$Quantity','$date',null,null,'$login_session',@SUCCESS_FLAG,@ENQ_ID,@ENQUIRY_DATE)";
    $result = $connection->query($callquery);
    if(!$result)
    {
        die("CALL failed: (" . $con->errno . ") " . $connection->error);
    }
    $select = $connection->query('SELECT @SUCCESS_FLAG');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    $date = $connection->query('SELECT @ENQUIRY_DATE');
    $result1 = $date->fetch_assoc();
    $date= $result1['@ENQUIRY_DATE'];
    $eqid = $connection->query('SELECT @ENQ_ID');
    $result2 = $eqid->fetch_assoc();
    $eq_id= $result2['@ENQ_ID'];
    if($flag==1)
    {
    $email_details=emaildetails();
    $emailtemplate=emailtempaltedetails();
    $sessionname=strtoupper($login_session);
    $emailmessage=str_replace("[USERNAME]",$sessionname,$emailtemplate[0][1]);
    $emailmessage=str_replace("[ENQDATE]",$date,$emailmessage);
    $emailmessage=str_replace("[ENQID]",$eq_id,$emailmessage);

    $email_details=emaildetails();
    $Username=$email_details[5][0];
    $Password=$email_details[6][0];
    $smtp=$email_details[7][0];
    $host=$email_details[8][0];
    $toaddress=$email_details[1][0];
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->Username = $Username;
    $mail->Password = $Password;
    $mail->SMTPSecure = $smtp;
    $mail->Port=587;
//    $mail->From = $email_details[0][0];
    $mail->FromName = 'JHUB';
    $mail->addAddress($toaddress);
    $mail->WordWrap = 50;
    $mail->isHTML(true);
    $mail->Subject =$emailtemplate[0][0];
    $mail->Body =$emailmessage;
    $mail->Send();
    }
    echo $flag;
}
elseif($_REQUEST["Option"]=="EnquiryList")
{
    $returnvalue=Enquirylist();
    $enquirydata=productnamelist();
    $values=array($returnvalue,$enquirydata);
    echo json_encode($values);
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
//    $select_option="SELECT PD_ID,ET.ETI_PRODUCT_NAME,PD_DIMENSION,PD_DESCRIPTION,PD_PRICE,UED_PRICE FROM JP_USER_PRODUCT_DETAILS UPD,JP_USER_ENQUIRY_DETAILS UED,JP_ENQUIRY_TITLE ET WHERE UPD.UED_ID='$uedrowid' AND UPD.UED_ID=UED.UED_ID AND ET.ETI_ID=UPD.ETI_ID";
    $select_option="SELECT *FROM VW_USER_PRODUCT_DETAILS WHERE UED_ID='$uedrowid'";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable2 ="<table id='quotation_view' style='width:2500px;' border=1 cellspacing='0' data-class='table'class='srcresult table '>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;width:100px;!important;'>JOB TITLE</th>
    <th style='text-align:center;width:120px;!important;'>TYPE OF PRINT</th>
    <th style='text-align:center;width:50px;!important;'>SIZE</th>
    <th style='text-align:center;width:100px;!important;'>PAPER TYPE</th>
    <th style='text-align:center;width:120px;!important;'>PAPER WEIGHT</th>
    <th style='text-align:center;width:150px;!important;'>PRINTING METHOD</th>
    <th style='text-align:center;width:150px;!important;'>PRINTING PROCESS</th>
    <th style='text-align:center;width:150px;!important;'>TREAMENT PROCESS</th>
    <th style='text-align:center;width:150px;!important;'>FINISHING PROCESS</th>
    <th style='text-align:center;width:130px;!important;'>BINDING PROCESS</th>
    <th style='text-align:center;width:90px;!important;'>QUANTITY</th>
    <th style='text-align:center;width:120px;!important;'>DATE REQUIRED</th>
    <th style='text-align:center;width:170px;!important;'>DELIVERY LOCATION</th>
     <th style='text-align:center;width:150px;!important;'>REMARKS</th>
    <th style='text-align:center;width:100px;!important;'>PRICE($)</th>
    </tr>
    </thead>
    <tbody>";
    $loopid=1;
    while($record=mysqli_fetch_array($sql))
    {
        $rowid="QT_".$loopid;
        $temprowid="QTtemp_".$loopid;
        $rowno=$record[1];
        $price=$record[16];
        $totalprice=$record[17];
        $appendTable2 .="<tr>";
        for($y = 2; $y <=16; $y++)
        {
            if($y==16)
            {
            if($price!="")
            {
           $appendTable2 .="<td><input type='text' value=$price class='numbersOnly Quotationprice decimal Quotationamountvalidation' id=$rowid maxlength='8'  style='border:0;text-align:right;font-size: 12px !important;'>
           <input type='hidden' id=$temprowid value=$rowno style='border:0;font-size: 12px !important;' value=></td>";
            }
            else
            {
           $appendTable2 .="<td><input type='text' class='numbersOnly Quotationprice decimal Quotationamountvalidation' id=$rowid maxlength='8' style='border:0;text-align:right;font-size: 12px !important;'>
           <input type='hidden' id=$temprowid  value=$rowno style='border:0;'></td>";
            }
            }
            elseif($y==13 && $record[$y]=='0000-00-00')
            {
                $appendTable2 .="<td> </td>";
            }
            else
            {
            $appendTable2 .="<td style='text-align:left;font-size: 14px !important;'>".$record[$y]."</td>";
            }
        }
        $appendTable2 .="</tr>";
        $loopid++;
    }
    $appendTable2 .="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td style='text-align:right;font-weight: bold;margin-right: 50px'>TOTAL</td><td style='text-align:right;'><label id='quotationtotal' style='font-weight: bold;font-size: 14px !important;padding-right: 40px;' >$totalprice</td></tr>";
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
    $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(2,'$pd_id',NULL,NULL,2,'NULL','$product_price',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$login_session',@SUCCESS_FLAG,@ENQUIRYID,@ENQUIRYDATE)";
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
    $tabledata=Quotationlist();
    echo $tabledata;
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
    $select_option="SELECT PRODUCT_NAME,PD_DESCRIPTION,PD_PRICE,UED_PRICE FROM JP_USER_PRODUCT_DETAILS UPD,JP_USER_ENQUIRY_DETAILS UED WHERE UPD.UED_ID='$uedrowid' AND UPD.UED_ID=UED.UED_ID";
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
                $appendTable7 .="<td style='text-align:right;font-size: 14px !important;'>".$record[$y]."</td>";
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
    $select_option="SELECT *FROM VW_USER_PRODUCT_DETAILS WHERE UED_ID='$uedrowid'";

    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable4 ="<table id='quotation_view' style='width:2500px;' border=1 cellspacing='0' data-class='table'class='srcresult table'>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;width:100px;!important;'>JOB TITLE</th>
    <th style='text-align:center;width:120px;!important;'>TYPE OF PRINT</th>
    <th style='text-align:center;width:50px;!important;'>SIZE</th>
    <th style='text-align:center;width:100px;!important;'>PAPER TYPE</th>
    <th style='text-align:center;width:120px;!important;'>PAPER WEIGHT</th>
    <th style='text-align:center;width:130px;!important;'>PRINTING METHOD</th>
    <th style='text-align:center;width:130px;!important;'>PRINTING PROCESS</th>
    <th style='text-align:center;width:150px;!important;'>TREAMENT PROCESS</th>
    <th style='text-align:center;width:150px;!important;'>FINISHING PROCESS</th>
    <th style='text-align:center;width:130px;!important;'>BINDING PROCESS</th>
    <th style='text-align:center;width:90px;!important;'>QUANTITY</th>
    <th style='text-align:center;width:120px;!important;'>DATE REQUIRED</th>
    <th style='text-align:center;width:150px;!important;'>DELIVERY LOCATION</th>
     <th style='text-align:center;width:150px;!important;'>REMARKS</th>
    <th style='text-align:center;width:100px;!important;'>PRICE($)</th>
    </tr>
    </thead>
    <tbody>";
    $appendTable4 .="<tr>";
    while($record=mysqli_fetch_array($sql))
    {
        $price=$record[17];
        for($y = 2; $y <=16; $y++)
        {
             if($y==16)
             {
              $appendTable4 .="<td style='text-align:right;font-size: 14px !important;'>".$record[16]."</td>";
             }
             elseif($y==13)
             {
               if($record[$y]=='0000-00-00')
               {
                   $appendTable4 .="<td style='text-align:right;font-size: 14px !important;'></td>";
               }
                 else
                 {
                     $appendTable4 .="<td style='text-align:right;font-size: 14px !important;'>".$record[13]."</td>";
                 }
             }
            else
            {
            $appendTable4 .="<td style='font-size: 14px !important;'>".$record[$y]."</td>";
            }
        }

        $appendTable4 .="</tr>";
    }
    $appendTable4 .="<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td style='text-align:right;font-weight: bold;'>TOTAL</td><td style='background-color:#FFE4B5;color:black;text-align:right;font-weight: bold;font-size: 14px !important;'>".$price."</td>";
    $appendTable4 .="</tbody></table>";
    $valuearray=array($appendTable4,$status);
    echo JSON_encode($valuearray);
}
elseif($_REQUEST["option"]=="userenquirysearch")
{
    $uedrowid=$_REQUEST["Data"];
//    $oldenquirydetails=mysqli_query($connection,"SELECT PD_ID,ETI_PRODUCT_NAME,PD_DIMENSION,PD_DESCRIPTION FROM JP_USER_PRODUCT_DETAILS UPD,JP_ENQUIRY_TITLE ET WHERE UED_ID='$uedrowid' AND ET.ETI_ID=UPD.ETI_ID");
    $oldenquirydetails=mysqli_query($connection,"SELECT *FROM VW_USER_PRODUCT_DETAILS WHERE UED_ID='$uedrowid'");

    while($row=mysqli_fetch_array($oldenquirydetails))
    {
        $oldenquiry_details[]=array($row["PD_ID"],$row["PD_JOB_TITLE"],$row["PRODUCT_NAME"],$row["SIZE"],
            $row["PAPER_TYPE"],$row["PAPER_WEIGHT"],$row["PRINTING_METHOD"],$row["PRINTING_PROCESS"],
            $row["TREATMENT_PROCESS"],$row["FINISHING_PROCESS"],$row["BINDING_PROCESS"],$row["PD_QUANTITY"],
            $row["PD_REQUIRED_DATE"],$row["PD_DELIVERY_LOC"],$row["PD_DESCRIPTION"]);
    }
    $oldimagedetails=mysqli_query($connection,"SELECT ULD_UPLOAD_IMG_NAME FROM JP_USER_ENQUIRY_DETAILS WHERE UED_ID='$uedrowid'");
    while($imagerow=mysqli_fetch_array($oldimagedetails))
    {
        $oldimage_details=$imagerow["ULD_UPLOAD_IMG_NAME"];
    }
    $imagelist=explode("/", $oldimage_details);
    $values=array($oldenquiry_details,$imagelist);

    echo JSON_encode($values);
}
elseif($_REQUEST["Option"]=="AdminNotificationList")
{
    $enquiryselect_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UPD.PD_JOB_TITLE,UED.UED_ENQUIRY_ID,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1,JP_USER_PRODUCT_DETAILS UPD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND ES.ES_ID = 1 AND ULD1.ULD_ID=UED.UED_USERSTAMP_ID AND UED.UED_ID=UPD.UED_ID ORDER BY UED.UED_TIMESTAMP DESC";
    $enquirysql=mysqli_query($connection,$enquiryselect_option);
    $enquiryrecord=mysqli_num_rows($enquirysql);
    $y=$enquiryrecord;
    $appendTablenotifi_enquiry ="<table id='example' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><th style='text-align:center;'>DATE REQUIRED</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>TIMESTAMP</th></tr></thead><tbody>";

    while($enquiryrecord=mysqli_fetch_array($enquirysql))
    {
        $appendTablenotifi_enquiry .="<tr>";
       $rowid="VWEQ_ID/".$enquiryrecord[0];
        for($y = 1; $y < 6; $y++) {
            $appendTablenotifi_enquiry .="<td>".$enquiryrecord[$y]."</td>";
        }
        $appendTablenotifi_enquiry .="</tr>";
    }
    $appendTablenotifi_enquiry .="</tbody></table>";

    $conformselect_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UPD.PD_JOB_TITLE,UED_ENQUIRY_ID, QD.QD_QUOTATION_ID,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1, JP_QUOTATION_DETAILS QD,JP_USER_PRODUCT_DETAILS UPD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID  AND UED.UED_USERSTAMP_ID=ULD1.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 3 AND UED.UED_ID=UPD.UED_ID ORDER BY UED.UED_TIMESTAMP DESC";
    $conformsql=mysqli_query($connection,$conformselect_option);
    $conformrecord=mysqli_num_rows($conformsql);
    $y=$conformrecord;
    $appendTablenotifi_conform ="<table id='example1' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th style='text-align:center;'>DATE REQUIRED</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>QUOTATION ID</th><th style='text-align:center;'>USERSTAMP</th><th style='text-align:center;'>TIMESTAMP</th></tr></thead><tbody>";
    while($conformrecord=mysqli_fetch_array($conformsql))
    {
        $appendTablenotifi_conform .="<tr>";
        $row_id="VWQT_ID/".$conformrecord[0];
        for($y = 1; $y < 8; $y++) {
            $appendTablenotifi_conform .="<td>".$conformrecord[$y]."</td>";
        }
        $appendTablenotifi_conform .="</tr>";
    }
    $appendTablenotifi_conform .="</tbody></table>";

    $reorderselect_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UPD.PD_JOB_TITLE,UED_ENQUIRY_ID, QD.QD_QUOTATION_ID,UCASE(ULD1.ULD_USERNAME)AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1, JP_QUOTATION_DETAILS QD,JP_USER_PRODUCT_DETAILS UPD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID  AND UED.UED_USERSTAMP_ID=ULD1.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 4 AND UED.UED_ID=UPD.UED_ID ORDER BY UED.UED_TIMESTAMP DESC";
    $reordersql=mysqli_query($connection,$reorderselect_option);
    $reorderrecord=mysqli_num_rows($reordersql);
    $y=$reorderrecord;

    $appendTablenotifi_reorder ="<table id='example2' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th style='text-align:center;'>DATE REQUIRED</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>QUOTATION ID</th><th style='text-align:center;'>USERSTAMP</th><th style='text-align:center;'>TIMESTAMP</th></tr></thead><tbody>";
    while($reorderrecord=mysqli_fetch_array($reordersql))
    {
        $appendTablenotifi_reorder .="<tr>";
        $row_id="VWQT_ID/".$reorderrecord[0];
        for($y = 1; $y < 8; $y++) {
            $appendTablenotifi_reorder .="<td>".$reorderrecord[$y]."</td>";
        }
        $appendTablenotifi_reorder .="</tr>";
    }
    $appendTablenotifi_reorder .="</tbody></table>";
    $deliveredselect_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UPD.PD_JOB_TITLE,UED_ENQUIRY_ID, QD.QD_QUOTATION_ID,UCASE(ULD1.ULD_USERNAME)AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1, JP_QUOTATION_DETAILS QD,JP_USER_PRODUCT_DETAILS UPD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID  AND UED.UED_USERSTAMP_ID=ULD1.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 5 AND UED.UED_ID=UPD.UED_ID ORDER BY UED.UED_TIMESTAMP DESC";
    $deliveredsql=mysqli_query($connection,$deliveredselect_option);
    $deliveredrecord=mysqli_num_rows($deliveredsql);
    $y=$deliveredrecord;

    $appendTablenotifi_delivered ="<table id='example3' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th style='text-align:center;'>DATE REQUIRED</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>QUOTATION ID</th><th style='text-align:center;'>USERSTAMP</th><th style='text-align:center;'>TIMESTAMP</th></tr></thead><tbody>";
    while($deliveredrecord=mysqli_fetch_array($deliveredsql))
    {
        $appendTablenotifi_delivered .="<tr>";
        $row_id="VWQT_ID/".$deliveredrecord[0];
        for($y = 1; $y < 8; $y++) {
            $appendTablenotifi_delivered .="<td>".$deliveredrecord[$y]."</td>";
        }
        $appendTablenotifi_delivered .="</tr>";
    }
    $appendTablenotifi_delivered .="</tbody></table>";
    $values=array($appendTablenotifi_enquiry,$appendTablenotifi_conform,$appendTablenotifi_reorder,$appendTablenotifi_delivered);
    echo JSON_encode($values);

}
elseif($_REQUEST["Option"]=="User_NotificationList")
{
    $enquiryselect_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UPD.PD_JOB_TITLE,UED_ENQUIRY_ID, QD.QD_QUOTATION_ID,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1, JP_QUOTATION_DETAILS QD,JP_USER_PRODUCT_DETAILS UPD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND UED.UED_USERSTAMP_ID=ULD1.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 2 and ULD.ULD_USERNAME='$session_id' AND UPD.UED_ID=UED.UED_ID ORDER BY UED.UED_TIMESTAMP DESC";
    $enquirysql=mysqli_query($connection,$enquiryselect_option);
    $enquiryrecord=mysqli_num_rows($enquirysql);
    $y=$enquiryrecord;
    $appendTablenotifi_enquiry ="<table id='example' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><th style='text-align:center;'>DATE REQUIRED</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>QUOTATION ID</th><th style='text-align:center;'>USERSTAMP</th><th style='text-align:center;'>TIMESTAMP</th></tr></thead><tbody>";

    while($enquiryrecord=mysqli_fetch_array($enquirysql))
    {
        $appendTablenotifi_enquiry .="<tr>";
        $rowid="VWEQ_ID/".$enquiryrecord[0];
        for($y = 1; $y < 7; $y++) {
            $appendTablenotifi_enquiry .="<td>".$enquiryrecord[$y]."</td>";
        }
        $appendTablenotifi_enquiry .="</tr>";
    }
    $appendTablenotifi_enquiry .="</tbody></table>";

    $conformselect_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UPD.PD_JOB_TITLE,UED_ENQUIRY_ID, QD.QD_QUOTATION_ID,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1, JP_QUOTATION_DETAILS QD,JP_USER_PRODUCT_DETAILS UPD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND UED.UED_USERSTAMP_ID=ULD1.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 4 AND UPD.UED_ID=UED.UED_ID AND ULD.ULD_USERNAME='$session_id' ORDER BY UED.UED_TIMESTAMP DESC";
    $conformsql=mysqli_query($connection,$conformselect_option);
    $conformrecord=mysqli_num_rows($conformsql);
    $y=$conformrecord;
    $appendTablenotifi_conform ="<table id='example1' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th style='text-align:center;'>DATE REQUIRED</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>QUOTATION ID</th><th style='text-align:center;'>USERSTAMP</th><th style='text-align:center;'>TIMESTAMP</th></tr></thead><tbody>";
    while($conformrecord=mysqli_fetch_array($conformsql))
    {
        $appendTablenotifi_conform .="<tr>";
        $row_id="VWQT_ID/".$conformrecord[0];
        for($y = 1; $y < 7; $y++) {
            $appendTablenotifi_conform .="<td>".$conformrecord[$y]."</td>";
        }
        $appendTablenotifi_conform .="</tr>";
    }
    $appendTablenotifi_conform .="</tbody></table>";

    $reorderselect_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UPD.PD_JOB_TITLE,UED_ENQUIRY_ID, QD.QD_QUOTATION_ID,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1, JP_QUOTATION_DETAILS QD,JP_USER_PRODUCT_DETAILS UPD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND UED.UED_USERSTAMP_ID=ULD1.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 5 AND UPD.UED_ID=UED.UED_ID AND ULD.ULD_USERNAME='$session_id' ORDER BY UED.UED_TIMESTAMP DESC";
    $reordersql=mysqli_query($connection,$reorderselect_option);
    $reorderrecord=mysqli_num_rows($reordersql);
    $y=$reorderrecord;

    $appendTablenotifi_reorder ="<table id='example2' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th style='text-align:center;'>DATE REQUIRED</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>QUOTATION ID</th><th style='text-align:center;'>USERSTAMP</th><th style='text-align:center;'>TIMESTAMP</th></tr></thead><tbody>";
    while($reorderrecord=mysqli_fetch_array($reordersql))
    {
        $appendTablenotifi_reorder .="<tr>";
        $row_id="VWQT_ID/".$reorderrecord[0];
        for($y = 1; $y < 7; $y++) {
            $appendTablenotifi_reorder .="<td>".$reorderrecord[$y]."</td>";
        }
        $appendTablenotifi_reorder .="</tr>";
    }
    $appendTablenotifi_reorder .="</tbody></table>";
    $Deliveredselect_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UPD.PD_JOB_TITLE,UED_ENQUIRY_ID, QD.QD_QUOTATION_ID,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1, JP_QUOTATION_DETAILS QD,JP_USER_PRODUCT_DETAILS UPD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND UED.UED_USERSTAMP_ID=ULD1.ULD_ID AND UED.QD_ID = QD.QD_ID AND ES.ES_ID = 3 AND UPD.UED_ID=UED.UED_ID AND ULD.ULD_USERNAME='$session_id' ORDER BY UED.UED_TIMESTAMP DESC";
    $Deliveredsql=mysqli_query($connection,$Deliveredselect_option);
    $Deliveredrecord=mysqli_num_rows($Deliveredsql);
    $y=$Deliveredrecord;

    $appendTablenotifi_Delivered ="<table id='example3' border=1 cellspacing='0' data-class='table'class=' srcresult table'><thead class='headercolor'><th style='text-align:center;'>DATE REQUIRED</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>QUOTATION ID</th><th style='text-align:center;'>USERSTAMP</th><th style='text-align:center;'>TIMESTAMP</th></tr></thead><tbody>";
    while($Deliveredrecord=mysqli_fetch_array($Deliveredsql))
    {
        $appendTablenotifi_reorder .="<tr>";
        $row_id="VWQT_ID/".$Deliveredrecord[0];
        for($y = 1; $y < 7; $y++) {
            $appendTablenotifi_Delivered .="<td>".$Deliveredrecord[$y]."</td>";
        }
        $appendTablenotifi_Delivered .="</tr>";
    }
    $appendTablenotifi_reorder .="</tbody></table>";
    $values=array($appendTablenotifi_enquiry,$appendTablenotifi_conform,$appendTablenotifi_reorder,$appendTablenotifi_Delivered);
    echo JSON_encode($values);

}
elseif($_POST["Option"]=="OrderUpdate")
{
    $rowid=$_POST["Uedid"];
    $PON=$connection->real_escape_string($_POST["Key"]);
    $uploadfiles=$_POST["Files"];
    $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(3,null,NULL,NULL,3,NULL,null,NULL,'$uploadfiles',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,
       NULL,NULL,'$PON',$rowid,'$login_session',@success_flag,@ENQ_ID,@ENQUIRY_DATE)";
    $result = $connection->query($callquery);
    if(!$result)
    {
        die("CALL failed: (" . $con->errno . ") " . $connection->error);
    }
    $select = $connection->query('SELECT @success_flag');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    $returnvalue=User_Quotationlist();
    echo $returnvalue;
}
elseif($_POST["Option"]=="RevisedQuotation")
{
    $rowid=$_POST["Uedid"];
    $callquery="UPDATE JP_USER_ENQUIRY_DETAILS SET ES_ID=4 WHERE UED_ID='$rowid';";
    $result = $connection->query($callquery);
    $returnvalue=User_Quotationlist();
    echo $returnvalue;
}
elseif($_REQUEST["Option"]=="User_Quotationlist")
{
    $returnvalue=User_Quotationlist();
    $enquirydata=productnamelist();
    $values=array($returnvalue,$enquirydata);
    echo json_encode($values);
}
elseif($_REQUEST["Option"]=="Delivered")
{
    $delivered_uldid=$_REQUEST["temp_id"];
    $attach_file_name=$_REQUEST["Files"];
    $updatequery="UPDATE JP_USER_ENQUIRY_DETAILS SET ES_ID=5,ULD_POD_IMAGENAME='$attach_file_name' WHERE UED_ID='$delivered_uldid'";
    mysqli_query($connection,$updatequery);
    $tabledata=DeliveredQuotationlist();
    echo json_encode($tabledata);
}
elseif($_REQUEST["Option"]=="UserNotification")
{
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UED.UED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UED.UED_ENQUIRY_ID,QD_QUOTATION_ID,ES.ES_STATUS,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,ULD1.ULD_IMAGE_NAME FROM  JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1 WHERE  ES.ES_ID=UED.ES_ID AND ULD.ULD_ID=UED.ULD_ID AND ULD1.ULD_ID=UED.UED_USERSTAMP_ID AND QD.UED_ID=UED.UED_ID  AND UED.ES_ID IN (2,5) AND ULD.ULD_USERNAME='$login_session'";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    while($record=mysqli_fetch_array($sql))
    {
            if($record[4]=='Quotation Updated')
            {
            $message="Quotation Generated For Enquiry Id:".$record[2]." And Quotation Id:".$record[3]." Check The Updated Quotation On Enquiry Details Table";
            }
            elseif($record[4]=='Delivered')
            {
            $message="Product Delivered For Enquiry Id:".$record[2]." And Quotation Id:".$record[3]." Check The Details on Enquiry Details Table";
            }
        $notificationdetails[]=array($record[5],$message,$record[6]);
    }
    echo JSON_encode($notificationdetails);
}

elseif($_REQUEST["Option"]=="AdminDeliveredQuotationList")
{
    $list=DeliveredQuotationlist(5);
    echo $list;
}
elseif($_REQUEST["Option"]=="AdminCancelledQuotationList")
{
    $list=DeliveredQuotationlist(4);
    echo $list;
}
elseif($_REQUEST["Option"]=="AdminconfirmedQuotationList")
{
    $list=DeliveredQuotationlist(3);
    echo $list;
}
elseif($_REQUEST['Option']=="EMAILTEMPLATE")
{
$emaillist=Emailtemplatelist();
echo $emaillist;
}
elseif($_REQUEST['Option']=="EMAILTEMPLATEUPDATE")
{
    $data=$connection->real_escape_string($_REQUEST['DATA']);
    $Title=$_REQUEST['Title'];
    $ID=$_REQUEST['ID'];
    if($Title=='SUBJECT')
    {
      $updatequery="UPDATE JP_EMAIL_TEMPLATE_DETAILS SET ETD_EMAIL_SUBJECT='$data' WHERE ET_ID=".$ID;
    }
    elseif($Title=='BODY')
    {
       $updatequery="UPDATE JP_EMAIL_TEMPLATE_DETAILS SET ETD_EMAIL_BODY='$data' WHERE ET_ID=".$ID;
    }
    $connection->query($updatequery);
    $emaillist=Emailtemplatelist();
    echo $emaillist;
}
elseif($_REQUEST['Option']=="CONFIRMMESSAGE")
{
    $messagelist=Confirmmessagelist();
    echo $messagelist;
}
elseif($_REQUEST['Option']=="CONFIRM_MESSAGE_UPDATE")
{
    $data=$connection->real_escape_string($_REQUEST['DATA']);
    $Title=$_REQUEST['Title'];
    $ID=$_REQUEST['ID'];
    if($Title=='TITLE')
    {
        $updatequery="UPDATE JP_ERROR_MESSAGE_CONFIGURATION SET EMC_TITLE='$data' WHERE EMC_CODE=".$ID;
    }
    elseif($Title=='MESSAGE')
    {
        $updatequery="UPDATE JP_ERROR_MESSAGE_CONFIGURATION SET EMC_DATA='$data' WHERE EMC_CODE=".$ID;
    }
    $connection->query($updatequery);
    $messagelist=Confirmmessagelist();
    echo $messagelist;
}
elseif($_REQUEST['Option']=="MAIL_NOTIFICATION")
{
    $amillist=Mailnotificationlist();
    echo $amillist;
}
elseif($_REQUEST['Option']=="MAIL_NOTIFICATION_UPDATE")
{
    $data=$connection->real_escape_string($_REQUEST['DATA']);
    $ID=$_REQUEST['ID'];
    $updatequery="UPDATE JP_USER_RIGHTS_CONFIGURATION SET URC_DATA='$data' WHERE URC_ID=".$ID;
    $connection->query($updatequery);
    $amillist=Mailnotificationlist();
    echo $amillist;
}
function Mailnotificationlist()
{
    global $connection;
    $selectquery="SELECT JPURC.URC_ID,JPC.CGN_TYPE,JPURC.URC_DATA FROM JP_CONFIGURATION JPC,JP_USER_RIGHTS_CONFIGURATION JPURC WHERE JPC.CGN_ID=JPURC.CGN_ID AND JPURC.URC_ID IN(2,3)";
    $sql=mysqli_query($connection,$selectquery);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='Mail_Notification_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'>
    <thead class='headercolor'>
        <tr class='head' style='text-align:center'>
            <th style='text-align:center;'>CONFIGURATION TYPE</th>
            <th style='text-align:center;'>CONFIGURATION DATA</th>
        </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        $id="Mail_id/".$record[1];
        for($y = 1; $y <=2; $y++)
        {
            if($y==1)
            {
                $appendTable1 .="<td class='Mail_Edit' style='width:300px;' id=Type_".$record[0].">".$record[$y]."</td>";
            }
            if($y==2)
            {
                $appendTable1 .="<td class='Mail_Edit' style='width:600px;' id=Data_".$record[0]." >".$record[$y]."</td>";
            }
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    return $appendTable1;
}
function Confirmmessagelist()
{
    global $connection;
    $selectquery="SELECT EMC_CODE,EMC_TITLE,EMC_DATA FROM JP_ERROR_MESSAGE_CONFIGURATION WHERE EMC_INITIALIZE_FLAG IS NULL";
    $sql=mysqli_query($connection,$selectquery);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='Confirm_message_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'>
    <thead class='headercolor'>
        <tr class='head' style='text-align:center'>
            <th style='text-align:center;'>TITLE</th>
            <th style='text-align:center;'>CONFIRM MESSAGE</th>
        </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        $id="EMC_id/".$record[1];
        for($y = 1; $y <=2; $y++)
        {
            if($y==1)
            {
                $appendTable1 .="<td class='EMC_Edit' style='width:300px;' id=Title_".$record[0].">".$record[$y]."</td>";
            }
            if($y==2)
            {
                $appendTable1 .="<td class='EMC_Edit' style='width:600px;' id=Message_".$record[0]." >".$record[$y]."</td>";
            }
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    return $appendTable1;
}
function Emailtemplatelist()
{
    global $connection;
    $selectquery="SELECT * FROM JP_EMAIL_TEMPLATE_DETAILS";
    $sql=mysqli_query($connection,$selectquery);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='emailtemplate' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'>
    <thead class='headercolor'>
        <tr class='head' style='text-align:center'>
            <th style='text-align:center;'>EMAIL SUBJECT</th>
            <th style='text-align:center;'>EMAIL BODY</th>
        </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[1]'>";
        $id="ET_id/".$record[1];
        for($y = 2; $y <=3; $y++)
        {
            if($y==2)
            {
                 $appendTable1 .="<td class='ET_Edit' style='width:300px;' id=Sub_".$record[1].">".$record[$y]."</td>";
            }
            if($y==3)
            {
                $appendTable1 .="<td class='ET_Edit' style='width:600px;' id=Body_".$record[1]." >".$record[$y]."</td>";
            }
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    return $appendTable1;
}
function Enquirylist()
{
    global $connection;
    global $session_id;
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UED_ENQUIRY_ID,ES.ES_STATUS,UPD.PD_JOB_TITLE,GROUP_CONCAT(ID.ITD_ITEM  SEPARATOR '^^') AS PRODUCT_NAME,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T'),ULD_UPLOAD_IMG_NAME
                    FROM
                    JP_USER_ENQUIRY_DETAILS UED LEFT JOIN JP_QUOTATION_DETAILS QD
                    ON UED.UED_ID=QD.UED_ID,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_PRODUCT_DETAILS UPD
                    LEFT JOIN JP_ITEM_DETAILS ID ON UPD.ETI_ID=ID.ITD_ID
                    WHERE
                    UED.ULD_ID=(SELECT ULD_ID FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME='$session_id') AND ULD.ULD_ID=UED.ULD_ID AND ES.ES_ID=UED.ES_ID AND UED.UED_ID=UPD.UED_ID
                    AND ES.ES_ID=1 GROUP BY UED_TIMESTAMP DESC";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='user_table'  border=1 cellspacing='0' data-class='table'  class=' srcresult table'>
    <thead class='headercolor'>
        <tr class='head' style='text-align:center'>
            <th style='text-align:center;'>DATE REQUIRED</th>
            <th style='text-align:center;'>ENQUIRY ID</th>
            <th style='text-align:center;'>STATUS</th>
            <th style='text-align:center;'>JOB TITLE</th>
            <th style='text-align:center;'>TYPE OF PRINT</th>
            <th style='text-align:center;'>TIMESTAMP</th>
            <th style='text-align:center;'>UPLOAD IMAGES</th>
        </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        $id="EQ_id/".$record[0];
        for($y = 1; $y <8; $y++)
        {
            if($y==2)
            {

                    $appendTable1 .="<td><a href='#'id=$id class='userenquiryview'>".$record[$y]."</a></td>";

            }
            elseif($y==7)
            {
                $imagebody="";
                if($record[$y]!="")
                {
                    $array=explode("/",$record[$y]);
                    for($j=0;$j<count($array);$j++)
                    {
                        $row=$j+1;
                        $imagebody.='<a href="download.php?filename='.$array[$j].'" class="links">'.$row.'.'.$array[$j].'</a><br>';
                    }
                }
                $appendTable1.='<td>'.$imagebody.'</td>';

            }
            else
            {
                $appendTable1 .="<td>".$record[$y]."</td>";
            }
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    return $appendTable1;
}

function User_Quotationlist()
{
    global $connection;
    global $session_id;
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UED_ENQUIRY_ID,QD.QD_QUOTATION_ID,ES.ES_STATUS,UPD.PD_JOB_TITLE,GROUP_CONCAT(ETI.ITD_ITEM  SEPARATOR '^^'),UED.UED_PURCHASE_ORDER_NO,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T'),ULD_UPLOAD_IMG_NAME,UED.ULD_POD_IMAGENAME
    FROM JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD ,JP_USER_PRODUCT_DETAILS UPD
    left join JP_ITEM_DETAILS ETI on ETI.ITD_ID=UPD.ETI_ID,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD
    WHERE UED.ULD_ID=(SELECT ULD_ID FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME= '$session_id') AND ULD.ULD_ID=UED.ULD_ID AND ES.ES_ID=UED.ES_ID AND UED.UED_ID=UPD.UED_ID AND  UED.QD_ID IS NOT NULL AND UED.UED_ID=QD.UED_ID GROUP BY UED_TIMESTAMP DESC";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='user_table' style='width:1500px'  border=1 cellspacing='0' data-class='table'  class=' srcresult table'>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;vertical-align: top;'>DATE REQUIRED</th>
    <th style='text-align:center;vertical-align: top'>ENQUIRYID</th>
    <th style='text-align:center;vertical-align: top'>QUOTATION ID</th>
    <th style='text-align:center;vertical-align: top'>STATUS</th>
    <th style='text-align:center;vertical-align: top'>JOB TITLE</th>
    <th style='text-align:center;vertical-align: top'>TYPE OF PRINT</th>
    <th style='text-align:center;vertical-align: top'>PURCHASE ORDER NO</th>
    <th style='text-align:center;vertical-align: top'>TIMESTAMP</th>
    <th style='text-align:center;vertical-align: top'>UPLOAD IMAGES</th>
    <th style='text-align:center;vertical-align: top'>POD IMAGES</th>
    </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        $id="EQ_id/".$record[0];
        for($y = 1; $y <11; $y++)
        {
            if($y==3)
            {
                $appendTable1 .="<td><a href='#'id=$id class='userquotationview'>".$record[$y]."</a></td>";
            }
            elseif($y==2)
            {
                if($y==2 && $record[3]=="")
                {
                    $appendTable1 .="<td><a href='#'id=$id class='userenquiryview'>".$record[$y]."</a></td>";
                }
                else
                {
                    $appendTable1 .="<td>".$record[$y]."</td>";
                }
            }
            elseif($y==9 || $y==10)
            {
                $imagebody="";
                if($record[$y]!="")
                {
                    $array=explode("/",$record[$y]);
                    for($j=0;$j<count($array);$j++)
                    {
                        $row=$j+1;
                        $imagebody.='<a href="download.php?filename='.$array[$j].'" class="links">'.$row.'.'.$array[$j].'</a><br>';
                    }
                }
                $appendTable1.='<td>'.$imagebody.'</td>';

            }
            else
            {
                $appendTable1 .="<td>".$record[$y]."</td>";
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
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD_USERNAME),UED_ENQUIRY_ID,ES.ES_STATUS,UPD.PD_JOB_TITLE,GROUP_CONCAT(ID.ITD_ITEM  SEPARATOR '^^') AS PRODUCT_NAME,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T'),ULD_UPLOAD_IMG_NAME
                    FROM
                    JP_USER_ENQUIRY_DETAILS UED LEFT JOIN JP_QUOTATION_DETAILS QD
                    ON UED.UED_ID=QD.UED_ID,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_PRODUCT_DETAILS UPD
                    LEFT JOIN JP_ITEM_DETAILS ID ON UPD.ETI_ID=ID.ITD_ID
                    WHERE
                    ULD.ULD_ID=UED.ULD_ID AND ES.ES_ID=UED.ES_ID AND UED.UED_ID=UPD.UED_ID
                    AND ES.ES_ID=1 GROUP BY UED_TIMESTAMP DESC";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;vertical-align: top'>DATE REQUIRED</th>
    <th style='text-align:center;vertical-align: top'>USERNAME</th>
    <th style='text-align:center;vertical-align: top'>ENQUIRY ID</th>
    <th style='text-align:center;vertical-align: top'>STATUS</th>
    <th style='text-align:center;vertical-align: top'>JOB TITLE</th>
    <th style='text-align:center;vertical-align: top'>TYPE OF PRINT</th>
    <th style='text-align:center;vertical-align: top'>TIMESTAMP</th>
    <th style='text-align:center;vertical-align: top'>UPLOAD IMAGES</th>
    </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        for($y = 1; $y < 9; $y++)
        {
            if($y==3)
            {
                $Eqid="Enquiryid/".$record[0];
                $appendTable1 .="<td><a href='#' id=$Eqid class='Quotationcreation'>".$record[$y]."</a></td>";
            }
            elseif($y==2)
            {
                $appendTable1 .="<td>".ucfirst($record[$y])."</td>";
            }
            elseif($y==8)
            {
                $imagebody="";
                if($record[$y]!="")
                {
                    $array=explode("/",$record[$y]);
                    for($j=0;$j<count($array);$j++)
                    {
                        $row=$j+1;
                        $imagebody.='<a href="download.php?filename='.$array[$j].'" class="links">'.$row.'.'.$array[$j].'</a><br>';
                    }
                }
                $appendTable1.='<td>'.$imagebody.'</td>';
            }
            else
            {
                $appendTable1 .="<td>".$record[$y]."</td>";
            }
        }
        $appendTable1 .="</tr>";
    }
    $appendTable1 .="</tbody></table>";
    return $appendTable1;
}
function Quotationlist()
{
    global $connection;
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UED.UED_ENQUIRY_ID,QD_QUOTATION_ID,UPD.PD_JOB_TITLE,UED_PRICE,ES.ES_STATUS,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T'),UED.ULD_POD_IMAGENAME FROM  JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1,JP_USER_PRODUCT_DETAILS UPD WHERE  ES.ES_ID=UED.ES_ID AND ULD.ULD_ID=UED.ULD_ID AND ULD1.ULD_ID=UED.UED_USERSTAMP_ID AND QD.UED_ID=UED.UED_ID AND UED.UED_ID=UPD.UED_ID AND UED.ES_ID IN(2) ORDER BY UED.UED_TIMESTAMP DESC";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable3 ="<table id='user_table' style='width:1300px !important;' border=1 cellspacing='0' data-class='table'  class=' srcresult table' >
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;vertical-align: top''>DATE REQUIRED</th>
    <th style='text-align:center;vertical-align: top''>USERNAME</th>
    <th style='text-align:center;vertical-align: top''>ENQUIRY ID</th>
    <th style='text-align:center;vertical-align: top''>QUOTATION ID</th>
    <th style='text-align:center;vertical-align: top''>JOB TITLE</th>
    <th style='text-align:center;vertical-align: top''>PRICE</th>
    <th style='text-align:center;vertical-align: top''>STATUS</th>
    <th style='text-align:center;vertical-align: top''>USERSTAMP</th>
    <th style='text-align:center;vertical-align: top''>TIMESTAMP</th>
    <th style='text-align:center;vertical-align: top''>POD IMAGES</th>
    </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql))
    {
        $appendTable3 .="<tr id='$record[0]'>";
        $QT_view="QT_view/".$record[0];
        $EQ_viewid="Enquiryid/".$record[3];
        for($y = 1; $y <11; $y++)
        {
            if($y==10)
            {
               $appendTable3 .='<td><a href="download.php?filename='.$record[10].'" class="links">'.$record[10].'</a></td>';
            }
            elseif($y==4)
            {
                $appendTable3 .="<td><a href='#'id=$QT_view class='QuotationView' >".$record[$y]."</a></td>";
            }
            else
            {
                $appendTable3 .="<td>".$record[$y]."</td>";
            }
        }
        $appendTable3 .="</tr>";
    }
    $appendTable3 .="</tbody></table>";
    return $appendTable3;
}
function DeliveredQuotationlist($id)
{
    global $connection;
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UED.UED_ENQUIRY_ID,QD_QUOTATION_ID,UPD.PD_JOB_TITLE,UED.UED_PURCHASE_ORDER_NO,UED_PRICE,ES.ES_STATUS,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T'),UED.ULD_POD_IMAGENAME,UPD.PD_JOB_TITLE FROM  JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1,JP_USER_PRODUCT_DETAILS UPD WHERE  ES.ES_ID=UED.ES_ID AND ULD.ULD_ID=UED.ULD_ID AND ULD1.ULD_ID=UED.UED_USERSTAMP_ID AND QD.UED_ID=UED.UED_ID AND UED.ES_ID='$id' AND UPD.UED_ID=UED.UED_ID GROUP BY UED_TIMESTAMP DESC";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable3 ="<table id='Deliveredlist_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table' style='max-width:2000px'>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;vertical-align: top''>DATE REQUIRED</th>
    <th style='text-align:center;vertical-align: top''>USERNAME</th>
    <th style='text-align:center;vertical-align: top''>ENQUIRY ID</th>
    <th style='text-align:center;vertical-align: top''>QUOTATION ID</th>
    <th style='text-align:center;vertical-align: top''>JOB TITLE</th>
    <th style='text-align:center;vertical-align: top'>PURCHASE ORDER NO</th>
    <th style='text-align:center;vertical-align: top''>PRICE</th>
    <th style='text-align:center;vertical-align: top''>STATUS</th>
    <th style='text-align:center;vertical-align: top''>USERSTAMP</th>
    <th style='text-align:center;vertical-align: top''>TIMESTAMP</th>
    <th style='text-align:center;vertical-align: top''>POD IMAGES</th>
    </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql))
    {
        $appendTable3 .="<tr id='$record[0]'>";
        $QT_view="QT_view/".$record[0];
        $EQ_viewid="Enquiryid/".$record[3];
        for($y = 1; $y <12; $y++)
        {
            if($y==11)
            {
                $body='';
                if($record[11]!="")
                {
                $array=explode("/",$record[11]);
                for($j=0;$j<count($array);$j++)
                {
                      $body.='<a href="download.php?filename='.$array[$j].'" class="links">'.$array[$j].'</a><br>';
                }
                }
                $appendTable3 .='<td>'.$body.'</td>';

            }
            elseif($y==4)
            {
                $appendTable3 .="<td><a href='#'id=$QT_view class='QuotationView' >".$record[$y]."</a></td>";
            }
            else
            {
                $appendTable3 .="<td>".$record[$y]."</td>";
            }
        }
        $appendTable3 .="</tr>";
    }
    $appendTable3 .="</tbody></table>";
    return $appendTable3;
}
function productnamelist()
{
    global $connection;
    $enquiry_select="SELECT ITD_ID,SID_ID,ITD_ITEM from JP_ITEM_DETAILS WHERE ITD_FLAG='X' ORDER BY ITD_ITEM ASC";
    $enquirysql=mysqli_query($connection,$enquiry_select);
    $record1=mysqli_num_rows($enquirysql);
    while($record1=mysqli_fetch_array($enquirysql)){
     $enquirydata[]=array($record1['SID_ID'],$record1['ITD_ITEM'],$record1['ITD_ID']);
    }
    return $enquirydata;
}


