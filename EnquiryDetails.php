<?php
require_once("usermenu.php");
?>
<script>
    $(document).ready(function(){

        $(".vali").change(function(){

            if(($('#productname').val()!='')&&($('#Description').val()!=''))
            {
                $('#product_addrow').removeAttr("disabled");
            }
            else
            {
                $('#product_addrow').attr('disabled','disabled');
            }
        })


        $('.preloader').show();
        var en_save;
        var en_date;
        $.ajax({
            type: "POST",
            url: "DB_Error_Msg.php",
            data:{"Option":'ERROR'},
            success: function(data){
                $('.preloader').hide();
                var value_array=JSON.parse(data);
                en_save=value_array[0];
                en_date=value_array[6];
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });

//date picker

        $('.entey_date').datepicker({
            dateFormat:"yy-mm-dd",
            changeYear: true,
            changeMonth: true
        });

        $('#tr_txt_date').change(function(){
            $(".preloader").show();
            var date=$('#tr_txt_date').val();
            $.ajax({
                type: 'POST',
                url: 'DB_EnquiryDetails.php',
                data:{flag:1,date:date},
                success: function(data){
                    $(".preloader").hide();
                    var values_array=data;
                    if(data!='')
                    {
                        $('#productname').val('');
                        $('#Description').val('');
                        $('#producttable').hide();
                        $('#Create_Enquiry').hide();
                        show_msgbox("USER ENQUIRY ENTRY",en_date,"error",false)
                    }
                    else{
                        $('#productname').val('');
                        $('#Description').val('');
                        $('#entry').show();
                        $('#product_addrow').show();
                        $('#producttable').show();
                        $('#Create_Enquiry').show();
                    }
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            })
//            $('#tr_txt_date').val('')
        });
        $('#product_updaterow').hide();
        //Enquiry Add row
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
                var appendrow='<tr class="active" style="background-color:#ffffff;" id='+row_id+' ><td style="max-width: 50px"><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-edit product_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block" class="glyphicon glyphicon-trash product_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control" id='+productid+' ></td><td style="max-width: 200px">'+product+'</td><td style="max-width: 350px">'+description+'</td></tr>';
                $('#producttable tr:last').after(appendrow);
                ProductformClear();
            }
            $('#tablecontent').show();
        });
        //end of Add Row
        //Form Clear
        function ProductformClear()
        {
         $('#productname').val('');
         $('#Description').val('');
        }
        //End Form Clear
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
            if(product=="")
            {
                show_msgbox("USER ENQUIRY",'ENTER PRODUCT NAME',"success",false)
            }
            if(description=="")
            {
                show_msgbox("USER ENQUIRY",'ENTER PRODUCT DESCRIPTION',"success",false)
            }
            if(product!="" && description!="")
            {
            var objUser = {"materialid":product_id,"materialitems":product,"materialreceipt":description};
            var objKeys = ["","materialitems", "materialreceipt"];
            $('#product_tr_' + objUser.materialid + ' td').each(function(i) {
                $(this).text(objUser[objKeys[i]]);
            });
            $('#product_addrow').show();
            $('#product_updaterow').hide();
            ProductformClear();
            }
        });
   //Reset Function
     function Reset()
     {
         ProductformClear();
         $('#tr_txt_date').val('');
         $('#producttable tr:not(:first)').remove();
         $('#tablecontent').hide();
     }

    //Final Enquiry Creation
     $(document).on("click",'#Create_Enquiry', function ()
     {
        var productrefTab = document.getElementById("producttable");
        var product_array=[];
        for ( var i = 1; row = productrefTab.rows[i]; i++ )
        {
            var productrowid=$('#productid'+i).val();
            row = productrefTab.rows[i];
            var productinnerarray=[];
            if(productrowid==""){productrowid=" "}
            productinnerarray.push(productrowid);
            for ( var j = 1; col = row.cells[j]; j++ ) {
                productinnerarray.push(col.firstChild.nodeValue);
            }
            product_array.push(productinnerarray) ;
        }
        if(product_array.length==0)
        {
            product_array='null';
        }
         var eq_date=$('#tr_txt_date').val();
                 $(".preloader").show();
         $.ajax({
             type: "POST",
             url: "DB_EnquiryDetails.php",
             data: {"Option":"Insert","EnquiryDetails":product_array,"Date":eq_date},
             success: function(msg){
                 $(".preloader").hide();
                if(msg==1)
                {
                    show_msgbox("USER ENQUIRY",en_save,"success",false)
                    Reset();
                }
             }
         });
     });
//End Of Final Enquiry Creation
    });
</script>
<body>
<div class="container">
<div class="panel panel-success" >
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#FF8C00;color:black;">
        <h3 class="panel-title" style="color:#ffffff;font-weight: bold">ENQUIRY DETAILS</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <fieldset>
                <div class="row form-group">

                </div>

                <div class="row form-group">
                    <div class="col-md-3">
                        <label>ENQUIRY DATE<span class="labelrequired">*</span></label>
                        <input type="text" style="max-width: 200px" class="form-control entey_date" name="tr_txt_date" id="tr_txt_date" PLACEHOLDER="Date" >
                    </div>
                    <div class="col-md-3">
                        <label>PRODUCT NAME<span class="labelrequired">*</span></label>
                        <input type="text" class="form-control" name="productname" id="productname" placeholder="Product Name">
                    </div>
                    <div class="col-md-3">
                        <label>DESCRIPTION<span class="labelrequired">*</span></label>
                        <textarea class="form-control" rows="2" id="Description" name="Description" placeholder="Description"></textarea>
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" class="form-control" name="productid" id="productid">
                    </div>
                </div>
                <div>
                    <button type="button" id="product_addrow" class="btn btn-success">ADD NEW</button>
                    <button type="button" id="product_updaterow" class="btn btn-success">UPDATE</button>
                </div>
            </fieldset>
            <div id="tablecontent" hidden>
          <div>
               <table class="table table-striped table-hover" style="max-width: 1000px;" border="1" id="producttable">
                   <thead>
                   <tr class="headercolor">
                       <th style="max-width: 100px">ACTION</th>
                       <th style="max-width: 200px">PRODUCT</th>
                       <th style="max-width: 400px">DESCRIPTION</th>
                   </tr>
                   </thead>
               </table>
            </div>
            <div class="col-lg-6 col-lg-offset-7">
                <button type="button" id="Create_Enquiry" class="btn btn-success">CREATE ENQUIRY</button>

            </div>
            </div>
        </form>
    </div>
</div>
    </div>
</body>
