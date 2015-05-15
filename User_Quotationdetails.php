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
    var en_save;
    var en_update;
    var qo_update;
    var lg_create;
    var lg_update;
    var order_conform;
    var confirmorder;
    var quotationrevise;

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
                if(values_array[1]=='DELIVERED' || values_array[1]=='CANCEL' || values_array[1]=='CONFIRMED ORDER')
                {
                    $('#btn_conform_order').hide();
                    $('#btn_revised_order').hide();
                }
                else
                {
                    $('#btn_conform_order').show();
                    $('#btn_revised_order').show();
                }
            }
        }
        var Option="AdminQuotationView";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+rowid);
        xmlhttp.send();

    });

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
    $(document).on('click','.UserQuotationpdf',function(){
        var QT_id=$('#temp_id').val();
        var url=document.location.href='COMMON_PDF.php?inputValOne='+QT_id;
    });
      //CONFORMED ORDER
    $(document).on("click",'#btn_conform_order', function (){
        $(".preloader").show();
        var rowid=$('#temp_id').val();
        data={"Option":"OrderUpdate","Uedid":rowid};
        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":"OrderUpdate","Uedid":rowid},
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
    //REVISED QUOTATION
    $(document).on("click",'#btn_revised_order', function (){
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


});
</script>
<body class="bg-theme">
<div class="container">
    <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <div class="panel-heading" style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold">UPDATED AND DELIVERED QUOTATION DETAILS </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal">
                <div style="padding-left: 20px" id="tablecontainer"  hidden>
                    <section >
                    </section>
                </div>
                <div id="pdgdiv" hidden><a href="#" class="UserQuotationpdf"><img src="images/pdfimage.jpg" alt="StarHub"></a><input type="hidden" id="temp_id"></div>

                <div class="col-lg-9 col-lg-offset-10">
                    <button type="button" id="UQT_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>   BACK</button>
                </div>
                <div style="padding-left: 15px" id="table_UserQuotationview" class="table-responsive" hidden>
                    <div><h3 style="color:#337ab7;font-weight: bold" id="userquotationviewstatus"></h3></div>
                    <section1>
                    </section1>
                </div>
                <div id="conformorder" hidden>
                    <div class="col-lg-6">
                        <button type="button" id="btn_conform_order" class="btn submit_btn" >CONFIRM ORDER</button>
                        <button type="button" id="btn_revised_order" class="btn submit_btn">CANCEL</button>
                    </div>
                </div>
                <div id="updateform" hidden>
                    <div class="col-lg-9 col-lg-offset-10">
                        <button type="button" id="update_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>    BACK</button>
                    </div>
                </div>
                <div id="updatetablecontent" hidden>

                    <section2>

                    </section2>
                    <div id="imagecontainer">

                    </div>
                    <div id="uploader" style="max-width: 1000px">
                    </div><br>
                    <div class="col-lg-3 col-lg-offset-4">
                        <button type="button" id="update_btn_enquiry" class="btn submit_btn">UPDATE ENQUIRY</button>
                    </div>
                </div>
        </div>

        </form>
    </div>
</div>
</div>
</body>
</HTML>