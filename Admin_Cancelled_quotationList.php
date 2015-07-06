<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("adminmenu.php");
?>
<HTML>
<script>
    $(document).ready(function(){
        $(".preloader").show();
        $('#admincancelledlist').css("color", "#73c20e");
        $('#QT_Bacttolist').hide();
        var imageerror;
        var delivered;
        $.ajax({
            type: "POST",
            url: "DB_Error_Msg.php",
            data:{"Option":'ERROR'},
            success: function(data){
                $('.preloader').hide();
                var value_array=JSON.parse(data);
                imageerror=value_array[11];
                delivered=value_array[7];
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });

        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":'AdminCancelledQuotationList'},
            success: function(data){

                var values_array=data;
                $('#canceltablecontainer').show();
                $('section').html(values_array);
                $('#Deliveredlist_table').DataTable( {
                    "bSort" : false
                });
                $('#QT_Bacttolist').hide();
                $('#pdgdiv').hide();
                $('#recverdetails').hide();
                $("#fileToUpload").val('');
                $(".preloader").hide();
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
        $(document).on("click",'.QuotationView', function (){
            $(".preloader").show();
            var id=this.id;
            var splitid=id.split('/');
            var rowid=splitid[1];
            $('#temp_id').val(rowid);
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#table_Quotationview').show();
                    var Datas=values_array[0];
                    $('#Job_tilte').val(Datas[0]);
                    if(Datas[0]=='' || Datas[0]==null){$('#div_job_title').hide();}else{$('#div_job_title').show();}
                    $('#Item').val(Datas[1]);
                    if(Datas[1]=='' || Datas[1]==null){$('#div_typeof_print').hide();}else{$('#div_typeof_print').show();}
                    $('#Size').val(Datas[2]);
                    if(Datas[2]=='' || Datas[2]==null){$('#div_size').hide();}else{$('#div_size').show();}
                    $('#Papertype').val(Datas[3]);
                    if(Datas[3]=='' || Datas[3]==null){$('#div_Papertype').hide();}else{$('#div_Papertype').show();}
                    $('#Paperweight').val(Datas[4]);
                    if(Datas[4]=='' || Datas[4]==null){$('#div_Paperweight').hide();}else{$('#div_Paperweight').show();}
                    $('#Printingmethod').val(Datas[5]);
                    if(Datas[5]=='' || Datas[5]==null){$('#div_Printingmethod').hide();}else{$('#div_Printingmethod').show();}
                    $('#Printingprocess').val(Datas[6]);
                    if(Datas[6]=='' || Datas[6]==null){$('#div_Printingprocess').hide();}else{$('#div_Printingprocess').show();}
                    $('#Treatmentprocess').val(Datas[7]);
                    if(Datas[7]=='' || Datas[7]==null){$('#div_Treatmentprocess').hide();}else{$('#div_Treatmentprocess').show();}
                    $('#Finishingprocess').val(Datas[8]);
                    if(Datas[8]=='' || Datas[8]==null){$('#div_Finishingprocess').hide();}else{$('#div_Finishingprocess').show();}
                    $('#Bindingprocess').val(Datas[9]);
                    if(Datas[9]=='' || Datas[9]==null){$('#div_Bindingprocess').hide();}else{$('#div_Bindingprocess').show();}
                    $('#Quantity').val(Datas[10]);
                    if(Datas[10]=='' || Datas[10]==null){$('#div_Quantity').hide();}else{$('#div_Quantity').show();}
                    $('#EnquiryDate').val(Datas[11]);
                    if(Datas[11]=='' || Datas[11]==null || Datas[11]=='0000-00-00'){$('#div_EnquiryDate').hide();}
                    else{
                        $('#div_EnquiryDate').show();
                        var date=DTFormTable_DateFormat(Datas[11]);
                        $('#EnquiryDate').val(date);
                    }
                    $('#DeliveryLocation').val(Datas[12]);
                    if(Datas[12]=='' || Datas[12]==null){$('#div_DeliveryLocation').hide();}else{$('#div_DeliveryLocation').show();}
                    $('#Remarks').val(Datas[13]);
                    if(Datas[13]=='' || Datas[13]==null){$('#div_Remarks').hide();}else{$('#div_Remarks').show();}
                    $('#tempstatus').val(Datas[15]);
                    $('#Amount').val(Datas[14]);
                    $('#adminquotationviewid').text("STATUS : "+values_array[1])
                    $(".preloader").hide();
                    $('#canceltablecontainer').hide();
                    $('#QT_Bacttolist').show();
                    $('#pdgdiv').show();
                    $('#recverdetails').hide();
                    if(values_array[1]=='CONFIRMED ORDER')
                    {
                        $('#Btn_Delivered').show();
                        $('#fileuploadform').show();
                    }
                    else
                    {
                        $('#Btn_Delivered').hide();
                        $('#fileuploadform').hide();
                    }
                    $('#uploadimages').html('');
                    var data=values_array[2].split('/');
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
            }
            var Option="AdminQuotationView";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+rowid);
            xmlhttp.send();

        });
        function DTFormTable_DateFormat(inputdate){
            var string = inputdate.split("-");
            return string[2]+'-'+ string[1]+'-'+string[0];
        }
        $(document).on("click",'#QT_Bacttolist', function (){
            $('#canceltablecontainer').show();
            $('#fileuploadform').hide();
            $('#QT_Bacttolist').hide();
            $('#table_Quotationview').hide();
            $('#pdgdiv').hide();
            $('#recverdetails').hide();
        });
        $(document).on('click','.Quotationpdf',function(){
            var QT_id=$('#temp_id').val();
            var url=document.location.href='COMMON_PDF.php?inputValOne='+QT_id;
        });
        $(document).on("click",'#Btn_Delivered', function (){
            var QT_id=$('#temp_id').val();
            $(".preloader").show();
            var FormElement=document.getElementById('quotationdetails');
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
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#canceltablecontainer').show();
                    $('section').html(values_array);
                    $('#Deliveredlist_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers"
                    });
                    $('#QT_Bacttolist').hide();
                    $('#table_Quotationview').hide();
                    $('#fileuploadform').hide();
                    $('#pdgdiv').hide();
                    show_msgbox("JHUB",delivered,"success",false);
                    $('#recverdetails').hide();var uploader = $('#uploader').plupload('getUploader');
                    uploader.splice();
                    $(".preloader").hide();
                }
            }
            var Option="Delivered";
            xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option+"&Data="+QT_id+"&Files="+filesarray);
            xmlhttp.send(new FormData(FormElement));
        });
        $('.Btn_validation').click(function(){
            $('#Btn_Delivered').removeAttr("disabled");
        });
        $('.Btn_add_validation').click(function(){
            $('#Btn_Delivered').attr('disabled','disabled');
        });
    });
