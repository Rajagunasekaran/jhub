
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
$emailtemplate=emailtempaltedetails();
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
    $item=$_POST["Item"];    if($item=='SELECT'){$item='';};
    $size=$_POST["Size"];if($size=='SELECT'){$size='';};
    $type=$_POST["Papertype"];if($type=='SELECT'){$type='';};
    $weight=$_POST["Paperweight"];if($weight=='SELECT'){$weight='';};
    $method=$_POST["Printingmethod"];if($method=='SELECT'){$method='';};
    $printingprocess=$_POST["Printingprocess"];if($printingprocess=='SELECT'){$printingprocess='';};
    $treatment=$_POST["Treatmentprocess"];if($treatment=='SELECT'){$treatment='';};
    $finish_process=$_POST["Finishingprocess"];if($finish_process=='SELECT'){$finish_process='';};
    $binding=$_POST["Bindingprocess"];if($binding=='SELECT'){$binding='';};
    $Quantity=$_POST["Quantity"];
    $date=$_POST["EnquiryDate"];
    $Location=$connection->real_escape_string($_POST["DeliveryLocation"]);
    $Remarks=$connection->real_escape_string($_POST["Remarks"]);
    $uploadfiles=$_POST["files"];

    $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(1,NULL,'$login_session',curdate(),'New','$item',NULL,'$Remarks','$uploadfiles',NULL,'$job_title','$Location','$size','$type','$weight','$method',
    '$printingprocess','$treatment','$finish_process','$binding','$Quantity','$date','$login_session',@SUCCESS_FLAG,@ENQ_ID,@ENQUIRY_DATE)";
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
//    $sessionname=strtoupper($login_session);
//    $emailmessage=str_replace("[USERNAME]",$sessionname,$emailtemplate[0][1]);
//    $emailmessage=str_replace("[ENQDATE]",$date,$emailmessage);
//    $emailmessage=str_replace("[ENQID]",$eq_id,$emailmessage);
//    $email_details=emaildetails();
//    $mail = new PHPMailer;
//    $mail->isSMTP();
//    $mail->Host = $email_details[8][0];
//    $mail->SMTPAuth = true;
//    $mail->Username = $email_details[5][0];
//    $mail->Password = $email_details[6][0];
//    $mail->SMTPSecure = $email_details[7][0];
//    $mail->From = $email_details[0][0];
//    $mail->FromName = 'J-PRINT';
//    $mail->addAddress($email_details[1][0]);
//    $mail->WordWrap = 50;
//    $mail->isHTML(true);
//    $mail->Subject =$emailtemplate[0][0];
//    $mail->Body =$emailmessage;
//    $mail->Send();
    }
    echo $flag;
}
if($_POST["Option"]=="Update")
{
    $product=$_POST["EnquiryDetails"];
    $Uploadfiles=$_POST['Files'];
    $Removedfiles=$_POST['RemovedFiles'];
    if($Removedfiles!='')
    {
        $removefile;
        for($k=0;$k<count($Removedfiles);$k++)
        {
            if($k==0){$removefile=$Removedfiles[$k];}
            else{$removefile=$removefile.'/'.$Removedfiles[$k];}
            unlink('POD-images/'.$Removedfiles[$k]);
        }
    }
    $product_id;$job_title;$size;$item;$type;$weight;$method;$printingprocess;$treatment;$finish_process;$binding;$Quantity;$date;$Location;$Remarks;
    if($product!='null')
    {
        for($i=0;$i<count($product);$i++)
        {
            $jobtitle= $connection->real_escape_string($product[$i][1]);
            $Item=$connection->real_escape_string($product[$i][2]);
            $Size= $connection->real_escape_string($product[$i][3]);
            $papertype=$product[$i][4];
            $paperweight=$product[$i][5];
            $printingmethod=$product[$i][6];
            $printing_process=$product[$i][7];
            $treatmentmethod=$product[$i][8];
            $finishingprocess=$product[$i][9];
            $bindingprocess=$product[$i][10];
            $quantity=$product[$i][11];
            $Date=$product[$i][12];
            $DelyLocation=$product[$i][13];
            $remarks=$product[$i][14];
            if($i==0)
            {
                $product_id=$product[$i][0]; $job_title=$jobtitle;$item=$Item;$size=$Size;$type=$papertype;
                $weight=$paperweight;$method=$printingmethod;$printingprocess=$printing_process;$treatment=$treatmentmethod;
                $finish_process=$finishingprocess;$binding=$bindingprocess;$Quantity=$quantity;$date=$Date;$Remarks=$remarks;
                $Location=$DelyLocation;
            }
            else
            {
                $product_id=$product_id.','.$product[$i][0]; $job_title=$job_title.'^~'.$jobtitle;$item=$item.'^~'.$Item;$size=$size.'^~'.$Size;
                $type=$type.'^~'.$papertype;$weight=$weight.'^~'.$paperweight;$method=$method.'^~'.$printingmethod;
                $printingprocess=$printingprocess.'^~'.$printing_process;$treatment=$treatment.'^~'.$treatmentmethod;
                $finish_process=$finish_process.'^~'.$finishingprocess;$binding=$binding.'^~'.$bindingprocess;$Quantity=$Quantity.'^~'.$quantity;
                $date=$date.'^~'.$Date;$Location=$Location.'^~'.$DelyLocation;$Remarks=$remarks.'^~'.$remarks;
            }
        }
    }
    $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(3,'$product_id','$login_session',curdate(),'New','$item',NULL,'$Remarks','$Uploadfiles','$removefile','$job_title','$Location','$size','$type','$weight','$method',
    '$printingprocess','$treatment','$finish_process','$binding','$Quantity','$date','$login_session',@SUCCESS_FLAG,@ENQ_ID,@ENQUIRY_DATE)";
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
    $appendTable2 ="<table id='quotation_view' border=1 cellspacing='0' data-class='table'class='srcresult table '>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;'>JOB TITLE</th>
    <th style='text-align:center;vertical-align: top'>ITEM</th>
    <th style='text-align:center;vertical-align: top'>SIZE</th>
    <th style='text-align:center;'>PAPER TYPE</th>
    <th style='text-align:center;'>PAPER WEIGHT</th>
    <th style='text-align:center;'>PRINTING METHOD</th>
    <th style='text-align:center;'>PRINTING PROCESS</th>
    <th style='text-align:center;'>TREAMENT PROCESS</th>
    <th style='text-align:center;'>FINISHING PROCESS</th>
    <th style='text-align:center;'>BINDING PROCESS</th>
    <th style='text-align:center;vertical-align: top'>QUANTITY</th>
    <th style='text-align:center;'>DATE REQUIRED</th>
    <th style='text-align:center;'>DELIVERY LOCATION</th>
    <th style='text-align:center;vertical-align: top'>REMARKS</th>
    <th style='text-align:center;vertical-align: top'>PRICE($)</th>
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
           $appendTable2 .="<td><input type='text' value=$price class='numbersOnly Quotationprice decimal Quotationamountvalidation' id=$rowid maxlength='8'  style='max-width:150px;border:0;text-align:right;font-size: 12px !important;'>
           <input type='hidden' id=$temprowid value=$rowno style='border:0;font-size: 12px !important;' value=></td>";
            }
            else
            {
           $appendTable2 .="<td><input type='text' class='numbersOnly Quotationprice decimal Quotationamountvalidation' id=$rowid maxlength='8' style='max-width:150px;border:0;text-align:right;font-size: 12px !important;'>
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
    $appendTable2 .="<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td style='text-align:right;font-weight: bold;margin-right: 50px'>TOTAL</td><td style='text-align:right;'><label id='quotationtotal' style='font-weight: bold;font-size: 14px !important;' >$totalprice</td></tr>";
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
    $callquery="CALL SP_ENQUIRY_INSERT_UPDATE(2,'$pd_id',NULL,NULL,'Quotation Updated','NULL','$product_price',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$login_session',@SUCCESS_FLAG,@ENQUIRYID,@ENQUIRYDATE)";
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
    $appendTable4 ="<table id='quotation_view'  border=1 cellspacing='0' data-class='table'class=' srcresult table'>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;'>JOB TITLE</th>
    <th style='text-align:center;vertical-align: top'>ITEM</th>
    <th style='text-align:center;vertical-align: top'>SIZE</th>
    <th style='text-align:center;'>PAPER TYPE</th>
    <th style='text-align:center;'>PAPER WEIGHT</th>
    <th style='text-align:center;'>PRINTING METHOD</th>
    <th style='text-align:center;'>PRINTING PROCESS</th>
    <th style='text-align:center;'>TREAMENT PROCESS</th>
    <th style='text-align:center;'>FINISHING PROCESS</th>
    <th style='text-align:center;'>BINDING PROCESS</th>
    <th style='text-align:center;vertical-align: top'>QUANTITY</th>
    <th style='text-align:center;'>DATE REQUIRED</th>
    <th style='text-align:center;'>DELIVERY LOCATION</th>
     <th style='text-align:center;vertical-align: top'>REMARKS</th>
    <th style='text-align:center;vertical-align: top'>PRICE($)</th>
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
elseif($_POST["Option"]=="AdminROQuotationView")
{
    $uedrowid=$_POST["Data"];
    $select_option="SELECT PRODUCT_NAME,PD_DESCRIPTION,PD_PRICE,UED_PRICE FROM JP_USER_PRODUCT_DETAILS UPD,JP_USER_ENQUIRY_DETAILS UED WHERE UPD.UED_ID='$uedrowid' AND UPD.UED_ID=UED.UED_ID";
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
                $appendTable4 .="<td style='text-align:right;font-size: 14px !important;'>".$record[$y]."</td>";
            }
            else
            {
                $appendTable4 .="<td style='text-align:left;font-size: 14px !important;'>".$record[$y]."</td>";
            }
        }

        $appendTable4 .="</tr>";
    }
    $appendTable4 .="<td></td><td style='text-align:right;font-weight: bold;'>TOTAL</td><td style='background-color:#FFE4B5;color:black;text-align:right;font-weight: bold;font-size: 14px !important;'>".$price."</td>";
    $appendTable4 .="</tbody></table>";
    echo $appendTable4;
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
            $appendTablenotifi_enquiry .="<td style='text-align: center;font-size: 14px !important;'>".$enquiryrecord[$y]."</td>";
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
            $appendTablenotifi_conform .="<td style='text-align: center;font-size: 14px !important;'>".$conformrecord[$y]."</td>";
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
            $appendTablenotifi_reorder .="<td style='text-align: center;font-size: 14px !important;'>".$reorderrecord[$y]."</td>";
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
            $appendTablenotifi_delivered .="<td style='text-align: center;font-size: 14px !important;'>".$deliveredrecord[$y]."</td>";
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
            $appendTablenotifi_enquiry .="<td style='text-align: center;font-size: 14px !important;'>".$enquiryrecord[$y]."</td>";
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
            $appendTablenotifi_conform .="<td style='text-align: center;font-size: 14px !important;'>".$conformrecord[$y]."</td>";
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
            $appendTablenotifi_reorder .="<td style='text-align: center;font-size: 14px !important;'>".$reorderrecord[$y]."</td>";
        }
        $appendTablenotifi_reorder .="</tr>";
    }
    $appendTablenotifi_reorder .="</tbody></table>";
    $values=array($appendTablenotifi_enquiry,$appendTablenotifi_conform,$appendTablenotifi_reorder);
    echo JSON_encode($values);

}
elseif($_POST["Option"]=="OrderUpdate")
{
$rowid=$_POST["Uedid"];
$callquery="UPDATE JP_USER_ENQUIRY_DETAILS SET ES_ID=3 WHERE UED_ID='$rowid';";
$result = $connection->query($callquery);
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
elseif($_POST["Option"]=="AdminRevisedQuotationList")
{
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UED.UED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UED.UED_ENQUIRY_ID,QD.QD_QUOTATION_ID,UED.UED_PRICE,ES.ES_STATUS,UCASE(ULD.ULD_USERNAME),DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED, JP_QUOTATION_DETAILS QD, JP_ENQUIRY_STATUS ES, JP_USER_LOGIN_DETAILS ULD WHERE UED.ES_ID = ES.ES_ID AND UED.ULD_ID = ULD.ULD_ID AND UED.UED_ID = QD.UED_ID AND UED.QD_ID = QD.QD_ID AND UED.UED_USERSTAMP_ID = ULD.ULD_ID AND ES.ES_ID = 4";

    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable3 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center'>ACTION</th><th style='text-align:center'>DATE</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center'>QUOTATION ID</th><th style='text-align:center'>PRICE</th><th style='text-align:center'>STATUS</th><th style='text-align:center'>USERSTAMP</th><th style='text-align:center'>TIMESTAMP</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql))
    {
        $appendTable3 .="<tr id='$record[0]'>";
        $QT_view="QT_view/".$record[0];
        $appendTable3.="<td style='text-align:center'><a href='#'><span class='glyphicon glyphicon-plus-sign' style='color:#73c20e;'></a></span></td>";
        for($y = 1; $y <9; $y++)
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
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UED.UED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UED.UED_ENQUIRY_ID,QD_QUOTATION_ID,UED_PRICE,ES.ES_STATUS,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T') FROM JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1 WHERE  ES.ES_ID=UED.ES_ID AND  ULD.ULD_ID=UED.ULD_ID AND ULD1.ULD_ID=UED.UED_USERSTAMP_ID AND QD.UED_ID=UED.UED_ID AND UED.UED_ENQUIRY_ID='$EQ_id'";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable3 ="<table id='All_recverdetails' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center'>DATE</th><th style='text-align:center;'>USERNAME</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center'>QUOTATION ID</th><th style='text-align:center'>PRICE</th><th style='text-align:center'>STATUS</th><th style='text-align:center'>USERSTAMP</th><th style='text-align:center'>TIMESTAMP</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql))
    {
        $appendTable3 .="<tr id='$record[0]'>";
        $QT_view="QT_view/".$record[0];
        $EQ_viewid="Enquiryid/".$record[3];
        for($y = 1; $y <9; $y++)
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
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'><thead class='headercolor'><tr class='head' style='text-align:center'><th style='text-align:center;max-width:80px;'>DATE REQUIRED</th><th style='text-align:center;'>ENQUIRY ID</th><th style='text-align:center;'>STATUS</th><th style='text-align:center;'>JOB TITLE</th><th style='text-align:center;'>ITEM</th><th style='text-align:center;'>TIMESTAMP</th><th style='text-align:center;'>UPLOAD IMAGES</th></tr></thead><tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        $id="EQ_id/".$record[0];
        for($y = 1; $y <8; $y++)
        {
            if($y==2)
            {

                    $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'><a href='#'id=$id class='userenquiryview'>".$record[$y]."</a></td>";

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
                $appendTable1.='<td style="font-size: 14px !important;">'.$imagebody.'</td>';

            }
            else
            {
                $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'>".$record[$y]."</td>";
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
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UED_ENQUIRY_ID,QD.QD_QUOTATION_ID,ES.ES_STATUS,UPD.PD_JOB_TITLE,GROUP_CONCAT(ETI.ITD_ITEM  SEPARATOR '^^'),DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T'),ULD_UPLOAD_IMG_NAME,UED.ULD_POD_IMAGENAME
    FROM JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD ,JP_USER_PRODUCT_DETAILS UPD
    left join JP_ITEM_DETAILS ETI on ETI.ITD_ID=UPD.ETI_ID,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD
    WHERE UED.ULD_ID=(SELECT ULD_ID FROM JP_USER_LOGIN_DETAILS WHERE ULD_USERNAME= '$session_id') AND ULD.ULD_ID=UED.ULD_ID AND ES.ES_ID=UED.ES_ID AND UED.UED_ID=UPD.UED_ID AND  UED.QD_ID IS NOT NULL AND UED.UED_ID=QD.UED_ID GROUP BY UED_TIMESTAMP DESC";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;vertical-align: top''>DATE REQUIRED</th>
    <th style='text-align:center;vertical-align: top''>ENQUIRY ID</th>
    <th style='text-align:center;vertical-align: top''>QUOTATION ID</th>
    <th style='text-align:center;vertical-align: top''>STATUS</th>
    <th style='text-align:center;vertical-align: top''>JOB TITLE</th>
    <th style='text-align:center;vertical-align: top''>ITEM</th>
    <th style='text-align:center;vertical-align: top''>TIMESTAMP</th>
    <th style='text-align:center;vertical-align: top''>UPLOAD IMAGES</th>
    <th style='text-align:center;vertical-align: top''>POD IMAGES</th>
    </tr>
    </thead>
    <tbody>";
    while($record=mysqli_fetch_array($sql)){
        $appendTable1 .="<tr id='$record[0]'>";
        $id="EQ_id/".$record[0];
        for($y = 1; $y <10; $y++)
        {
            if($y==3)
            {
                $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'><a href='#'id=$id class='userquotationview'>".$record[$y]."</a></td>";
            }
            elseif($y==2)
            {
                if($y==2 && $record[3]=="")
                {
                    $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'><a href='#'id=$id class='userenquiryview'>".$record[$y]."</a></td>";
                }
                else
                {
                    $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'>".$record[$y]."</td>";
                }
            }
            elseif($y==8 || $y==9)
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
                $appendTable1.='<td style="font-size: 14px !important;">'.$imagebody.'</td>';

            }
            else
            {
                $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'>".$record[$y]."</td>";
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
    $appendTable1 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'>
    <thead class='headercolor'>
    <tr class='head' style='text-align:center'>
    <th style='text-align:center;vertical-align: top'>DATE REQUIRED</th>
    <th style='text-align:center;vertical-align: top'>USERNAME</th>
    <th style='text-align:center;vertical-align: top'>ENQUIRY ID</th>
    <th style='text-align:center;vertical-align: top'>STATUS</th>
    <th style='text-align:center;vertical-align: top'>JOB TITLE</th>
    <th style='text-align:center;vertical-align: top'>ITEM</th>
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
                $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'><a href='#' id=$Eqid class='Quotationcreation'>".$record[$y]."</a></td>";
            }
            elseif($y==2)
            {
                $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'>".ucfirst($record[$y])."</td>";
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
                $appendTable1.='<td style="font-size: 14px !important;">'.$imagebody.'</td>';
            }
            else
            {
                $appendTable1 .="<td style='text-align:center;font-size: 14px !important;'>".$record[$y]."</td>";
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
    $appendTable3 ="<table id='user_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'>
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
               $appendTable3 .='<td style="font-size: 14px !important;"><a href="download.php?filename='.$record[10].'" class="links">'.$record[10].'</a></td>';
            }
            elseif($y==4)
            {
                $appendTable3 .="<td style='text-align:center;font-size: 14px !important;'><a href='#'id=$QT_view class='QuotationView' >".$record[$y]."</a></td>";
            }
            else
            {
                $appendTable3 .="<td style='text-align:center;font-size: 14px !important;'>".$record[$y]."</td>";
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
    $select_option="SELECT UED.UED_ID,DATE_FORMAT(CONVERT_TZ(UPD.PD_REQUIRED_DATE,'+00:00','+08:00'), '%d-%m-%Y'),UCASE(ULD.ULD_USERNAME),UED.UED_ENQUIRY_ID,QD_QUOTATION_ID,UPD.PD_JOB_TITLE,UED_PRICE,ES.ES_STATUS,UCASE(ULD1.ULD_USERNAME) AS USERSTAMP,DATE_FORMAT(CONVERT_TZ(UED.UED_TIMESTAMP,'+00:00','+08:00'), '%d-%m-%Y %T'),UED.ULD_POD_IMAGENAME,UPD.PD_JOB_TITLE FROM  JP_USER_ENQUIRY_DETAILS UED,JP_QUOTATION_DETAILS QD,JP_ENQUIRY_STATUS ES,JP_USER_LOGIN_DETAILS ULD,JP_USER_LOGIN_DETAILS ULD1,JP_USER_PRODUCT_DETAILS UPD WHERE  ES.ES_ID=UED.ES_ID AND ULD.ULD_ID=UED.ULD_ID AND ULD1.ULD_ID=UED.UED_USERSTAMP_ID AND QD.UED_ID=UED.UED_ID AND UED.ES_ID='$id' AND UPD.UED_ID=UED.UED_ID GROUP BY UED_TIMESTAMP DESC";
    $sql=mysqli_query($connection,$select_option);
    $record=mysqli_num_rows($sql);
    $y=$record;
    $appendTable3 ="<table id='Deliveredlist_table' border=1 cellspacing='0' data-class='table'  class=' srcresult table'  width='auto'>
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
                $body='';
                if($record[10]!="")
                {
                $array=explode("/",$record[10]);
                for($j=0;$j<count($array);$j++)
                {
                      $body.='<a href="download.php?filename='.$array[$j].'" class="links">'.$array[$j].'</a><br>';
                }
                }
                $appendTable3 .='<td style="font-size: 14px !important;">'.$body.'</td>';

            }
            elseif($y==4)
            {
                $appendTable3 .="<td style='text-align:center;font-size: 14px !important;'><a href='#'id=$QT_view class='QuotationView' >".$record[$y]."</a></td>";
            }
            else
            {
                $appendTable3 .="<td style='text-align:center;font-size: 14px !important;'>".$record[$y]."</td>";
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
    $enquiry_select="SELECT ITD_ID,SID_ID,ITD_ITEM from JP_ITEM_DETAILS ORDER BY ITD_ITEM ASC";
    $enquirysql=mysqli_query($connection,$enquiry_select);
    $record1=mysqli_num_rows($enquirysql);
    while($record1=mysqli_fetch_array($enquirysql)){
     $enquirydata[]=array($record1['SID_ID'],$record1['ITD_ITEM'],$record1['ITD_ID']);
    }
    return $enquirydata;
}


