<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("usermenu.php");
?>
<HTML>
<script>
    $(document).ready(function(){
        $("#EnquiryDate").datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true,
            changeMonth: true
        });
        var CCRE_date1 = new Date();
        var CCRE_day=CCRE_date1.getDate();
        CCRE_date1.setDate( CCRE_day + 1 );
        var newDate = CCRE_date1.toDateString();
        newDate = new Date( Date.parse( newDate ));
        $('#EnquiryDate').datepicker("option","minDate",newDate);

        $('#enquirydetails').css("color", "#73c20e");
        $('textarea').autogrow({onInitialize: true});
        $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
        $("#Quantity").doValidation({rule:'numbersonly',prop:{realpart:6,leadzero:true}});
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
        var Option="EnquiryList";
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
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#tablecontainer').hide();
                    $('#UQT_Bacttolist').show();
                    $('#pdgdiv').show();
                    $('#conformorder').show();
                    $('#uploadfilediv').hide();
                    $('#userquotationviewstatus').text(values_array[1]);
                    if(values_array[1]=='DELIVERED' || values_array[1]=='CANCEL' )
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
        $(document).on("change",'.validation', function (){
            if(($('#productname').val()!='SELECT')&&($('#Description').val()!=''))
            {
                $('#product_addrow').removeAttr("disabled");
                $('#product_updaterow').removeAttr("disabled");
            }
            else
            {
                $('#product_addrow').attr('disabled','disabled');
                $('#product_updaterow').attr('disabled','disabled');
            }
        });