</script>
<body class="bg-theme">
<div class="container">
    <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <div class="panel-heading" style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold">CANCELLED ORDER DETAILS</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id="quotationdetails">
                <div style="padding-left: 25px" id="canceltablecontainer" class="table-responsive" hidden>
                    <div><h3 style="color:#337ab7;font-weight: bold" ></h3></div>
                    <section>
                    </section>
                </div>
                <div id="pdgdiv" hidden><a href="#" class="Quotationpdf"><img src="images/pdfimage.png" style="max-width:60px;max-height:60px;" alt="jhub"></a><input type="hidden" id="temp_id" name="temp_id"></div>

                <div class="col-lg-9 col-lg-offset-10">
                    <button type="button" id="QT_Bacttolist" class="btn btn-info" style="background-color:#337ab7;color:white" ><span class="glyphicon glyphicon-fast-backward"></span>     BACK</button>
                </div>
                <div style="padding-left: 25px" id="table_Quotationview" hidden>
                    <div><h3 style="color:#337ab7;font-weight: bold" id="adminquotationviewid"></h3></div>
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
                                <textarea class="form-control Validation" rows="2" id="DeliveryLocation" name="DeliveryLocation" placeholder="Delivery Location" readonly></textarea>
                            </div>
                        </div>
                        <div class="row form-group" id="div_Remarks">
                            <div class="col-md-3">
                                <label>REMARKS/SPECIAL REQUEST</label>
                            </div>
                            <div class="col-md-3">
                                <textarea class="form-control Validation" rows="2" id="Remarks" name="Remarks" placeholder="Remarks" readonly></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>AMOUNT</label>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control Validation amountonly" id="Amount" maxlength="10" readonly name="Amount" placeholder="Amount"/>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div  id="uploadimages"></div>
                    <br>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</HTML>