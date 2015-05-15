<?php
require_once("adminmenu.php");
?>
<HTML>

<script>
    $(document).ready(function(){
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
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var value_array=JSON.parse(xmlhttp.responseText);
                $(".preloader").hide();
                $('#enquirycontainer').show();
                $('section').html(value_array[0]);
                $('#example').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#conformcontainer').show();

                $('section2').html(value_array[1]);
                $('#example1').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#dashboard').show();
                $('section1').html(value_array[2]);
                $('#example2').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });

                $('#dashboard').show();
                $('#table_Quotationview').hide();
            }
        }
        var Option="AdminNotificationList";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option);
        xmlhttp.send();
        $(document).on("keyup",'.numbersOnly', function (){
            if (this.value != this.value.replace(/[^0-9\.]/g, ''))
            {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });
        //ENQUIRY DASHBOARD
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
                    $(".preloader").hide();
                    $('#dashboard').show();
                    $('#Enquirytable').hide();
                    $('#table_Quotationview').hide();
                    show_msgbox("J-PRINT",qo_update,"success",false)
                },
                error: function(data){
                    alert('error in getting'+data);
                }
            });
        });
        $(document).on("click",'.enquiryviewdetails', function (){
            $(".preloader").show();
            var id=this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#Enquirytable').show();
                    $('section3').html(values_array[0]);
                    $('#quotation_view').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#dashboard').hide();
                    $('#table_Quotationview').hide();
                    $('#table_reorderQuotationview').hide();
                    $(".preloader").hide();
                    $('#Quotation_Save').attr('disabled','disabled');
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
        $ (document).on("click",'#DB_Bacttolist', function (){
                $('#dashboard').show();
                $('#Enquirytable').hide();
            $('#table_Quotationview').hide();
            $('#table_reorderQuotationview').hide();
        });
//QUOTATION DASHBOARD
        $(document).on("click",'.conformedviewdetails', function (){
            $(".preloader").show();
            var id=this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $(".preloader").hide();
                    $('#table_Quotationview').show();
                    $('section10').html(values_array[0]);
//                    $('#quotation_view').DataTable( {
//                        "aaSorting": [],
//                        "pageLength": 10,
//                        "responsive": true,
//                        "sPaginationType":"full_numbers"
//                    });
                    $('#dashboard').hide();
                    $('#Enquirytable').hide();
                    $('#table_Quotationview').show();
                    $('#table_reorderQuotationview').hide();
                }
            }
            var Option="AdminQuotationView";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+rowid);
            xmlhttp.send();
        });

        $(document).on("click",'.reorderviewdetails', function (){
            $(".preloader").show();
            var id=this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            $.ajax({
                type: "POST",
                url: "DB_EnquiryDetails.php",
                data:{"Option":'AdminROQuotationView',"Data":rowid},
                success: function(data){
                    $(".preloader").hide();
                    var values_array=data;
                    $('#table_reorderQuotationview').show();
                    $('section8').html(values_array);
                    $('#quotation_reorderview').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#dashboard').hide();
                    $('#Enquirytable').hide();
                    $('#table_Quotationview').hide();
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            });
        });

        $ (document).on("click",'#QT_Bacttolist', function (){
            $('#dashboard').show();
            $('#Enquirytable').hide();
            $('#table_Quotationview').hide();
            $('#table_reorderQuotationview').hide();
        });
        $ (document).on("click",'#QT_ROBacttolist', function (){
            $('#dashboard').show();
            $('#Enquirytable').hide();
            $('#table_Quotationview').hide();
            $('#table_reorderQuotationview').hide();
        });
      });
</script>
<body>
<div class="container">
<div class="panel panel-info">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#FF8C00;color:black;">
        <h3 class="panel-title" style="color:#ffffff;font-weight: bold">DASHBOARD</h3>
    </div>
    <div class="panel-body">
       <div id="dashboard">
        <div class="row">
            <div class="col-sm-6">
                <div height="50%">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="background-color:#FFFFFF;color:#337ab7;font-weight: bold">NEW ENQUIRES</h3>
                    </div>
                    <div class="panel-body" style="background-color:#FFFFFF;">
                    <div id="enquirycontainer">
                        <section>

                        </section>
                    </div>
                    </div>
                </div>
                <div height="50%">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="color:#337ab7;font-weight: bold">CANCEL ORDERS</h3>
                    </div>
                    <div id="nodata" style="color: red"></div>

                    <div class="panel-body" style="background-color:#FFFFFF;">

                    <div id="conformcontainer">
                        <section1>

                        </section1>
                    </div>
                   </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div height="50%">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="color:#337ab7;font-weight: bold">NEW CONFIRMED ORDERS</h3>
                    </div>
                    <div class="panel-body" style="background-color:#FFFFFF;">
                        <div id="reordercontainer">
                            <section2>

                            </section2>
                         </div>
                    </div>
                 </div>
                <div height="50%">
<!--                    <div class="panel-heading">-->
<!--                        <h3 class="panel-title" style="color:#337ab7;font-weight: bold">RECENT DELIVERED ORDERS</h3>-->
<!--                    </div>-->
<!--                    <div class="panel-body" style="background-color:#FFFFFF;">-->
<!--                        <div id="deliveredcontainer">-->
<!--                            <section9>-->
<!---->
<!--                            </section9>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
      </div>
        <div id="Enquirytable" style="max-width: 1000px"hidden>
            <div><input type="hidden" id="tempstatus" name="tempstatus"></div>
            <div class="col-lg-9 col-lg-offset-10">
                <button type="button" id="DB_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>    BACK</button>
            </div>
            <div><h3 style="color:#337ab7;font-weight: bold">NEW QUOTATION</h3></div>
            <section3>
            </section3>
            <div class="col-lg-3 col-lg-offset-4">
                <button type="button" id="Quotation_Save" class="btn btn-success" disabled>SAVE QUOTATION</button>
            </div>
        </div>
        <div  id="table_Quotationview" style="max-width:1000px"  hidden>
<!--            <div id="pdgdiv"><a href="#" class="Quotationpdf"><img src="images/pdfimage.jpg" alt="StarHub"></a><input type="hidden" id="temp_id"></div>-->
            <div class="col-lg-9 col-lg-offset-10">
                <button type="button" id="QT_Bacttolist" class="btn btn-info" style="background-color:#337ab7;;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>     BACK</button>
            </div>
            <div><h3 style="color:#337ab7;font-weight: bold">CONFIRMED ORDER</h3></div>
            <section10>
            </section10>
        </div>
        <div  id="table_reorderQuotationview" style="max-width:1000px"  hidden>
            <!--            <div id="pdgdiv"><a href="#" class="Quotationpdf"><img src="images/pdfimage.jpg" alt="StarHub"></a><input type="hidden" id="temp_id"></div>-->
            <div class="col-lg-9 col-lg-offset-10">
                <button type="button" id="QT_ROBacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>     BACK</button>
            </div>
            <div><h3 style="color:#337ab7;font-weight: bold">REVISE QUOTATION</h3></div>
            <section8>
            </section8>
        </div>
    </div>
</div>
</body>
<?php
//require_once("Footermenu.php");
//?>
</HTML>