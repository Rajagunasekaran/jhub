<?php
require_once("usermenu.php");
?>
<HTML>
<script>
    $(document).ready(function(){
     $('#conformorder').hide();
            $('.preloader').show();
            var en_save;
            var en_update;
            var qo_update;
            var lg_create;
            var lg_update;
            var order_conform;

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
                    order_conform=value_array[6];
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            });
            $(".preloader").show();
        $('#UQT_Bacttolist').hide();
        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":'EnquiryList'},
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
                $('#conformorder').hide();
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
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
                    $('#userquotationviewstatus').text(values_array[1])
                }
            }
            var Option="AdminQuotationView";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+rowid);
            xmlhttp.send();

        });
        $(document).on("click",'#product_addrow', function (){
            var product=$('#productname').val();
            var description=$('#Description').val();
            if((product!="Select") && (description!=''))
            {
                var tablerowCount=$('#producttable tr').length;
                var editid='product_editrow/'+tablerowCount;
                var deleterowid='product_deleterow/'+tablerowCount;
                var row_id="product_tr_"+tablerowCount;
                var productid="productid"+tablerowCount;
                var appendrow='<tr class="active" id='+row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit product_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash product_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control" id='+productid+' ></td><td style="max-width: 250px">'+product+'</td><td style="max-width: 250px">'+description+'</td></tr>';
                $('#producttable tr:last').after(appendrow);
                ProductformClear();
            }
        });
        function ProductformClear()
        {
            $('#productname').val('');
            $('#Description').val('');
        }
        //**********DELETE ROW*************//
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
                    product = $tds.eq(1).text(),
                    des = $tds.eq(2).text();
                $('#productname').val(product);
                $('#Description').val(des);
            });

        });
        //********UPDATE ROW****************//
        $(document).on("click",'#product_updaterow', function (){
            var product=$('#productname').val();
            var description=$('#Description').val();
            var product_id=$('#productid').val();
            var objUser = {"materialid":product_id,"materialitems":product,"materialreceipt":description};
            var objKeys = ["","materialitems", "materialreceipt"];
            $('#product_tr_' + objUser.materialid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#product_addrow').show();
            $('#product_updaterow').hide();
            ProductformClear();
        });

        $(document).on("click",'.userenquiryview', function (){
            var id=this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            $('#temp_id').val(rowid);
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var value_array=JSON.parse(xmlhttp.responseText);
                    $('#producttable tr:not(:first)').remove();
                   for(var i=0;i<value_array.length;i++)
                   {
                       var tablerowCount=$('#producttable tr').length;
                       var editid='product_editrow/'+tablerowCount;
                       var deleterowid='product_deleterow/'+tablerowCount;
                       var row_id="product_tr_"+tablerowCount;
                       var productid="productid"+tablerowCount;
                       var appendrow='<tr class="active" id='+row_id+'><td style="max-width: 150px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit product_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash product_removebutton"  id='+deleterowid+'></div><input type="hidden" value='+value_array[i][0]+' class="form-control" id='+productid+' ></td><td style="max-width: 250px">'+value_array[i][1]+'</td><td style="max-width: 250px">'+value_array[i][2]+'</td></tr>';
                       $('#producttable tr:last').after(appendrow);
                   }
                    $('#updateform').show();
                    $('#UQT_Bacttolist').hide();
                    $('#table_UserQuotationview').hide();
                    $('#pdgdiv').hide();
                    $('#tablecontainer').hide();
                    $('#product_updaterow').hide();
                }
            }
            var option="userenquirysearch";
            xmlhttp.open("GET","DB_EnquiryDetails.php?option="+option+"&Data="+rowid);
            xmlhttp.send();
        });
        $(document).on("click",'#UQT_Bacttolist', function (){
            $('#tablecontainer').show();
            $('#UQT_Bacttolist').hide();
            $('#table_UserQuotationview').hide();
            $('#pdgdiv').hide();
            $('#conformorder').hide();
        });
        $(document).on("click",'#update_Bacttolist', function (){
            $('#tablecontainer').show();
            $('#UQT_Bacttolist').hide();
            $('#table_UserQuotationview').hide();
            $('#pdgdiv').hide();
            $('#updateform').hide();
        });
        $(document).on('click','.UserQuotationpdf',function(){
            var QT_id=$('#temp_id').val();
            var url=document.location.href='COMMON_PDF.php?inputValOne='+QT_id;
        });
        $(document).on('click','#update_btn_enquiry',function(){
            var Pd_refTab = document.getElementById("producttable");
            var product_array=[];
            for (var r = 1, n = Pd_refTab.rows.length; r < n; r++) {
                var svrowid;
                var productinnerarray=[];
                var SV_inputval = Pd_refTab.getElementsByTagName('input');
                for (var j=0; j < r; j++){
                    if (SV_inputval[j].value != ""){
                        svrowid=SV_inputval[j].value;
                    }
                    if (SV_inputval[j].value == ""){
                        svrowid="";
                    }
                }
                if(svrowid==""){svrowid=" "}
                productinnerarray.push(svrowid);
                for (var c = 1, m = Pd_refTab.rows[r].cells.length; c < m; c++) {
                    productinnerarray.push(Pd_refTab.rows[r].cells[c].innerHTML);
                }
                product_array.push(productinnerarray) ;
            }
            if(product_array.length==0)
            {
                product_array='null';
            }
            $(".preloader").show();
            data={"Option":"Update","EnquiryDetails":product_array};
            $.ajax({
                type: "POST",
                url: "DB_EnquiryDetails.php",
                data:data ,
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
                    $('#tablecontainer').show();
                    $('#UQT_Bacttolist').hide();
                    $('#table_UserQuotationview').hide();
                    $('#pdgdiv').hide();
                    $('#updateform').hide();
                    $('#conformorder').hide();

                    show_msgbox("REPORT SUBMISSION ENTRY",en_update,"success",false)
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            });
        });
        //CONFORMED ORDER
        $(document).on("click",'#btn_conform_order', function (){

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

                    show_msgbox("J-PRINT Conform Order","QUOTATION CONFORMED SUCCESSFULLY","success",false)
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            });
        });
        //REVISED QUOTATION
        $(document).on("click",'#btn_revised_order', function (){
            var rowid=$('#temp_id').val();
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

                    show_msgbox("J-PRINT REVISED QUOTATION","QUOTATION RIVISE SUCCESSFULLY","success",false)
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            });
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
            <div  id="tablecontainer"  hidden>
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
                <button type="button" id="btn_conform_order" class="btn submit_btn" >CONFORM ORDER</button>
                <button type="button" id="btn_revised_order" class="btn submit_btn">REVISE QUOTATION</button>
             </div>
            </div>
            <div id="updateform" hidden>
                <div class="col-lg-9 col-lg-offset-10">
                    <button type="button" id="update_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>    BACK</button>
                </div>
                    <div class="row form-group">
                        <div class="col-md-4">
                            <label>PRODUCT NAME<span class="labelrequired">*</span></label>
                            <input type="text" class="form-control" name="productname" id="productname" placeholder="Product Name">
                        </div>
                        <div class="col-md-4">
                            <label>DESCRIPTION<span class="labelrequired">*</span></label>
                            <textarea class="form-control" rows="2" id="Description" name="Description" placeholder="Description"></textarea>
                        </div>
                        <div class="col-md-4">
                            <input type="hidden" class="form-control" name="productid" id="productid">
                        </div>
                    </div>
                    <div>
                        <button type="button" id="product_addrow" class="btn submit_btn">ADD NEW</button>
                        <button type="button" id="product_updaterow" class="btn submit_btn">UPDATE</button>
                    </div>
                <div>
                    <table class="table table-striped table-hover" style="max-width:1000px;border:1" id="producttable">
                    <thead>
                    <tr class="headercolor">
                        <th >ACTION</th>
                        <th>PRODUCT</th>
                        <th>DESCRIPTION</th>
                    </tr>
                    </thead>
                </table>
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