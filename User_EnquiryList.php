<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("usermenu.php");
?>
<HTML>
<script>
    $(document).ready(function(){
        $('.autogrowcomments').autogrow({onInitialize: true});
        $('#update_Bacttolist').hide();
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
                    var Datas=values_array[0];
                    $('#Job_tilte').val(value_array[0][1]);
                    if(value_array[0][1]=='' || value_array[0][1]==null){$('#div_job_title').hide();}else{$('#div_job_title').show();}
                    $('#Item').val(value_array[0][2]);
                    if(value_array[0][2]=='' || value_array[0][2]==null){$('#div_typeof_print').hide();}else{$('#div_typeof_print').show();}
                    $('#Size').val(value_array[0][3]);
                    if(value_array[0][3]=='' || value_array[0][3]==null){$('#div_size').hide();}else{$('#div_size').show();}
                    $('#Papertype').val(value_array[0][4]);
                    if(value_array[0][4]=='' || value_array[0][4]==null){$('#div_Papertype').hide();}else{$('#div_Papertype').show();}
                    $('#Paperweight').val(value_array[0][5]);
                    if(value_array[0][5]=='' || value_array[0][5]==null){$('#div_Paperweight').hide();}else{$('#div_Paperweight').show();}
                    $('#Printingmethod').val(value_array[0][6]);
                    if(value_array[0][6]=='' || value_array[0][6]==null){$('#div_Printingmethod').hide();}else{$('#div_Printingmethod').show();}
                    $('#Printingprocess').val(value_array[0][7]);
                    if(value_array[0][7]=='' || value_array[0][7]==null){$('#div_Printingprocess').hide();}else{$('#div_Printingprocess').show();}
                    $('#Treatmentprocess').val(value_array[0][8]);
                    if(value_array[0][8]=='' || value_array[0][8]==null){$('#div_Treatmentprocess').hide();}else{$('#div_Treatmentprocess').show();}
                    $('#Finishingprocess').val(value_array[0][9]);
                    if(value_array[0][9]=='' || value_array[0][9]==null){$('#div_Finishingprocess').hide();}else{$('#div_Finishingprocess').show();}
                    $('#Bindingprocess').val(value_array[0][10]);
                    if(value_array[0][10]=='' || value_array[0][10]==null){$('#div_Bindingprocess').hide();}else{$('#div_Bindingprocess').show();}
                    $('#Quantity').val(value_array[0][11]);
                    if(value_array[0][11]=='' || value_array[0][11]==null){$('#div_Quantity').hide();}else{$('#div_Quantity').show();}
                    $('#EnquiryDate').val(value_array[0][12]);
                    if(value_array[0][12]=='' || value_array[0][12]==null || value_array[0][12]=='0000-00-00'){$('#div_EnquiryDate').hide();}
                    else{
                        $('#div_EnquiryDate').show();
                        var date=DTFormTable_DateFormat(value_array[0][12]);
                        $('#EnquiryDate').val(date);
                    }
                    $('#DeliveryLocation').val(value_array[0][13]);
                    if(value_array[0][13]=='' || value_array[0][13]==null){$('#div_DeliveryLocation').hide();}else{$('#div_DeliveryLocation').show();}
                    $('#Remarks').val(value_array[0][14]);
                    if(value_array[0][14]=='' || value_array[0][14]==null){$('#div_Remarks').hide();}else{$('#div_Remarks').show();}
                    $('#tempstatus').val(value_array[0][15]);
                    $('#updatetablecontent').show();
                    $('#updateform').show();
                    $('#UQT_Bacttolist').hide();
                    $('#table_UserQuotationview').hide();
                    $('#pdgdiv').hide();
                    $('#tablecontainer').hide();
                     $('#uploadfilediv').show();
                    $('#product_updaterow').hide();
                    $('#update_Bacttolist').show();
                    $(".preloader").hide();
                }
                $('#uploadimages').html('');
                var data=values_array[1].split('/');
                if(data!=0)
                {
                    var headerdata='';
                    for(var i=0;i<data.length;i++)
                    {
                        var rowid=i+1;
                        if(i==0)
                        {
                            headerdata += '<div class="row form-group">';
                            headerdata += '<div class="col-md-3">';
                            headerdata += '<label>UPLOADED IMAGES</label>';
                            headerdata += '</div>';
                            headerdata += '<div class="col-md-5">';
                            headerdata += '<a href="download.php?filename=' + data[i] + '" class="links">' + rowid + '.' + data[i] + '</a></div>';
                            headerdata += '</div></div>';
                        }
                        else
                        {
                            headerdata += '<div class="row form-group">';
                            headerdata += '<div class="col-md-3">';
                            headerdata += '<label></label>';
                            headerdata += '</div>';
                            headerdata += '<div class="col-md-5">';
                            headerdata += '<a href="download.php?filename=' + data[i] + '" class="links">' + rowid + '.' + data[i] + '</a></div>';
                            headerdata += '</div></div>';
                        }
                    }
                    $('#uploadimages').append(headerdata);
                }
            }
            var option="userenquirysearch";
            xmlhttp.open("GET","DB_EnquiryDetails.php?option="+option+"&Data="+rowid);
            xmlhttp.send();
        });
        function DTFormTable_DateFormat(inputdate){
            var string = inputdate.split("-");
            return string[2]+'-'+ string[1]+'-'+string[0];
        }
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
            $('#update_Bacttolist').hide();
        });

    });
