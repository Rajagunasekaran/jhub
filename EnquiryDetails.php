<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("usermenu.php");
?>
<script>
    $(document).ready(function(){
        $("#Quantity").doValidation({rule:'numbersonly',prop:{realpart:6,leadzero:true}});
        $('#enquirtload').css("color", "#73c20e");
        //**************NEW ENQUIRY FROM FILE UPLOAD PROCESS START*********************//
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
        //**************NEW ENQUIRY FROM FILE UPLOAD PROCESS END*********************//
        //**************NEW ENQUIRY FROM REQUIRED DATE START*********************//
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
       //**************NEW ENQUIRY FROM REQUIRED DATE END*********************//
       //**************NEW ENQUIRY FROM INITIAL DATA LOAD FUNCTION START*********************//
        $('.preloader').show();
        $('textarea').autogrow({onInitialize: true});
        $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
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
        var values_array=[];
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                values_array=JSON.parse(xmlhttp.responseText);
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
        //**************NEW ENQUIRY FROM INITIAL DATA LOAD FUNCTION END*********************//
        //**************NEW ENQUIRY FROM FORM RESET FUNCTION START*********************//
        function ProductformClear()
        {
            $('#JP_EnquiryDetails')[0].reset();
            $('#Remarks').val('').height('40');
        }
        //**************NEW ENQUIRY FROM FORM RESET FUNCTION END*********************//
        //**************NEW ENQUIRY FROM FORM SAVE FUNCTION START*********************//
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
                    $('#Item_others').hide();
                    $('#Size_others').hide();
                    $('#Papertype_others').hide();
                    $('#Printingprocess_others').hide();
                    $('#Bindingprocess_others').hide();
                    $('#Treatmentprocess_others').hide();
                    $('#Finishingprocess_others').hide();
                    $('#Remarks').height(30);
                    $('#DeliveryLocation').height(30);
                    $('#Create_Enquiry').attr('disabled','disabled');
                    var uploader = $('#uploader').plupload('getUploader');
                    uploader.splice();
                    $('#Create_Enquiry').attr('disabled','disabled');
                }
                else
                {
                    show_msgbox("JHUB",msg,"success",false);
                }
             }
         });
     });
        //**************NEW ENQUIRY FROM FORM SAVE FUNCTION END*********************//
        //**************NEW ENQUIRY FROM FORM BUTTON VALIDATION FUNCTION START*********************//
        $('.Btn_validation').click(function(){
            $('#Create_Enquiry').removeAttr("disabled");
        });
        $('.Btn_add_validation').click(function(){
            $('#Create_Enquiry').attr('disabled','disabled');
        });
        function BtnValidation()
        {
            var jobtitle=$('#Job_tilte').val();
            var item=$('#Item').val();
            if(item=='SELECT'){item="";}
            if(item=='Others'){item=$('#Item_others').val();}
            var Size=$('#Size').val();
            if(Size=='SELECT'){Size="";}
            if(Size=='Others'){Size=$('#Size_others').val();}
            var Papertype=$('#Papertype').val();
            if(Papertype=='SELECT'){Papertype="";}
            if(Papertype=='Others'){Papertype=$('#Papertype_others').val();}
            var Paperweight=$('#Paperweight').val();
            if(Paperweight=='SELECT'){Paperweight="";}
            var Printingmethod=$('#Printingmethod').val();
            if(Printingmethod=='SELECT'){Printingmethod="";}
            var Printingprocess=$('#Printingprocess').val();
            if(Printingprocess=='SELECT'){Printingprocess="";}
            if(Printingprocess=='Others'){Printingprocess=$('#Printingprocess_others').val();}
            var Treatmentprocess=$('#Treatmentprocess').val();
            if(Treatmentprocess=='SELECT'){Treatmentprocess="";}
            if(Treatmentprocess=='Others'){Treatmentprocess=$('#Treatmentprocess_others').val();}
            var Finishingprocess=$('#Finishingprocess').val();
            if(Finishingprocess=='SELECT'){Finishingprocess="";}
            if(Finishingprocess=='Others'){Finishingprocess=$('#Finishingprocess_others').val();}
            var Bindingprocess=$('#Bindingprocess').val();
            if(Bindingprocess=='SELECT'){Bindingprocess="";}
            if(Bindingprocess=='Others'){Bindingprocess=$('#Bindingprocess_others').val();}
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
        //**************NEW ENQUIRY FROM FORM BUTTON VALIDATION FUNCTION START*********************//
        //**************NEW ENQUIRY FROM FORM OTHERS VALIDATION FUNCTION START*********************//
        $(document).on("change",'#Item', function (){
         if($('#Item').val()=='Others')
         { $('#Item_others').val('').show();} else{$('#Item_others').val('').hide();}
        });
        $(document).on("change",'#Size', function (){
            if($('#Size').val()=='Custom')
            {$('#Size_others').val('').show();} else{$('#Size_others').val('').hide();}
        });
        $(document).on("change",'#Papertype', function (){
            if($('#Papertype').val()=='Others')
            {$('#Papertype_others').val('').show();} else{$('#Papertype_others').val('').hide();}
        });
        $(document).on("change",'#Printingprocess', function (){
            if($('#Printingprocess').val()=='Others')
            {$('#Printingprocess_others').val('').show();} else{$('#Printingprocess_others').val('').hide();}
        });
        $(document).on("change",'#Treatmentprocess', function (){
            if($('#Treatmentprocess').val()=='Others')
            {$('#Treatmentprocess_others').val('').show();} else{$('#Treatmentprocess_others').val('').hide();}
        });
        $(document).on("change",'#Finishingprocess', function (){
            if($('#Finishingprocess').val()=='Others')
            {$('#Finishingprocess_others').val('').show();} else{$('#Finishingprocess_others').val('').hide();}
        });
        $(document).on("change",'#Bindingprocess', function (){
            if($('#Bindingprocess').val()=='Others')
            {$('#Bindingprocess_others').val('').show();} else{$('#Bindingprocess_others').val('').hide();}
        });
        //**************NEW ENQUIRY FROM FORM OTHERS VALIDATION FUNCTION END*********************//
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
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control Validation" id="Job_tilte" name="Job_tilte" maxlength="50" placeholder="Job Title"/>
                        </div>
                     </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>TYPE OF PRINT</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation" id="Item" name="Item"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                        <div class="col-md-3">
                            <input type="text" style="display:none" class="form-control Validation" id="Item_others" name="Item_others" maxlength="50" placeholder="Other's Type of Print"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>SIZE</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation" id="Size" name="Size"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                        <div class="col-md-3">
                            <input type="text" style="display:none" class="form-control Validation" id="Size_others" name="Size_others" maxlength="50" placeholder="Custom Size"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>PAPER TYPE</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation" id="Papertype" name="Papertype"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                        <div class="col-md-3">
                            <input type="text" style="display:none" class="form-control Validation" id="Papertype_others" name="Papertype_others" maxlength="50" placeholder="Other's Papertype"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>PAPER WEIGHT</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation" id="Paperweight" name="Paperweight"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>PRINTING METHOD</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation" id="Printingmethod" name="Printingmethod"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>PRINTING PROCESS</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation"  id="Printingprocess" name="Printingprocess"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                        <div class="col-md-3">
                            <input type="text" style="display:none" class="form-control Validation" id="Printingprocess_others" maxlength="50" name="Printingprocess_others" placeholder="Other's Printing Process"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>TREATMENT PROCESS</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation" id="Treatmentprocess" name="Treatmentprocess"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                        <div class="col-md-3">
                            <input type="text" style="display:none" class="form-control Validation" id="Treatmentprocess_others" maxlength="50" name="Treatmentprocess_others" placeholder="Other's Treatment Process"/>
                        </div>
                     </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>FINISHING PROCESS</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation" id="Finishingprocess" name="Finishingprocess"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                        <div class="col-md-3">
                            <input type="text" style="display:none" class="form-control Validation" id="Finishingprocess_others" maxlength="50" name="Finishingprocess_others" placeholder="Other's Finishing Process"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>BINDING PROCESS</label>
                        </div>
                        <div class="col-md-2">
                            <SELECT class="form-control Validation" id="Bindingprocess" name="Bindingprocess"><OPTION>SELECT</OPTION></SELECT>
                        </div>
                        <div class="col-md-3">
                            <input type="text" style="display:none" class="form-control Validation" id="Bindingprocess_others" maxlength="50" name="Bindingprocess_others" placeholder="Other's Binding Process"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>QUANTITY</label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control Validation" id="Quantity" maxlength="10" name="Quantity" placeholder="Quantity"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>DATE REQUIRED</label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control Validation" id="EnquiryDate" name="EnquiryDate" placeholder="Date"/>
                        </div>
                    </div>
                    <div class="row form-group">
                         <div class="col-md-3">
                             <label>DELIVERY LOCATION</label>
                         </div>
                         <div class="col-md-3">
                             <textarea class="form-control Validation" rows="2" id="DeliveryLocation" name="DeliveryLocation" placeholder="Delivery Location"></textarea>
                         </div>
                    </div>
                    <div class="row form-group">
                         <div class="col-md-3">
                             <label>REMARKS/SPECIAL REQUEST</label>
                         </div>
                        <div class="col-md-3">
                             <textarea class="form-control Validation" rows="2" id="Remarks" name="Remarks" placeholder="Remarks"></textarea>
                        </div>
                    </div>
                         <div class="col-md-4">
                             <input type="hidden" class="form-control Validation" name="productid" id="productid">
                         </div>
                    <div style="max-width: 1000px" id="uploader">
                    </div>
                    <br>
                    <div class="col-lg-3 col-lg-offset-4">
                        <button type="button" id="Create_Enquiry" class="btn btn-success" disabled>CREATE ENQUIRY</button>
                    </div>
                  </div>
               </fieldset>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</body>
</HTML>