//        ///******************Product Add Row******************************//
//        $('#product_addrow').on( 'click', function ()
//        {
//            var jobtitle=$('#Job_tilte').val();
//            var item=$('#Item').val();
//            if(item=='SELECT'){item="";}
//            var Size=$('#Size').val();
//            if(Size=='SELECT'){Size="";}
//            var Papertype=$('#Papertype').val();
//            if(Papertype=='SELECT'){Papertype="";}
//            var Paperweight=$('#Paperweight').val();
//            if(Paperweight=='SELECT'){Paperweight="";}
//            var Printingmethod=$('#Printingmethod').val();
//            if(Printingmethod=='SELECT'){Printingmethod="";}
//            var Printingprocess=$('#Printingprocess').val();
//            if(Printingprocess=='SELECT'){Printingprocess="";}
//            var Treatmentprocess=$('#Treatmentprocess').val();
//            if(Treatmentprocess=='SELECT'){Treatmentprocess="";}
//            var Finishingprocess=$('#Finishingprocess').val();
//            if(Finishingprocess=='SELECT'){Finishingprocess="";}
//            var Bindingprocess=$('#Bindingprocess').val();
//            if(Bindingprocess=='SELECT'){Bindingprocess="";}
//            var Quantity=$('#Quantity').val();
//            var Enquirydate=$('#EnquiryDate').val();
//            var DeliveryLocation=$('#DeliveryLocation').val();
//            var Remarks=$('#Remarks').val();
//            if(jobtitle!="" || item!="" || Size!="" || Papertype!="" || Paperweight!="" || Printingmethod!="" ||
//                Printingprocess!="" ||  Treatmentprocess!="" || Finishingprocess!="" || Bindingprocess!="" || Quantity!="" || Enquirydate!="" || DeliveryLocation!="" || Remarks!="")
//            {
//                var tablerowCount=$('#producttable tr').length;
//                var editid='product_editrow/'+tablerowCount;
//                var deleterowid='product_deleterow/'+tablerowCount;
//                var row_id="product_tr_"+tablerowCount;
//                var productid="productid"+tablerowCount;
//                var appendrow='<tr id='+row_id+'>' +
//                    '<td><div class="col-lg-1"><span style="display: block;color:green" class="glyphicon glyphicon-edit product_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block;color:red" class="glyphicon glyphicon-trash product_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control" id='+productid+' ></td>' +
//                    '<td>'+jobtitle+'</td>' +
//                    '<td>'+item+'</td>' +
//                    '<td>'+Size+'</td>' +
//                    '<td>'+Papertype+'</td>' +
//                    '<td>'+Paperweight+'</td>' +
//                    '<td>'+Printingmethod+'</td>' +
//                    '<td>'+Printingprocess+'</td>' +
//                    '<td>'+Treatmentprocess+'</td>' +
//                    '<td>'+Finishingprocess+'</td>' +
//                    '<td>'+Bindingprocess+'</td>' +
//                    '<td>'+Quantity+'</td>' +
//                    '<td>'+Enquirydate+'</td>' +
//                    '<td>'+DeliveryLocation+'</td>' +
//                    '<td>'+Remarks+'</td>' +
//                    '</tr>';
//                $('#producttable tr:last').after(appendrow);
//                $('#tablecontent').show();
//                ProductformClear()
//            }
//        });
//        function ProductformClear()
//        {
//            $('#Job_tilte').val('');
//            $('#Item').val('SELECT');
//            $('#Size').val('SELECT');
//            $('#Papertype').val('SELECT');
//            $('#Paperweight').val('SELECT');
//            $('#Printingmethod').val('SELECT');
//            $('#Printingprocess').val('SELECT');
//            $('#Treatmentprocess').val('SELECT');
//            $('#Finishingprocess').val('SELECT');
//            $('#Bindingprocess').val('SELECT');
//            $('#Quantity').val('');
//            $('#EnquiryDate').val('');
//            $('#DeliveryLocation').val('');
//            $('#Remarks').val('');
//            $('#JP_EnquiryDetails')[0].reset();
//            $('#Remarks').val('').height('40');
//
//        }
//        //**********DELETE ROW*************//
        $(document).on("click",'.product_removebutton', function (){
            $(this).closest('tr').remove();
            return false;
        });
        //Edit Row
        $(document).on("click",'.product_editbutton', function (){
            $('#product_updaterow').show();
            $('#product_addrow').hide();
            var id = this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            $('#productid').val(rowid);
            $('#producttable tr:eq('+rowid+')').each(function () {
                var $tds = $(this).find('td'),
                    jobtitle = $tds.eq(1).text(),
                    item = $tds.eq(2).text(),
                    size = $tds.eq(3).text(),
                    papertype = $tds.eq(4).text(),
                    paperweight = $tds.eq(5).text(),
                    printingmethod = $tds.eq(6).text(),
                    printingrocess = $tds.eq(7).text(),
                    treatmentprocess = $tds.eq(8).text(),
                    finishingprocess = $tds.eq(9).text(),
                    bindingprocess = $tds.eq(10).text(),
                    quantity = $tds.eq(11).text(),
                    date = $tds.eq(12).text(),
                    location = $tds.eq(13).text(),
                    remarks = $tds.eq(14).text();
                $('#Job_tilte').val(jobtitle);
                if(item!=""){$('#Item').val(item);}
                if(size!=""){$('#Size').val(size);}
                if(papertype!=""){$('#Papertype').val(papertype);}
                if(paperweight!=""){$('#Paperweight').val(paperweight);}
                if(printingmethod!=""){$('#Printingmethod').val(printingmethod);}
                if(printingrocess!=""){$('#Printingprocess').val(printingrocess);}
                if(treatmentprocess!=""){$('#Treatmentprocess').val(treatmentprocess);}
                if(finishingprocess!=""){$('#Finishingprocess').val(finishingprocess);}
                if(bindingprocess!=""){$('#Bindingprocess').val(bindingprocess);}
                $('#Quantity').val(quantity);
                $('#DeliveryLocation').val(location);
                $('#Remarks').val(remarks);
                if(date!='' && date!='0000-00-00' && date!=null)
                {
                    var dbdate=date.split('-');
                    var dp_mindate=new Date(dbdate[0],dbdate[1]-1,dbdate[2]);
                    $("#EnquiryDate").datepicker("option","minDate",dp_mindate);
                    $('#EnquiryDate').val(date);
                }
                else
                {
                    $("#EnquiryDate").datepicker("option","minDate",new Date());
                }
            });

        });
