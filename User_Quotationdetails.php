<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("usermenu.php");
?>
<HTML>
<script>
$(document).ready(function(){
    $('#userQuotationlist').css("color", "#73c20e");
    $('textarea').autogrow({onInitialize: true});
    $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
    $('#conformorder').hide();
    $('.preloader').show();
    //**********ENQUIRY DETAILS INITIALIZE FUNCTION START*************//
    var en_save;
    var en_update;
    var qo_update;
    var lg_create;
    var lg_update;
    var order_conform;
    var confirmorder;
    var quotationrevise;
    var purchaseconfirmmessage;
    var confirmpdfmessage;
    var deliveredpdfmessage;
    $.ajax({
        type: "POST",
        url: "DB_Error_Msg.php",
        data:{"Option":'ERROR'},
        success: function(data){
            $('.preloader').hide();
            var value_array=JSON.parse(data);
            en_save=value_array[0];
            en_update=value_array[1];
            qo_update=value_array[2];
            lg_create=value_array[3];
            lg_update=value_array[4];
            confirmorder=value_array[5];
            order_conform=value_array[6];
            quotationrevise=value_array[8];
            purchaseconfirmmessage=value_array[14];
            confirmpdfmessage=value_array[15];
            deliveredpdfmessage=value_array[16];
        },
        error: function(data){
            alert('error in getting'+JSON.stringify(data));
        }
    });
    $(".preloader").show();
    $('#UQT_Bacttolist').hide();
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var values_array=JSON.parse(xmlhttp.responseText);
            $('#tablecontainer').show();
            $('section').html(values_array[0]);
            $('#user_table').DataTable( {
                "aaSorting": [],
                "pageLength": 10,
                "sPaginationType":"full_numbers"
            });
            $(".preloader").hide();
            $('#conformorder').hide();
        }
    }
    var Option="User_Quotationlist";
    xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option);
    xmlhttp.send();
    //**********ENQUIRY DETAILS INITIALIZE FUNCTION END*************//
    //**********ENQUIRY DETAILS USER QUOTATION VIEW FUNCTION START*************//
    var pdfstatus;
    $(document).on("click",'.userquotationview', function (){
        $(".preloader").show();
        var id=this.id;
        var splitid=id.split('/');
        var rowid=splitid[1];
        $('#temp_id').val(rowid);
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var values_array=JSON.parse(xmlhttp.responseText);
                $(".preloader").hide();
                $('#table_UserQuotationview').show();
                var Datas=values_array[0];
                $('#Job_tilte').val(Datas[0]);
                if(Datas[0]=='' || Datas[0]==null){$('#div_job_title').hide();}else{$('#div_job_title').show();}
                $('#Item').val(Datas[1]);
                if(Datas[1]=='' || Datas[1]==null){$('#div_typeof_print').hide();}else{$('#div_typeof_print').show();}
                $('#Size').val(Datas[2]);
                if(Datas[2]=='' || Datas[2]==null){$('#div_size').hide();}else{$('#div_size').show();}
                $('#Papertype').val(Datas[3]);
                if(Datas[3]=='' || Datas[3]==null){$('#div_Papertype').hide();}else{$('#div_Papertype').show();}
                $('#Paperweight').val(Datas[4]);
                if(Datas[4]=='' || Datas[4]==null){$('#div_Paperweight').hide();}else{$('#div_Paperweight').show();}
                $('#Printingmethod').val(Datas[5]);
                if(Datas[5]=='' || Datas[5]==null){$('#div_Printingmethod').hide();}else{$('#div_Printingmethod').show();}
                $('#Printingprocess').val(Datas[6]);
                if(Datas[6]=='' || Datas[6]==null){$('#div_Printingprocess').hide();}else{$('#div_Printingprocess').show();}
                $('#Treatmentprocess').val(Datas[7]);
                if(Datas[7]=='' || Datas[7]==null){$('#div_Treatmentprocess').hide();}else{$('#div_Treatmentprocess').show();}
                $('#Finishingprocess').val(Datas[8]);
                if(Datas[8]=='' || Datas[8]==null){$('#div_Finishingprocess').hide();}else{$('#div_Finishingprocess').show();}
                $('#Bindingprocess').val(Datas[9]);
                if(Datas[9]=='' || Datas[9]==null){$('#div_Bindingprocess').hide();}else{$('#div_Bindingprocess').show();}
                $('#Quantity').val(Datas[10]);
                if(Datas[10]=='' || Datas[10]==null){$('#div_Quantity').hide();}else{$('#div_Quantity').show();}
                $('#EnquiryDate').val(Datas[11]);
                if(Datas[11]=='' || Datas[11]==null || Datas[11]=='0000-00-00'){$('#div_EnquiryDate').hide();}
                else{
                    $('#div_EnquiryDate').show();
                    var date=DTFormTable_DateFormat(Datas[11]);
                    $('#EnquiryDate').val(date);
                }
                $('#DeliveryLocation').val(Datas[12]);
                if(Datas[12]=='' || Datas[12]==null){$('#div_DeliveryLocation').hide();}else{$('#div_DeliveryLocation').show();}
                $('#Remarks').val(Datas[13]);
                if(Datas[13]=='' || Datas[13]==null){$('#div_Remarks').hide();}else{$('#div_Remarks').show();}
                $('#tempstatus').val(Datas[15]);
                $('#Amount').val(Datas[14]);
                $('#tablecontainer').hide();
                $('#UQT_Bacttolist').show();
                $('#pdgdiv').show();
                $('#conformorder').show();
                $('#userquotationviewstatus').text("STATUS : "+values_array[1]);
                if(values_array[1]=='DELIVERED' || values_array[1]=='CANCELLED' || values_array[1]=='CONFIRMED ORDER')
                {
                    $('#conformorder').hide()
                    $('#btn_conform_order').hide();
                    $('#btn_Cancel_order').hide();
                }
                else
                {
                    $('#conformorder').show()
                    $('#btn_conform_order').show();
                    $('#btn_Cancel_order').show();
                }
                pdfstatus=values_array[1];
                if(values_array[1]=='DELIVERED')
                {   $('#pdfmessage').attr('title', deliveredpdfmessage);}
                if(values_array[1]=='CONFIRMED ORDER')
                { $('#pdfmessage').attr('title', confirmpdfmessage);}
                if(values_array[1]=='CANCELLED' || values_array[1]=='QUOTATION UPDATED')
                {
                    { $('#pdfmessage').attr('title', '');}
                }
                $("#btn_conform_order").attr("disabled", "disabled");
                $('#uploadimages').html('');
                var data=values_array[2].split('/');
                if(data!=0)
                {
                    var headerdata='';
                    for(var i=0;i<data.length;i++)
                    {
                        var rowid=i+1;
                        if(i==0)
                        {
                            headerdata += '<div class="row form-group">';
                            headerdata += '<div class="col-md-3">';
                            headerdata += '<label>UPLOADED IMAGES</label>';
                            headerdata += '</div>';
                            headerdata += '<div class="col-md-5">';
                            headerdata += '<a href="download.php?filename=' + data[i] + '" class="links">' + rowid + '.' + data[i] + '</a></div>';
                            headerdata += '</div></div>';
                        }
                        else
                        {
                            headerdata += '<div class="row form-group">';
                            headerdata += '<div class="col-md-3">';
                            headerdata += '<label></label>';
                            headerdata += '</div>';
                            headerdata += '<div class="col-md-5">';
                            headerdata += '<a href="download.php?filename=' + data[i] + '" class="links">' + rowid + '.' + data[i] + '</a></div>';
                            headerdata += '</div></div>';
                        }
                    }
                    $('#uploadimages').append(headerdata);
                }
                var data=values_array[3].split('/');
                if(data!=0)
                {
                    var headerdata='';
                    for(var i=0;i<data.length;i++)
                    {
                        var rowid=i+1;
                        if(i==0)
                        {
                            headerdata += '<div class="row form-group">';
                            headerdata += '<div class="col-md-3">';
                            headerdata += '<label>POD IMAGES</label>';
                            headerdata += '</div>';
                            headerdata += '<div class="col-md-5">';
                            headerdata += '<a href="download.php?filename=' + data[i] + '" class="links">' + rowid + '.' + data[i] + '</a></div>';
                            headerdata += '</div></div>';
                        }
                        else
                        {
                            headerdata += '<div class="row form-group">';
                            headerdata += '<div class="col-md-3">';
                            headerdata += '<label></label>';
                            headerdata += '</div>';
                            headerdata += '<div class="col-md-5">';
                            headerdata += '<a href="download.php?filename=' + data[i] + '" class="links">' + rowid + '.' + data[i] + '</a></div>';
                            headerdata += '</div></div>';
                        }
                    }
                    $('#podimages').append(headerdata);
                }

            }
        }
        var Option="AdminQuotationView";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+rowid);
        xmlhttp.send();
    });
    function DTFormTable_DateFormat(inputdate){
        var string = inputdate.split("-");
        return string[2]+'-'+ string[1]+'-'+string[0];
    }
    //**********ENQUIRY DETAILS USER QUOTATION VIEW FUNCTION END*************//
    //**********ENQUIRY DETAILS USER QUOTATION VIEW BACK TO LIST FUNCTION START*************//
    $(document).on("click",'#UQT_Bacttolist', function (){
        $(".preloader").show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var values_array=JSON.parse(xmlhttp.responseText);
                $('#tablecontainer').show();
                $('section').html(values_array[0]);
                $('#user_table').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "sPaginationType":"full_numbers"
                });
                $(".preloader").hide();
                $('#conformorder').hide();
            }
        }
        var Option="User_Quotationlist";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option);
        xmlhttp.send();
        $('#tablecontainer').show();
        $('#UQT_Bacttolist').hide();
        $('#updatetablecontent').hide();
        $('#table_UserQuotationview').hide();
        $('#pdgdiv').hide();
        $('#conformorder').hide();
        $('#tablecontent').hide();
    });
    //**********ENQUIRY DETAILS USER QUOTATION VIEW BACK TO LIST FUNCTION END*************//
    //**********ENQUIRY DETAILS USER QUOTATION PDF FUNCTION START*************//
    $(document).on("click",'.UserQuotationpdf', function (){
        if(pdfstatus=='DELIVERED')
        {
            var pdfmessage=deliveredpdfmessage;
            show_msgbox("JHUB",pdfmessage,"success","pdfconfirm");
        }
        if(pdfstatus=='CONFIRMED ORDER')
        {
            var pdfmessage=confirmpdfmessage;
            show_msgbox("JHUB",pdfmessage,"success","pdfconfirm");
        }
        if(pdfstatus=='CANCELLED' || pdfstatus=='QUOTATION UPDATED')
        {
            var QT_id=$('#temp_id').val();
            var url=document.location.href='COMMON_PDF.php?inputValOne='+QT_id;
        }
    });

    $(document).on('click','.pdfConfirm',function(){
        var QT_id=$('#temp_id').val();
        var url=document.location.href='COMMON_PDF.php?inputValOne='+QT_id;
    });
    //**********ENQUIRY DETAILS USER QUOTATION PDF FUNCTION END*************//
      //**********ENQUIRY DETAILS CONFORMED ORDER FUNCTION START*************//
    $(document).on("click",'#btn_conform_order', function (){
    show_msgbox("JHUB",purchaseconfirmmessage,"success","delete");
    });
    $(document).on("click",'.Confirm', function (){
        $(".preloader").show();
        var files=$('#uploader').plupload('getFiles');
        var filesarray=[];
        if(files.length==0)
        {
            filesarray.push(files.name);
        }
        else
        {
            for(var i=0;i<files.length;i++)
            {
                var name=files[i].name;
                if(i==0){filesarray=name}else{filesarray=filesarray+'/'+name}
            }
        }
        var rowid=$('#temp_id').val();
        var purchaseorderkey=$('#Purchaseordernumber').val();
        data={"Option":"OrderUpdate","Uedid":rowid,"Files":filesarray,"Key":purchaseorderkey};
        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":"OrderUpdate","Uedid":rowid,"Files":filesarray,"Key":purchaseorderkey},
            success: function(msg){
                $(".preloader").hide();
                var values_array=msg;
                $('#tablecontainer').show();
                $('section').html(values_array);
                $('#user_table').DataTable({
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });

                $('#UQT_Bacttolist').hide();
                $('#table_UserQuotationview').hide();
                $('#pdgdiv').hide();
                $('#updateform').hide();
                $('#conformorder').hide();
                $(".preloader").hide();
                show_msgbox("JHUB",confirmorder,"success",false);
                var uploader = $('#uploader').plupload('getUploader');
                uploader.splice();
                $('#Purchaseordernumber').val('');
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
    });
    //**********ENQUIRY DETAILS CONFORMED ORDER FUNCTION END*************//
    //**********ENQUIRY DETAILS CANCEL ORDER FUNCTION START*************//
    $(document).on("click",'#btn_Cancel_order', function (){
        var rowid=$('#temp_id').val();
        $(".preloader").show();
        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":"RevisedQuotation","Uedid":rowid},
            success: function(msg){
                $(".preloader").hide();
                var values_array=msg;
                $('#tablecontainer').show();
                $('section').html(values_array);
                $('#user_table').DataTable({
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });

                $('#UQT_Bacttolist').hide();
                $('#table_UserQuotationview').hide();
                $('#pdgdiv').hide();
                $('#updateform').hide();
                $('#conformorder').hide();
                $(".preloader").hide();
                show_msgbox("JHUB",quotationrevise,"success",false)
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
    });
    //**********ENQUIRY DETAILS CANCEL ORDER FUNCTION END*************//
    //**********ENQUIRY DETAILS CONFIRM ORDER BUTTON VALIDATION FUNCTION START*************//

    $(document).on("change",'#Purchaseordernumber', function (){
        if($('#Purchaseordernumber').val()!='')
        {
            $("#btn_conform_order").removeAttr("disabled");
        }
        else
        {
            $("#btn_conform_order").attr("disabled", "disabled");
        }
    });
   //**********ENQUIRY DETAILS CONFIRM ORDER BUTTON VALIDATION FUNCTION END*************//
    //**************NEW ENQUIRY FROM FILE UPLOAD PROCESS START*********************//
    $("#uploader").plupload({
        // General settings
        runtimes : 'html5,flash,silverlight,html4',
        url : 'upload.php',

        // User can upload no more then 20 files in one go (sets multiple_queues to false)
        max_file_count: 20,

        chunk_size: '1mb',

        // Resize images on clientside if we can
        resize : {
            width : 200,
            height : 200,
            quality : 90,
            crop: true // crop to exact dimensions
        },

        filters : {
            // Maximum file size
            max_file_size : '1000mb',
            // Specify what files to browse for
            mime_types: [
                {title : "Image files", extensions : "jpg,gif,png,pdf"},
                {title : "Zip files", extensions : "zip"}
            ]
        },

        // Rename files by clicking on their titles
        rename: true,

        // Sort files
        sortable: true,

        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
        dragdrop: true,

        // Views to activate
        views: {
            list: true,
            thumbs: true, // Show thumbs
            active: 'thumbs'
        },

        // Flash settings
        flash_swf_url : '../../js/Moxie.swf',

        // Silverlight settings
        silverlight_xap_url : '../../js/Moxie.xap'
    });
    //**********BUTTON VALIDATION*****************//
    $('.Btn_validation').click(function(){
        if($('#Purchaseordernumber').val()!='')
        {
             $('#btn_conform_order').removeAttr("disabled");
        }
        else
        {
            $('#btn_conform_order').attr('disabled','disabled');
        }

    });
    $('.Btn_add_validation').click(function(){

        $('#btn_conform_order').attr('disabled','disabled');
    });
    //**************NEW ENQUIRY FROM FILE UPLOAD PROCESS END*********************//
});
</script>
<body class="bg-theme">
<div class="container">
    <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <div class="panel-heading" style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold">ALL QUOTATIONS DETAILS </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal">
                <div style="padding-left: 20px;" id="tablecontainer" class="table-responsive" hidden>
                    <section >
                    </section>
                </div>
                <div id="pdgdiv" hidden><a href="#" class="UserQuotationpdf"><img src="images/pdfimage.png" id="pdfmessage" style="max-width:60px;max-height:60px;" alt="jhub"></a><input type="hidden" id="temp_id"></div>

                <div class="col-lg-9 col-lg-offset-10">
                    <button type="button" id="UQT_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>   BACK</button>
                </div>
                <div style="padding-left: 15px" id="table_UserQuotationview" hidden>
                    <div><h3 style="color:#337ab7;font-weight: bold" id="userquotationviewstatus"></h3></div>
                    <br>
                    <form class="form-horizontal" id="Quotation_Save">
                        <div class="row form-group" id="div_job_title">
                            <div class="col-md-3">
                                <label>JOB TITLE</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control Validation" id="Job_tilte" name="Job_tilte" maxlength="50" placeholder="Job Title" readonly/>
                            </div>
                        </div>
                        <div class="row form-group" id="div_typeof_print">
                            <div class="col-md-3">
                                <label>TYPE OF PRINT</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Item" name="Item" placeholder="Item" readonly/>
                            </div>

                        </div>
                        <div class="row form-group" id="div_size">
                            <div class="col-md-3">
                                <label>SIZE</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Size" name="Size" placeholder="Size" readonly/>
                            </div>

                        </div>
                        <div class="row form-group" id="div_Papertype">
                            <div class="col-md-3">
                                <label>PAPER TYPE</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Papertype" name="Papertype" placeholder="PaperType" readonly/>
                            </div>

                        </div>
                        <div class="row form-group" id="div_Paperweight">
                            <div class="col-md-3">
                                <label>PAPER WEIGHT</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Paperweight" name="Paperweight" placeholder="PaperWeight" readonly/>
                            </div>
                        </div>
                        <div class="row form-group" id="div_Printingmethod">
                            <div class="col-md-3">
                                <label>PRINTING METHOD</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Printingmethod" name="Printingmethod" placeholder="PrintingMethod" readonly/>
                            </div>
                        </div>
                        <div class="row form-group" id="div_Printingprocess">
                            <div class="col-md-3">
                                <label>PRINTING PROCESS</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation"  id="Printingprocess" name="Printingprocess" placeholder="PrintingProcess" readonly/>
                            </div>

                        </div>
                        <div class="row form-group" id="div_Treatmentprocess">
                            <div class="col-md-3">
                                <label>TREATMENT PROCESS</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Treatmentprocess" name="Treatmentprocess" placeholder="TreatmentProcess" readonly/>
                            </div>

                        </div>
                        <div class="row form-group" id="div_Finishingprocess">
                            <div class="col-md-3">
                                <label>FINISHING PROCESS</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Finishingprocess" name="Finishingprocess" placeholder="FinishingProcess" readonly/>
                            </div>

                        </div>
                        <div class="row form-group" id="div_Bindingprocess">
                            <div class="col-md-3">
                                <label>BINDING PROCESS</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Bindingprocess" name="Bindingprocess" placeholder="BindingProcess" readonly/>
                            </div>

                        </div>
                        <div class="row form-group" id="div_Quantity">
                            <div class="col-md-3">
                                <label>QUANTITY</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="Quantity" maxlength="10" name="Quantity" placeholder="Quantity" readonly/>
                            </div>
                        </div>
                        <div class="row form-group" id="div_EnquiryDate">
                            <div class="col-md-3">
                                <label>DATE REQUIRED</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation" id="EnquiryDate" name="EnquiryDate" placeholder="Date" readonly/>
                            </div>
                        </div>
                        <div class="row form-group" id="div_DeliveryLocation">
                            <div class="col-md-3">
                                <label>DELIVERY LOCATION</label>
                            </div>
                            <div class="col-md-3">
                                <textarea class="form-control Validation" rows="2" id="DeliveryLocation" name="DeliveryLocation" placeholder="Delivery Location" readonly></textarea>
                            </div>
                        </div>
                        <div class="row form-group" id="div_Remarks">
                            <div class="col-md-3">
                                <label>REMARKS/SPECIAL REQUEST</label>
                            </div>
                            <div class="col-md-3">
                                <textarea class="form-control Validation" rows="2" id="Remarks" name="Remarks" placeholder="Remarks" readonly></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>AMOUNT</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation amountonly" id="Amount" maxlength="10" name="Amount" readonly placeholder="Amount"/>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div id="uploadimages"></div>
                    <div  id="podimages"></div>
                    <br>
                </div>
                <div style="padding-left: 15px" id="conformorder" hidden>
                    <br>
                    <div class="row form-group">
                        <div class="col-md-3" style="padding-left: 20px">
                            <label>PURCHASE ORDER NUMBER<span class="labelrequired"><em>*</em></span></label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control Validation" id="Purchaseordernumber" name="Purchaseordernumber" maxlength="50" placeholder="Purchase Order Number"/>
                        </div>
                    </div>
                        <div style="max-width: 1000px" id="uploader">
                        </div>
                        <br>
                    <div class="col-lg-6">
                        <button type="button" id="btn_conform_order" class="btn submit_btn" disabled >CONFIRM ORDER</button>
                        <button type="button" id="btn_Cancel_order" class="btn submit_btn">CANCEL</button>
                    </div>
                </div>
                <div id="updateform" hidden>
                    <div class="col-lg-9 col-lg-offset-10">
                        <button type="button" id="update_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>    BACK</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</body>
</HTML>