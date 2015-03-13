<?php
require_once("adminmenu.php");
?>
<HTML>
<script>
    $(document).ready(function(){
        $('.preloader').show();
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
                $(".preloader").hide();
                var values_array=data;
                $('#tablecontainer').show();
                $('section').html(values_array);
                $('#user_table').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#EQ_Bacttolist').hide();
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
        $(document).on("click",'.Quotationcreation', function (){
            $(".preloader").show();
            var id=this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#Enquirytable').show();
                    $('section1').html(values_array[0]);
                    $('#quotation_view').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#Quotation_Save').show();
                    $('#tablecontainer').hide();
                    $('#EQ_Bacttolist').show();
                    $(".preloader").hide();
                    $('#statuslabel').text(values_array[2]);
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
            $('#quotationtotal').text(total).html();
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
                    var values_array=data;
                    $('#Enquirytable').show();
                    $('section1').html(values_array);
                    $('#quotation_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#tablecontainer').show();
                    $('#EQ_Bacttolist').hide();
                    $('#Enquirytable').hide();
                    $('#Quotation_Save').hide();
                    show_msgbox("QUOTATION CREATION",qo_update,"success",false)
                  },
                error: function(data){
                    alert('error in getting'+data);
                }
            });
        });
        $(document).on("click",'#EQ_Bacttolist', function (){
            $('#tablecontainer').show();
            $('#EQ_Bacttolist').hide();
            $('#Enquirytable').hide();
            $('#Quotation_Save').hide();
        });
    });
</script>
<body>
<div class="container">
<div class="panel panel-info" >
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#FF8C00;color:black;">
        <h3 class="panel-title" style="color:#ffffff;font-weight: bold">ENQUIRY DETAILS</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div  id="tablecontainer" style="max-width:1500px" hidden>
                <section >
                </section>
            </div>
            <div class="col-lg-9 col-lg-offset-10">
                <button type="button" id="EQ_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>   BACK</button>
            </div>
            <div id="Enquirytable" style="max-width: 1000px"hidden>
                <div><input type="hidden" id="tempstatus" name="tempstatus"></div>
             <div><h3 style="color:#337ab7;font-weight: bold" id="statuslabel"></h3></div>
                <section1>
                </section1>
            </div>
            <div class="col-lg-3 col-lg-offset-4">
                <button type="button" id="Quotation_Save" class="btn submit_btn" >SAVE QUOTATION</button>
            </div>
        </form>
    </div>
</div>
</div>
</body>
</HTML>