//        //********UPDATE ROW****************//
//        $(document).on("click",'#product_updaterow', function (){
//            var product_id=$('#productid').val();
//            var jobtitle=$('#Job_tilte').val();
//            var item=$('#Item').val();
//            if(item=='SELECT'){item="";}
//            var Size=$('#Size').val();
//            if(Size=='SELECT'){Size="";}
//            var Papertype=$('#Papertype').val();
//            if(Papertype=='SELECT'){Papertype="";}
//            var Paperweight=$('#Paperweight').val();
//            if(Paperweight=='SELECT'){Paperweight="";}
//            var Printingmethod=$('#Printingmethod').val();
//            if(Printingmethod=='SELECT'){Printingmethod="";}
//            var Printingprocess=$('#Printingprocess').val();
//            if(Printingprocess=='SELECT'){Printingprocess="";}
//            var Treatmentprocess=$('#Treatmentprocess').val();
//            if(Treatmentprocess=='SELECT'){Treatmentprocess="";}
//            var Finishingprocess=$('#Finishingprocess').val();
//            if(Finishingprocess=='SELECT'){Finishingprocess="";}
//            var Bindingprocess=$('#Bindingprocess').val();
//            if(Bindingprocess=='SELECT'){Bindingprocess="";}
//            var Quantity=$('#Quantity').val();
//            var Enquirydate=$('#EnquiryDate').val();
//            var DeliveryLocation=$('#DeliveryLocation').val();
//            var Remarks=$('#Remarks').val();
//            if(jobtitle!="" || item!="" || Size!="" || Papertype!="" || Paperweight!="" || Printingmethod!="" ||
//                Printingprocess!="" ||  Treatmentprocess!="" || Finishingprocess!="" || Bindingprocess!="" || Quantity!="" || Enquirydate!="" || DeliveryLocation!="" || Remarks!="")
//            {
//                var objUser = {"materialid":product_id,"jobtitle":jobtitle,"item":item,"size":Size,"type":Papertype,"weight":Paperweight,
//                    "Printingmethod":Printingmethod,"Printingprocess":Printingprocess,"Treatmentprocess":Treatmentprocess,"Finishingprocess":Finishingprocess,
//                    "Bindingprocess":Bindingprocess,"Quantity":Quantity,"Enquirydate":Enquirydate,"DeliveryLocation":DeliveryLocation,"Remarks":Remarks};
//                var objKeys = ["","jobtitle","item","size","type","weight","Printingmethod","Printingprocess","Treatmentprocess","Finishingprocess",
//                    "Bindingprocess","Quantity","Enquirydate","DeliveryLocation","Remarks"];
//                $('#product_tr_' + objUser.materialid + ' td').each(function(i) {
//                    $(this).text(objUser[objKeys[i]]);
//                });
//                $('#product_addrow').show();
//                $('#product_updaterow').hide();
//                var CCRE_date1 = new Date();
//                var CCRE_day=CCRE_date1.getDate();
//                CCRE_date1.setDate( CCRE_day + 1 );
//                var newDate = CCRE_date1.toDateString();
//                newDate = new Date( Date.parse( newDate ));
//                $('#EnquiryDate').datepicker("option","minDate",newDate);
//                ProductformClear()
//            }
//        });      var Removefilearray=[];
        $(document).on("click",'.userenquiryview', function (){
            var id=this.id;
            Removefilearray=[];
            var splitid=id.split('/');
            var rowid=splitid[1];
            $(".preloader").show();
            $('#temp_id').val(rowid);
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    var value_array=values_array[0];
                   var tabledata='<table id="producttable" border=1 cellspacing="0" data-class="table" class="srcresult table">' +
                        '<thead>' +
                        '<tr class="headercolor">' +
//                        '<th style="vertical-align: top">ACTION</th>'+
                        '<th>JOB TITLE</th>'+
                        '<th style="vertical-align: top">ITEM</th>'+
                        '<th style="vertical-align: top">SIZE</th>'+
                        '<th>PAPER TYPE</th>'+
                        '<th>PAPER WEIGHT</th>'+
                        '<th>PRINTING METHOD</th>'+
                        '<th>PRINTING PROCESS</th>'+
                        '<th>TREATMENT PROCESS</th>'+
                        '<th>FINISHING PROCESS</th>'+
                        '<th>BINDING PROCESS</th>'+
                        '<th style="vertical-align: top">QUANTITY</th>'+
                        '<th>DATE REQUIRED</th>'+
                        '<th>DELIVERY LOCATION</th>'+
                        '<th style="vertical-align: top">REMARKS</th>'+
                       '</tr></thead><tbody>';
                   for(var i=0;i<value_array.length;i++)
                   {
                       var tablerowCount=i+1;//$('#producttable tr').length;
                       var editid='product_editrow/'+tablerowCount;
                       var deleterowid='product_deleterow/'+tablerowCount;
                       var row_id="product_tr_"+tablerowCount;
                       var productid="productid"+tablerowCount;
                       if(value_array[i][1]==null){value_array[i][1]="";}
                       if(value_array[i][2]==null){value_array[i][2]="";}
                       if(value_array[i][3]==null){value_array[i][3]="";}
                       if(value_array[i][4]==null){value_array[i][4]="";}
                       if(value_array[i][5]==null){value_array[i][5]="";}
                       if(value_array[i][6]==null){value_array[i][6]="";}
                       if(value_array[i][7]==null){value_array[i][7]="";}
                       if(value_array[i][8]==null){value_array[i][8]="";}
                       if(value_array[i][9]==null){value_array[i][9]="";}
                       if(value_array[i][10]==null){value_array[i][10]="";}
                       if(value_array[i][11]==null){value_array[i][11]="";}
                       if(value_array[i][12]=='0000-00-00'){value_array[i][12]="";}
                       if(value_array[i][13]==null){value_array[i][13]="";}
                       if(value_array[i][14]==null){value_array[i][14]="";}
                       tabledata+='<tr id='+row_id+'>' +
//                           '<td style="width: 100px">' +
//                           '<div class="col-lg-1"><span style="display: block;color:green" class="glyphicon glyphicon-edit product_editbutton" id='+editid+'></div>' +
//                           '<div class="col-lg-1"><span style="display: block;color:red" class="glyphicon glyphicon-trash product_removebutton"  id='+deleterowid+'></div>' +
//                           '<input type="hidden" value='+value_array[i][0]+' class="form-control" id='+productid+' ></td>' +
                           '<td>'+value_array[i][1]+'</td>' +
                           '<td>'+value_array[i][2]+'</td>' +
                           '<td>'+value_array[i][3]+'</td>' +
                           '<td>'+value_array[i][4]+'</td>' +
                           '<td>'+value_array[i][5]+'</td>' +
                           '<td>'+value_array[i][6]+'</td>' +
                           '<td>'+value_array[i][7]+'</td>' +
                           '<td>'+value_array[i][8]+'</td>' +
                           '<td>'+value_array[i][9]+'</td>' +
                           '<td>'+value_array[i][10]+'</td>' +
                           '<td>'+value_array[i][11]+'</td>' +
                           '<td>'+value_array[i][12]+'</td>' +
                           '<td>'+value_array[i][13]+'</td>' +
                           '<td>'+value_array[i][14]+'</td>' +
                           '</tr>';
                   }
                    tabledata+='</tbody></table>';
                    $('section2').html(tabledata);
                    $('#producttable').DataTable({
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#updatetablecontent').show();
                    $('#updateform').show();
                    $('#UQT_Bacttolist').hide();
                    $('#table_UserQuotationview').hide();
                    $('#pdgdiv').hide();
                    $('#tablecontainer').hide();
                     $('#uploadfilediv').show();
                    $('#product_updaterow').hide();
                    $(".preloader").hide();
                }
            }
            var option="userenquirysearch";
            xmlhttp.open("GET","DB_EnquiryDetails.php?option="+option+"&Data="+rowid);
            xmlhttp.send();
        });
        $(document).on("click",'#update_Bacttolist', function (){
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
            var Option="EnquiryList";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option);
            xmlhttp.send();
            $('#tablecontainer').show();
            $('#UQT_Bacttolist').hide();
            $('#table_UserQuotationview').hide();
            $('#updatetablecontent').hide();
            $('#pdgdiv').hide();
            $('#updateform').hide();
            $('#tablecontent').hide();
            $('#test').remove();
            $('#uploadfilediv').hide();
            ProductformClear()
        });

    });
</script>
<body class="bg-theme">
<div class="container">
<div class="panel panel-info" >
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#73c20e;color:black;">
        <h3 class="panel-title" style="color:#ffffff;font-weight: bold">ENQUIRY DETAILS UPDATION</h3>
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
            <div  id="table_UserQuotationview" style="max-width: 1000px"   hidden>
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
                <div><h3 style="color:#337ab7;font-weight: bold" >STATUS  : NEW</h3></div>
                <div class="col-lg-9 col-lg-offset-10">
                    <button type="button" id="update_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>    BACK</button>
                </div>
                </div>
                    <div style="padding-left: 15px" id="updatetablecontent" class="table-responsive" hidden>

                        <section2>

                        </section2>
                    </div>
            <br>
               </div>

        </form>
    </div>
</div>
</div>
</body>
</HTML>