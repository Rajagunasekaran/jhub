<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("usermenu.php");
?>
<script>
    $(document).ready(function(){
        $("#Quantity").doValidation({rule:'numbersonly',prop:{realpart:6,leadzero:true}});
        $('#enquirtload').css("color", "#73c20e");
        //FILE UPLOAD
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
        //END OF FILE UPLOAD
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
        var product_array=[];
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
        $('.preloader').show();
        $('textarea').autogrow({onInitialize: true});
        $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
        //error messages//
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
                alert(en_data)
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
        //end of error messages////
        var values_array=[];
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                values_array=JSON.parse(xmlhttp.responseText);
                var appendcontent='<div><label style="color:#4387fd;font-size: 17px">COMPANY  NAME&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</label><label>'+values_array[0][0]+'</label></div><br>';
                appendcontent+='<div><label style="color:#4387fd;font-size: 17px">CONTACT PERSON&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</label><label>'+values_array[0][1]+'</label></div><br>';
                var currentdate=new Date();
                var month=(currentdate.getMonth()+1).toString();
                if(month.length==1)
                {
                    month="0"+month;
                }
                var curr_date=currentdate.getDate()+'-'+month+'-'+currentdate.getFullYear();
                appendcontent+='<div><label style="color:#4387fd;font-size: 17px">ENQUIRY DATE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</label><label>'+curr_date+'</label></div>';
                $('#enquiry_heading').append(appendcontent);
                for(var i=0;i<values_array[1].length;i++)
                {
                    var data=values_array[1][i];
                    if(data[0]==1)
                    {
                    $('#Item').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                    if(data[0]==2)
                    {
                        $('#Size').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                    if(data[0]==3)
                    {
                        $('#Papertype').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                    if(data[0]==4)
                    {
                        $('#Paperweight').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                    if(data[0]==5)
                    {
                        $('#Printingmethod').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                    if(data[0]==6)
                    {
                        $('#Printingprocess').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                    if(data[0]==7)
                    {
                        $('#Treatmentprocess').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                    if(data[0]==8)
                    {
                        $('#Finishingprocess').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                    if(data[0]==9)
                    {
                        $('#Bindingprocess').append($('<option>').text(data[1]).attr('value', data[1]));
                    }
                }
                $('.preloader').hide();
            }
        }
        var Optionvalue="UserDetails";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Optionvalue,true);
        xmlhttp.send();
         $('#product_updaterow').hide();
        //Form Clear
        function ProductformClear()
        {
            $('#JP_EnquiryDetails')[0].reset();
         $('#Remarks').val('').height('40');
        }
//        //End Form Clear
//        //**********DELETE ROW*************//
//        $(document).on("click",'.product_removebutton', function (){
//            $(this).closest('tr').remove();
//            return false;
//        });
//        //Edit Row
//
//        $(document).on("click",'.product_editbutton', function (){
//            $('#product_updaterow').show();
//            $('#product_addrow').hide();
//            var id = this.id;
//            var splitid=id.split('/');
//            var rowid=splitid[1];
//            $('#productid').val(rowid);
//            $('#Enquiry_table tr:eq('+rowid+')').each(function () {
//                var $tds = $(this).find('td'),
//                    jobtitle = $tds.eq(1).text(),
//                    item = $tds.eq(2).text(),
//                    size = $tds.eq(3).text(),
//                    papertype = $tds.eq(4).text(),
//                    paperweight = $tds.eq(5).text(),
//                    printingmethod = $tds.eq(6).text(),
//                    printingrocess = $tds.eq(7).text(),
//                    treatmentprocess = $tds.eq(8).text(),
//                    finishingprocess = $tds.eq(9).text(),
//                    bindingprocess = $tds.eq(10).text(),
//                    quantity = $tds.eq(11).text(),
//                    date = $tds.eq(12).text(),
//                    location = $tds.eq(13).text(),
//                    remarks = $tds.eq(14).text();
//                $('#Job_tilte').val(jobtitle);
//                if(item!=""){$('#Item').val(item);}
//                if(size!=""){$('#Size').val(size);}
//                if(papertype!=""){$('#Papertype').val(papertype);}
//                if(paperweight!=""){$('#Paperweight').val(paperweight);}
//                if(printingmethod!=""){$('#Printingmethod').val(printingmethod);}
//                if(printingrocess!=""){$('#Printingprocess').val(printingrocess);}
//                if(treatmentprocess!=""){$('#Treatmentprocess').val(treatmentprocess);}
//                if(finishingprocess!=""){$('#Finishingprocess').val(finishingprocess);}
//                if(bindingprocess!=""){$('#Bindingprocess').val(bindingprocess);}
//                $('#Quantity').val(quantity);
//                $('#EnquiryDate').val(date);
//                $('#DeliveryLocation').val(location);
//                $('#Remarks').val(remarks);
//            });
//
//        });
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
//                "Bindingprocess":Bindingprocess,"Quantity":Quantity,"Enquirydate":Enquirydate,"DeliveryLocation":DeliveryLocation,"Remarks":Remarks};
//                var objKeys = ["","jobtitle","item","size","type","weight","Printingmethod","Printingprocess","Treatmentprocess","Finishingprocess",
//                "Bindingprocess","Quantity","Enquirydate","DeliveryLocation","Remarks"];
//                $('#product_tr_' + objUser.materialid + ' td').each(function(i) {
//                    $(this).text(objUser[objKeys[i]]);
//                });
//                $('#product_addrow').show();
//                $('#product_updaterow').hide();
//                ProductformClear();
//            }
//        });
//   //Reset Function
//     function Reset()
//     {
//         ProductformClear();
//         $('#tablecontent').hide();
//         product_array=[];
//     }

    //Final Enquiry Creation
     $(document).on("click",'#Create_Enquiry', function ()
     {
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

        var productrefTab = document.getElementById("JP_EnquiryDetails");
//        var product_array=[];
//
//         for (var r = 1, n = productrefTab.rows.length; r < n; r++) {
//             var productrowid=$('#productid'+i).val();
//             row = productrefTab.rows[i];
//             var productinnerarray=[];
//             if(productrowid==""){productrowid=" "}
//             productinnerarray.push(productrowid);
//             for (var c = 1, m = productrefTab.rows[r].cells.length; c < m; c++) {
//                 productinnerarray.push(productrefTab.rows[r].cells[c].innerHTML);
//             }
//             product_array.push(productinnerarray) ;
//         }
//        if(product_array.length==0)
//        {
//            product_array='null';
//        }
         var data=$('#JP_EnquiryDetails').serialize();
         var option="Insert";
         $.ajax({
             type: "POST",
             url: "DB_EnquiryDetails.php",
             data:  data+'&Option='+ option+'&files='+filesarray,
             success: function(msg){
                 $(".preloader").hide();
                if(msg==1)
                {
                    show_msgbox("JHUB",en_save,"success",false);
                    $('#JP_EnquiryDetails')[0].reset();
                    $('#Create_Enquiry').attr('disabled','disabled');
//                    $("#Enquiry_table").find("tr:gt(0)").remove();
                    var uploader = $('#uploader').plupload('getUploader');
                    uploader.splice();
                    $('#Create_Enquiry').attr('disabled','disabled');
                }
             }
         });
     });
        $('.Btn_validation').click(function(){
            $('#Create_Enquiry').removeAttr("disabled");
        });
        $('.Btn_add_validation').click(function(){

            $('#Create_Enquiry').attr('disabled','disabled');
//            BtnValidation();
        });
        function BtnValidation()
        {
            var jobtitle=$('#Job_tilte').val();
            var item=$('#Item').val();
            if(item=='SELECT'){item="";}
            var Size=$('#Size').val();
            if(Size=='SELECT'){Size="";}
            var Papertype=$('#Papertype').val();
            if(Papertype=='SELECT'){Papertype="";}
            var Paperweight=$('#Paperweight').val();
            if(Paperweight=='SELECT'){Paperweight="";}
            var Printingmethod=$('#Printingmethod').val();
            if(Printingmethod=='SELECT'){Printingmethod="";}
            var Printingprocess=$('#Printingprocess').val();
            if(Printingprocess=='SELECT'){Printingprocess="";}
            var Treatmentprocess=$('#Treatmentprocess').val();
            if(Treatmentprocess=='SELECT'){Treatmentprocess="";}
            var Finishingprocess=$('#Finishingprocess').val();
            if(Finishingprocess=='SELECT'){Finishingprocess="";}
            var Bindingprocess=$('#Bindingprocess').val();
            if(Bindingprocess=='SELECT'){Bindingprocess="";}
            var Quantity=$('#Quantity').val();
            var Enquirydate=$('#EnquiryDate').val();
            var DeliveryLocation=$('#DeliveryLocation').val();
            var Remarks=$('#Remarks').val();
            if(jobtitle!="" || item!="" || Size!="" || Papertype!="" || Paperweight!="" || Printingmethod!="" ||
                Printingprocess!="" ||  Treatmentprocess!="" || Finishingprocess!="" || Bindingprocess!="" || Quantity!="" || Enquirydate!="" || DeliveryLocation!="" || Remarks!="")
            {
                $('#Create_Enquiry').removeAttr("disabled");
            }
            else
            {
                $('#Create_Enquiry').attr('disabled','disabled');
            }
        }
        $('.Validation').on( 'change', function (){
            BtnValidation();
         });

//        ///******************Product Add Row******************************//
//        $('#product_addrow').on( 'click', function ()
//        {
//        var jobtitle=$('#Job_tilte').val();
//        var item=$('#Item').val();
//            if(item=='SELECT'){item="";}
//        var Size=$('#Size').val();
//            if(Size=='SELECT'){Size="";}
//        var Papertype=$('#Papertype').val();
//            if(Papertype=='SELECT'){Papertype="";}
//        var Paperweight=$('#Paperweight').val();
//            if(Paperweight=='SELECT'){Paperweight="";}
//        var Printingmethod=$('#Printingmethod').val();
//            if(Printingmethod=='SELECT'){Printingmethod="";}
//        var Printingprocess=$('#Printingprocess').val();
//            if(Printingprocess=='SELECT'){Printingprocess="";}
//        var Treatmentprocess=$('#Treatmentprocess').val();
//            if(Treatmentprocess=='SELECT'){Treatmentprocess="";}
//        var Finishingprocess=$('#Finishingprocess').val();
//            if(Finishingprocess=='SELECT'){Finishingprocess="";}
//        var Bindingprocess=$('#Bindingprocess').val();
//            if(Bindingprocess=='SELECT'){Bindingprocess="";}
//        var Quantity=$('#Quantity').val();
//        var Enquirydate=$('#EnquiryDate').val();
//        var DeliveryLocation=$('#DeliveryLocation').val();
//        var Remarks=$('#Remarks').val();
//        if(jobtitle!="" || item!="" || Size!="" || Papertype!="" || Paperweight!="" || Printingmethod!="" ||
//            Printingprocess!="" ||  Treatmentprocess!="" || Finishingprocess!="" || Bindingprocess!="" || Quantity!="" || Enquirydate!="" || DeliveryLocation!="" || Remarks!="")
//        {
//            var tablerowCount=$('#Enquiry_table tr').length;
//            var editid='product_editrow/'+tablerowCount;
//            var deleterowid='product_deleterow/'+tablerowCount;
//            var row_id="product_tr_"+tablerowCount;
//            var productid="productid"+tablerowCount;
//            var appendrow='<tr id='+row_id+'>' +
//                '<td><div class="col-lg-1"><span style="display: block;color:green" class="glyphicon glyphicon-edit product_editbutton" id='+editid+'></div><div class="col-lg-1"><span style="display: block;color:red" class="glyphicon glyphicon-trash product_removebutton"  id='+deleterowid+'></div><input type="hidden" class="form-control" id='+productid+' ></td>' +
//                '<td>'+jobtitle+'</td>' +
//                '<td>'+item+'</td>' +
//                '<td>'+Size+'</td>' +
//                '<td>'+Papertype+'</td>' +
//                '<td>'+Paperweight+'</td>' +
//                '<td>'+Printingmethod+'</td>' +
//                '<td>'+Printingprocess+'</td>' +
//                '<td>'+Treatmentprocess+'</td>' +
//                '<td>'+Finishingprocess+'</td>' +
//                '<td>'+Bindingprocess+'</td>' +
//                '<td>'+Quantity+'</td>' +
//                '<td>'+Enquirydate+'</td>' +
//                '<td>'+DeliveryLocation+'</td>' +
//                '<td>'+Remarks+'</td>' +
//                '</tr>';
//            $('#Enquiry_table tr:last').after(appendrow);
//            $('#tablecontent').show();
//            ProductformClear()
//        }
//        });
    });

</script>

<body class="bg-theme">
<div class="container">
    <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <div class="panel-heading" style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold">ENQUIRY UPLOAD</h3>
        </div>
        <div class="panel-body">
<!--            <div id="enquiry_heading">-->
<!--            </div>-->
            <form class="form-horizontal" id="JP_EnquiryDetails">
                <fieldset>
                    <div class="row form-group">
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>JOB TITLE</label>
                            <input type="text" class="form-control Validation" id="Job_tilte" name="Job_tilte" placeholder="Job Title"/>
                        </div>
                        <div class="col-md-3">
                            <label>ITEM</label>
                            <SELECT class="form-control Validation" id="Item" name="Item">
                                <OPTION>SELECT</OPTION>
                             </SELECT>
                        </div>
                        <div class="col-md-3">
                            <label>SIZE</label>
                            <SELECT class="form-control Validation" id="Size" name="Size">
                                <OPTION>SELECT</OPTION>
                            </SELECT>
                        </div>
                        <div class="col-md-3">
                            <label>PAPER TYPE</label>
                            <SELECT class="form-control Validation" id="Papertype" name="Papertype">
                                <OPTION>SELECT</OPTION>
                            </SELECT>
                        </div>
                     </div>
                     <div class="row form-group">
                        <div class="col-md-3">
                            <label>PAPER WEIGHT</label>
                            <SELECT class="form-control Validation" id="Paperweight" name="Paperweight">
                                <OPTION>SELECT</OPTION>
                            </SELECT>
                        </div>
                        <div class="col-md-3">
                            <label>PRINTING METHOD</label>
                            <SELECT class="form-control Validation" id="Printingmethod" name="Printingmethod">
                                <OPTION>SELECT</OPTION>
                            </SELECT>
                        </div>
                        <div class="col-md-3">
                            <label>PRINTING PROCESS</label>
                            <SELECT class="form-control Validation"  id="Printingprocess" name="Printingprocess">
                                <OPTION>SELECT</OPTION>
                            </SELECT>
                        </div>
                        <div class="col-md-3">
                            <label>TREATMENT PROCESS</label>
                            <SELECT class="form-control Validation" id="Treatmentprocess" name="Treatmentprocess">
                                <OPTION>SELECT</OPTION>
                            </SELECT>
                        </div>
                     </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>FINISHING PROCESS</label>
                            <SELECT class="form-control Validation" id="Finishingprocess" name="Finishingprocess">
                                <OPTION>SELECT</OPTION>
                            </SELECT>
                        </div>
                        <div class="col-md-3">
                            <label>BINDING PROCESS</label>
                            <SELECT class="form-control Validation" id="Bindingprocess" name="Bindingprocess">
                                <OPTION>SELECT</OPTION>
                            </SELECT>
                        </div>
                        <div class="col-md-3">
                            <label>QUANTITY</label>
                            <input type="text" class="form-control Validation" id="Quantity" name="Quantity" placeholder="Quantity"/>
                        </div>
                        <div class="col-md-3">
                            <label>DATE REQUIRED</label>
                            <input type="text" class="form-control Validation" id="EnquiryDate" name="EnquiryDate" placeholder="Date"/>
                        </div>
                     </div>
                     <div class="row form-group">

                         <div class="col-md-3">
                             <label>DELIVERY LOCATION</label>
                             <input type="text" class="form-control Validation" id="DeliveryLocation" name="DeliveryLocation" placeholder="DeliveryLocation"/>
                         </div>
                         <div class="col-md-3">
                             <label>REMARKS/SPECIAL REQUEST</label>
                             <textarea class="form-control Validation" rows="2" id="Remarks" name="Remarks"></textarea>
                         </div>
                         <div class="col-md-4">
                             <input type="hidden" class="form-control Validation" name="productid" id="productid">
                         </div>
                    </div>
<!--                    <div>-->
<!--                        <button type="button" id="product_addrow" class="btn submit_btn">ADD NEW</button>-->
<!--                        <button type="button" id="product_updaterow" class="btn submit_btn">UPDATE</button>-->
<!--                    </div>-->
                </fieldset>
            </form>
            <div id="tablecontent" >
<!--                   <div class="table-responsive">-->
<!--                         <section>-->
<!--                              <table id="Enquiry_table" border=1 cellspacing='0' data-class='table'class=' srcresult table'>-->
<!--                                   <thead>-->
<!--                                       <tr class="headercolor">-->
<!--                                           <th style="vertical-align: top">ACTION</th>-->
<!--                                           <th style="vertical-align: top">JOB TITLE</th>-->
<!--                                           <th style="vertical-align: top">ITEM</th>-->
<!--                                           <th style="vertical-align: top">SIZE</th>-->
<!--                                           <th style="vertical-align: top">PAPER TYPE</th>-->
<!--                                           <th style="vertical-align: top">PAPER WEIGHT</th>-->
<!--                                           <th style="vertical-align: top">PRINTING METHOD</th>-->
<!--                                           <th style="vertical-align: top">PRINTING PROCESS</th>-->
<!--                                           <th style="vertical-align: top">TREATMENT PROCESS</th>-->
<!--                                           <th style="vertical-align: top">FINISHING PROCESS</th>-->
<!--                                           <th style="vertical-align: top">BINDING PROCESS</th>-->
<!--                                           <th style="vertical-align: top">QUANTITY</th>-->
<!--                                           <th style="vertical-align: top">DATE REQUIRED</th>-->
<!--                                           <th style="vertical-align: top">DELIVERY LOCATION</th>-->
<!--                                           <th style="vertical-align: top">REMARKS</th>-->
<!--                                           </tr>-->
<!--                                   </thead>-->
<!--                              </table>-->
<!--                         </section>-->
<!--                   </div>-->
<!--                <br>-->
                <div id="uploader" style="max-width: 1000px">
                </div>
                <br>
                <div class="col-lg-3 col-lg-offset-4">
                    <button type="button" id="Create_Enquiry" class="btn btn-success" disabled>CREATE ENQUIRY</button>
                </div>
</div>
        </div>
    </div>
</div>
</div>
</div>
</body>
</HTML>