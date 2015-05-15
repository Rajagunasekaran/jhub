<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("adminmenu.php");
?>
<HTML>
<div style="max-width: 1300px !important;">
<script>
    $(document).ready(function(){
        $('#Adminenquirylist').css("color", "#73c20e");
        $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:5,imaginary:2}});
        $('.preloader').show();
        $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
        var qo_update;
        $.ajax({
            type: "POST",
            url: "DB_Error_Msg.php",
            data:{"Option":'ERROR'},
            success: function(data){
                $('.preloader').hide();
               var value_array=JSON.parse(data);
                 qo_update=value_array[2];
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });



        $(".preloader").show();
        $('#Quotation_Save').hide();
        $('#EQ_Bacttolist').hide();
        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":'AdminEnquiryList'},
            success: function(data){
                var values_array=data;
                $('#newenquiry').show();
                $('#allquotations').html(values_array);
                $('#user_table').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#EQ_Bacttolist').hide();
                $(".preloader").hide();
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
        $(document).on("click",'.Quotationcreation', function (){
            $(".preloader").show();
            $('#newenquiry').hide();
            var id=this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#quotationupdate').html(values_array[0]);
                    $('#quotation_view').DataTable( {
                        "bSort" : false
                    });
                    $('#Quotation_Save').show();
                    $('#EQ_Bacttolist').show();
                    $('#Enquiry_table').show();
                    $(".preloader").hide();
                    $('#Quotation_Save').attr('disabled','disabled');
                    $('#statuslabel').text("STATUS : "+values_array[2]);
                    if(values_array[1]!=null && values_array[1]!=0 && values_array[1]!=0.00 )
                    {
                    $('#tempstatus').val(4)
                    }
                    else
                    {
                    $('#tempstatus').val(1)
                    }
                }
            }
            var Option="AdminQuotation";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&UEDID="+rowid);
            xmlhttp.send();

        });
            $(document).on("keyup",'.numbersOnly', function (){
            if (this.value != this.value.replace(/[^0-9\.]/g, ''))
            {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });
        $(document).on("change",'.Quotationprice', function (){
            var id=this.id;
            var currentvalue=$('#'+id).val();
            var amtsplit=currentvalue.split('.');
            if(amtsplit[1]=="" || amtsplit[1]==undefined)
            {
                $('#'+id).val(currentvalue +'.00')
                if(currentvalue=="")
                {$('#'+id).val('')}
            }
            else
            {
              var real= amtsplit[0];
              var imag=amtsplit[1].substring(0, 2)
              var amountvalue=real+'.'+imag;
              $('#'+id).val(amountvalue)
            }
            var total=0;
            var x =  $('#quotation_view>tbody>tr').length-1;
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
            if(x==1)
            {
            $('#quotationtotal').text(total).html();
            }
            else
            {
                $('#quotationtotal').text(total.toFixed(2)).html();
            }
        });
        $(document).on("change",'.Quotationamountvalidation', function (){
            var x =  $('#quotation_view>tbody>tr').length-1;
            var amountflag=0;
            for(var j=1;j<=x;j++)
            {
                var currentamt=$('#QT_'+j).val();
                if(currentamt!='' && currentamt!=0.00)
                {
                    amountflag++;
                }
            }
            if(x!=amountflag)
            {
              $('#Quotation_Save').attr('disabled','disabled');
            }
            else
            {
                $('#Quotation_Save').removeAttr("disabled");
            }
        });
        $(document).on("click",'#Quotation_Save', function (){
            $(".preloader").show();
            var productrefTab = document.getElementById("quotation_view");
            var pricearray=[];
            for ( var i = 1; row = productrefTab.rows[i]; i++ )
            {
                var price_array=[];
                var amount=$('#QT_'+i).val();
                var id=$('#QTtemp_'+i).val();
                price_array.push(id,amount);
                pricearray.push(price_array);
            }
            var status=$('#tempstatus').val();
            $.ajax({
                type: "POST",
                url: "DB_EnquiryDetails.php",
                data:{"Option":'AdminQuotationupdation',"Data":pricearray,"Status":status},
                success: function(data){
                    var values_array=data;
                    $('section').html(values_array);
                    $('#user_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#newenquiry').show();
                    $('#EQ_Bacttolist').hide();
                    $('#Enquiry_table').hide();
                    $('#Quotation_Save').hide();

                    show_msgbox("JHUB",qo_update,"success",false)
                    $(".preloader").hide();
                  },
                error: function(data){
                    alert('error in getting'+data);
                }
            });
        });
        $(document).on("click",'#EQ_Bacttolist', function (){
            $('#newenquiry').show();
            $('#EQ_Bacttolist').hide();
            $('#Enquiry_table').hide();
            $('#Quotation_Save').hide();
        });
    });
</script>
<body class="bg-theme pagesize">
<div class="container">
<div class="panel panel-info" >
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#73c20e;color:black;">
        <h3 class="panel-title" style="color:#ffffff;font-weight: bold">NEW ENQUIRY DETAILS</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div style="padding-left: 20px;" class="table-responsive" id="newenquiry" hidden>
                <section id="allquotations">
                </section>
            </div>
            <div class="col-lg-9 col-lg-offset-10">
                <button type="button" id="EQ_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>   BACK</button>
            </div>
            <br>
            <div style="padding-left: 25px" id="Enquiry_table" class="table-responsive" hidden>
                <div><input type="hidden" id="tempstatus" name="tempstatus" class="amountonly"></div>
                <div><h3 style="color:#337ab7;font-weight: bold" id="statuslabel"></h3></div>
                <section id="quotationupdate">
                </section>
            </div>
            <br>
            <div class="col-lg-1 col-lg-offset-2">
                <button type="button" id="Quotation_Save" class="btn submit_btn" disabled>SEND QUOTATION</button>
            </div>

        </form>
    </div>
</div>
</div>
</body>
</div>
</HTML>