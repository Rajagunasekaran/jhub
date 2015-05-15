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
    var pdfmessage;
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
            pdfmessage=value_array[15]
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
                "responsive": true,
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
                $('section1').html(values_array[0]);
                $('#quotation_view').DataTable( {
                    "bSort" : false
                });
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
                $('#pdfmessage').attr('title', pdfmessage);
                $("#btn_conform_order").attr("disabled", "disabled");
            }
        }
        var Option="AdminQuotationView";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+rowid);
        xmlhttp.send();
    });
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
                    "responsive": true,
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
        show_msgbox("JHUB",pdfmessage,"success","pdfconfirm");
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
                show_msgbox("JHUB",confirmorder,"success",false)
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
                <div style="padding-left: 15px" id="table_UserQuotationview" class="table-responsive" hidden>
                    <div><h3 style="color:#337ab7;font-weight: bold" id="userquotationviewstatus"></h3></div>
                    <section1>
                    </section1>
                </div>
                <div id="conformorder" hidden>
                    <br>
                    <div class="row form-group">
                        <div class="col-md-3">
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