</script>
<body class="bg-theme">
<div class="container">
<div class="panel panel-info" >
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#73c20e;color:black;">
        <h3 class="panel-title" style="color:#ffffff;font-weight: bold">ENQUIRY DETAILS</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div style="padding-left: 20px" id="tablecontainer"  hidden>
                <section >
                </section>
            </div>
            <div id="pdgdiv" hidden><a href="#" class="UserQuotationpdf"><img src="images/pdfimage.jpg"  alt="StarHub"></a><input type="hidden" id="temp_id"></div>
            <div class="col-lg-9 col-lg-offset-10" style="padding-top:-100px;">
                <button type="button" hidden id="update_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white;" ><span class="glyphicon glyphicon-fast-backward"></span>    BACK</button>
            </div>
            <div id="updateform" hidden>
                <div><h3 style="color:#337ab7;font-weight: bold" >STATUS  : NEW</h3></div>
                <br>
                </div>
                    <div style="padding-left: 15px" id="updatetablecontent" hidden>
                        <br>
                        <form class="form-horizontal" id="Quotation_Save">
                            <div class="row form-group" id="div_job_title">
                                <div class="col-md-3">
                                    <label>JOB TITLE</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control Validation" id="Job_tilte" name="Job_tilte" maxlength="50" placeholder="Job Title" readonly/>
                                </div>
                            </div>
                            <div class="row form-group" id="div_typeof_print">
                                <div class="col-md-3">
                                    <label>TYPE OF PRINT</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Item" name="Item" placeholder="Item" readonly/>
                                </div>

                            </div>
                            <div class="row form-group" id="div_size">
                                <div class="col-md-3">
                                    <label>SIZE</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Size" name="Size" placeholder="Size" readonly/>
                                </div>

                            </div>
                            <div class="row form-group" id="div_Papertype">
                                <div class="col-md-3">
                                    <label>PAPER TYPE</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Papertype" name="Papertype" placeholder="PaperType" readonly/>
                                </div>

                            </div>
                            <div class="row form-group" id="div_Paperweight">
                                <div class="col-md-3">
                                    <label>PAPER WEIGHT</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Paperweight" name="Paperweight" placeholder="PaperWeight" readonly/>
                                </div>
                            </div>
                            <div class="row form-group" id="div_Printingmethod">
                                <div class="col-md-3">
                                    <label>PRINTING METHOD</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Printingmethod" name="Printingmethod" placeholder="PrintingMethod" readonly/>
                                </div>
                            </div>
                            <div class="row form-group" id="div_Printingprocess">
                                <div class="col-md-3">
                                    <label>PRINTING PROCESS</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation"  id="Printingprocess" name="Printingprocess" placeholder="PrintingProcess" readonly/>
                                </div>

                            </div>
                            <div class="row form-group" id="div_Treatmentprocess">
                                <div class="col-md-3">
                                    <label>TREATMENT PROCESS</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Treatmentprocess" name="Treatmentprocess" placeholder="TreatmentProcess" readonly/>
                                </div>

                            </div>
                            <div class="row form-group" id="div_Finishingprocess">
                                <div class="col-md-3">
                                    <label>FINISHING PROCESS</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Finishingprocess" name="Finishingprocess" placeholder="FinishingProcess" readonly/>
                                </div>

                            </div>
                            <div class="row form-group" id="div_Bindingprocess">
                                <div class="col-md-3">
                                    <label>BINDING PROCESS</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Bindingprocess" name="Bindingprocess" placeholder="BindingProcess" readonly/>
                                </div>

                            </div>
                            <div class="row form-group" id="div_Quantity">
                                <div class="col-md-3">
                                    <label>QUANTITY</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="Quantity" maxlength="10" name="Quantity" placeholder="Quantity" readonly/>
                                </div>
                            </div>
                            <div class="row form-group" id="div_EnquiryDate">
                                <div class="col-md-3">
                                    <label>DATE REQUIRED</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control Validation" id="EnquiryDate" name="EnquiryDate" placeholder="Date" readonly/>
                                </div>
                            </div>
                            <div class="row form-group" id="div_DeliveryLocation">
                                <div class="col-md-3">
                                    <label>DELIVERY LOCATION</label>
                                </div>
                                <div class="col-md-3">
                                    <textarea class="form-control Validation autogrowcomments" rows="2" id="DeliveryLocation" name="DeliveryLocation" placeholder="Delivery Location" readonly></textarea>
                                </div>
                            </div>
                            <div class="row form-group" id="div_Remarks">
                                <div class="col-md-3">
                                    <label>REMARKS/SPECIAL REQUEST</label>
                                </div>
                                <div class="col-md-3">
                                    <textarea class="form-control Validation autogrowcomments" rows="2" id="Remarks" name="Remarks" placeholder="Remarks" readonly></textarea>
                                </div>
                            </div>
                            <div  id="uploadimages"></div>
                        </form>
                        <br>
                    </div>
               </div>
        </form>
    </div>
</div>
</div>
</body>
</HTML>