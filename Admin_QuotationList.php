<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("adminmenu.php");
?>
<HTML>
<script>
    $(document).ready(function(){
        $('#adminquotationlist').css("color", "#73c20e");
        $(".preloader").show();
        $('#QT_Bacttolist').hide();
        var imageerror;
        $.ajax({
            type: "POST",
            url: "DB_Error_Msg.php",
            data:{"Option":'ERROR'},
            success: function(data){
                $('.preloader').hide();
                var value_array=JSON.parse(data);
                imageerror=value_array[11];
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });

        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":'AdminQuotationList'},
            success: function(data){
                var values_array=data;
                $('#tablecontainer').show();
                $('section').html(values_array);
                $('#user_table').DataTable( {
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
        $(document).on("keyup",'.decimal', function (){
            var val = $(this).val();
            if(isNaN(val)){
                val = val.replace(/[^0-9\.]/g,'');
                if(val.split('.').length>2)
                    val =val.replace(/\.+$/,"");
            }
            $(this).val(val);
        });
        $(document).on("change",'.Quotationprice', function (){
            var id=this.id;
            var currentvalue=$('#'+id).val();
            var amtsplit=currentvalue.split('.');
            if(amtsplit[1]=="" || amtsplit[1]==undefined)
            {
                $('#'+id).val(currentvalue +'.00')
                if(currentvalue==""){$('#'+id).val('')}
            }
            var total=0;
            var x =  $('#quotation_table>tbody>tr').length-1;
            for(var j=1;j<=x;j++)
            {
                var currentamt=$('#QT_'+j).val();
                if(currentamt!="")
                {
                    if(total==0)
                    {total=currentamt}
                    else
                    {
                        total=parseFloat(total)+parseFloat(currentamt)
                    }
                }
            }
            var finalamount=total.toString();
            $('#quotationtotal').text(total).html();
        });
        $(document).on("click",'#QT_Bacttolist', function (){
            $('#tablecontainer').show();
            $('#QT_Bacttolist').hide();
            $('#table_Quotationview').hide();
            $('#pdgdiv').hide();
            $('#recverdetails').hide();
        });
        $(document).on('click','.Quotationpdf',function(){
            var QT_id=$('#temp_id').val();
            var url=document.location.href='COMMON_PDF.php?inputValOne='+QT_id;
        });
        $(document).on('click','.showalldetails',function(){
            var id=this.id;
            var splitid=id.split('/');
            var enquiryid=splitid[1];
            $.ajax({
                type: "POST",
                url: "DB_EnquiryDetails.php",
                data:{"Option":'AdminAllLPQuotationList',"Data":enquiryid},
                success: function(data){
                    $(".preloader").hide();
                    var values_array=data;
                    $('#table_AllQuotationview').show();
                    $('section2').html(values_array);
                    $('#All_recverdetails').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                 },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            });
            $('#recverdetails').show();
            $('#tablecontainer').hide();
            $('#QT_Bacttolist').hide();
            $('#table_Quotationview').hide();
            $('#pdgdiv').hide();
        });

        $(document).on("click",'#AllQT_Bacttolist', function (){
            $('#tablecontainer').show();
            $('#QT_Bacttolist').hide();
            $('#table_Quotationview').hide();
            $('#pdgdiv').hide();
            $('#recverdetails').hide();
        });
        $(document).on("click",'#QT_LPBacttolist', function (){
            $('#tablecontainer').hide();
            $('#QT_Bacttolist').hide();
            $('#table_Quotationview').hide();
            $('#pdgdiv').hide();
            $('#recverdetails').show();
            $('#table_LPQuotationview').hide();
        });
        $(document).on("click",'#Btn_Delivered', function (){
            var QT_id=$('#temp_id').val();
            var FormElement=document.getElementById('quotationdetails');
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#tablecontainer').show();
                    $('section').html(values_array);
                    $('#user_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#QT_Bacttolist').hide();
                    $('#table_Quotationview').hide();
                    $('#pdgdiv').hide();
                    $('#recverdetails').hide();
                }
            }
            var Option="Delivered";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+QT_id);
            xmlhttp.send(new FormData(FormElement));
        });
    });
</script>
<body class="bg-theme">
<div class="container">
<div class="panel panel-success" >
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#73c20e;">
        <h3 class="panel-title" style="color:#ffffff;font-weight: bold">UPDATED QUOTATION DETAILS</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="quotationdetails">
            <div  id="tablecontainer" style="padding-left: 20px" hidden>
                <section >
                </section>
            </div>
            <div id="pdgdiv" hidden><a href="#" class="Quotationpdf"><img src="images/pdfimage.jpg" alt="StarHub"></a><input type="hidden" id="temp_id" name="temp_id"></div>

            <div class="col-lg-9 col-lg-offset-10">
                <button type="button" id="QT_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>     BACK</button>
            </div>
            <div style="padding-left: 15px" id="table_Quotationview" class="table-responsive" hidden>
                <div><h3 style="color:#337ab7;font-weight: bold" id="adminquotationviewid"></h3></div>
                <section1>
                </section1>
                <div id='fileuploadform'>
                <div><label>POD FILES</label>
                    <input type="file" name="fileToUpload"  class="fileextensionchk" id="fileToUpload">
                </div>
                </div>
                <div class="col-lg-5 col-lg-offset-6">
                    <button type="button" id="Btn_Delivered" class="btn submit_btn">DELIVERED</button>
                </div>
            </div>
            <div id="recverdetails" hidden>
                <div class="col-lg-9 col-lg-offset-10">
                    <button type="button" id="AllQT_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>     BACK</button>
                </div>
                <div  id="table_AllQuotationview">
                    <div><h3 style="color:#337ab7;font-weight: bold">QUOTATION DETAILS</h3></div>
                    <section2>
                    </section2>
                </div>
            </div>
            <div  id="table_LPQuotationview" style="max-width:1000px"  hidden>
                <div id="lppdgdiv" hidden><a href="#" class="Quotationpdf"><img src="images/pdfimage.jpg" alt="StarHub"></a><input type="hidden" id="lptemp_id"></div>
                <div class="col-lg-9 col-lg-offset-10">
                    <button type="button" id="QT_LPBacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>     BACK</button>
                </div>
                <div><h3 style="color:#337ab7;font-weight: bold" id="tablelpquotationstatus"></h3></div>
                <section4>
                </section4>
            </div>
        </form>
    </div>
</div>
    </div>
</body>
</HTML>