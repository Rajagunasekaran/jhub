<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("adminmenu.php");
?>
<HTML>
<script>
    $(document).ready(function(){
        $(".preloader").show();
        $('#admindeliveredlist').css("color", "#73c20e");
        $('#QT_Bacttolist').hide();
        var imageerror;
        var delivered;
        $.ajax({
            type: "POST",
            url: "DB_Error_Msg.php",
            data:{"Option":'ERROR'},
            success: function(data){
                $('.preloader').hide();
                var value_array=JSON.parse(data);
                imageerror=value_array[11];
                delivered=value_array[7];
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });

        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":'AdminDeliveredQuotationList'},
            success: function(data){

                var values_array=data;
                $('#tablecontainer').show();
                $('section').html(values_array);
                $('#Deliveredlist_table').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#QT_Bacttolist').hide();
                $('#pdgdiv').hide();
                $('#recverdetails').hide();
                $("#fileToUpload").val('');
                $(".preloader").hide();
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
        $(document).on("click",'.QuotationView', function (){
            $(".preloader").show();
            var id=this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            $('#temp_id').val(rowid);
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#table_Quotationview').show();
                    $('section1').html(values_array[0]);
                    $('#quotation_view').DataTable( {
                        "bSort" : false
                    });
                    $('#adminquotationviewid').text("STATUS : "+values_array[1])
                    $(".preloader").hide();
                    $('#tablecontainer').hide();
                    $('#QT_Bacttolist').show();
                    $('#pdgdiv').show();
                    $('#recverdetails').hide();
                    if(values_array[1]=='CONFIRMED ORDER')
                    {
                        $('#Btn_Delivered').show();
                        $('#fileuploadform').show();
                    }
                    else
                    {
                        $('#Btn_Delivered').hide();
                        $('#fileuploadform').hide();
                    }
                }
            }
            var Option="AdminQuotationView";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+rowid);
            xmlhttp.send();

        });
        $(document).on("click",'#QT_Bacttolist', function (){
            $('#tablecontainer').show();
            $('#fileuploadform').hide();
            $('#QT_Bacttolist').hide();
            $('#table_Quotationview').hide();
            $('#pdgdiv').hide();
            $('#recverdetails').hide();
        });
        $(document).on('click','.Quotationpdf',function(){
            var QT_id=$('#temp_id').val();
            var url=document.location.href='COMMON_PDF.php?inputValOne='+QT_id;
        });
        $(document).on("click",'#Btn_Delivered', function (){
            var QT_id=$('#temp_id').val();
            $(".preloader").show();
            var FormElement=document.getElementById('quotationdetails');
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
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#tablecontainer').show();
                    $('section').html(values_array);
                    $('#Deliveredlist_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#QT_Bacttolist').hide();
                    $('#table_Quotationview').hide();
                    $('#fileuploadform').hide();
                    $('#pdgdiv').hide();
                    show_msgbox("JHUB",delivered,"success",false);
                    $('#recverdetails').hide();var uploader = $('#uploader').plupload('getUploader');
                    uploader.splice();
                    $(".preloader").hide();
                }
            }
            var Option="Delivered";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+QT_id+"&Files="+filesarray);
            xmlhttp.send(new FormData(FormElement));
        });
        $('.Btn_validation').click(function(){
            $('#Btn_Delivered').removeAttr("disabled");
        });
        $('.Btn_add_validation').click(function(){
            $('#Btn_Delivered').attr('disabled','disabled');
        });
    });
</script>
<body class="bg-theme">
<div class="container">
    <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <div class="panel-heading" style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold">DELIVERED ORDER DETAILS</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id="quotationdetails">
                <div  id="tablecontainer" class="table-responsive" style="padding-left: 20px" hidden>
                    <section >
                    </section>
                </div>
                <div id="pdgdiv" hidden><a href="#" class="Quotationpdf"><img src="images/pdfimage.png" style="max-width:60px;max-height:60px;" alt="jhub"></a><input type="hidden" id="temp_id" name="temp_id"></div>

                <div class="col-lg-9 col-lg-offset-10">
                    <button type="button" id="QT_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>     BACK</button>
                </div>
                <div style="padding-left: 25px" id="table_Quotationview" class="table-responsive" hidden>
                    <div><h3 style="color:#337ab7;font-weight: bold" id="adminquotationviewid"></h3></div>
                    <section1>
                    </section1>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</HTML>