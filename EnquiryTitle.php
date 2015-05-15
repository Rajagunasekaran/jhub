<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("adminmenu.php");
?>
<html>
<script>
    $(document).ready(function(){
        $('#Enquirytitle').css("color", "#73c20e");
        $(".preloader").show();
        var enquiryerror;
        $.ajax({
            type: "POST",
            url: "DB_Error_Msg.php",
            data:{"Option":'ERROR'},
            success: function(data){
                $('.preloader').hide();
                var value_array=JSON.parse(data);
                enquiryerror=value_array[13];
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
        $('#EnquiryTitle').hide();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        var values_array=JSON.parse(xmlhttp.responseText);

                        $('section').html(values_array);
                        $('#Enquiry_titlelist').DataTable({
                            "aaSorting": [],
                            "pageLength": 10,
                            "responsive": true,
                            "sPaginationType":"full_numbers"
                        });
                        $('#tablecontainer').show();
                        $(".preloader").hide();
                    }
                }
              var Optionvalue="ProductList";
              xmlhttp.open("POST","DB_EnquiryTitle.php?Option="+Optionvalue,true);
              xmlhttp.send();
        $(document).on('change','#Enquiry_Title',function(){
            var Enquirytitle=$('#Enquiry_Title').val();
            if(Enquirytitle!="")
            {
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        var value=xmlhttp.responseText;
                        if(value!="")
                        {
                            show_msgbox("J-PRINT",enquiryerror,"error",false);
                            $('#EnquiryTitle').val('');
                            $('#Btn_Enquiry_addrow').attr('disabled','disabled')
                            $('#Btn_Enquiry_updaterow').attr('disabled','disabled')
                        }
                        else
                        {
                            $('#Btn_Enquiry_addrow').removeAttr("disabled");
                            $('#Btn_Enquiry_updaterow').removeAttr("disabled");
                        }
                    }
                }
                var Optionvalue="EnquiryCheck";
                xmlhttp.open("POST","DB_EnquiryTitle.php?Option="+Optionvalue+"&EnquiryTitle="+Enquirytitle,true);
                xmlhttp.send();
            }
        });
        $(document).on('click','#Btn_Enquiry_addrow',function(){
            $(".preloader").show();
            var Enquirytitle=$('#Enquiry_Title').val();
            if(Enquirytitle!="")
            {
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        var values_array=JSON.parse(xmlhttp.responseText);
                        $('#tablecontainer').show();
                        $('section').html(values_array);
                        $('#Enquiry_titlelist').DataTable({
                            "aaSorting": [],
                            "pageLength": 10,
                            "responsive": true,
                            "sPaginationType":"full_numbers"
                        });
                        $('#Enquiry_Title').val('');
                        $('#Btn_Enquiry_addrow').attr('disabled','disabled')
                        $('#Btn_Enquiry_updaterow').attr('disabled','disabled')
                        $('#EnquiryTitle').hide();
                        $('#Btn_Enquiry_addnew').show();
                        $('#ET_id').val('');
                        $(".preloader").hide();
                    }
                }
                var Optionvalue="EnquiryInsert";
                xmlhttp.open("POST","DB_EnquiryTitle.php?Option="+Optionvalue+"&EnquiryTitle="+Enquirytitle,true);
                xmlhttp.send();
            }
        });
        $(document).on('click','#Btn_Enquiry_updaterow',function(){
            $(".preloader").show();
            var Enquirytitle=$('#Enquiry_Title').val();
            var rowid=$('#ET_id').val();
            if(Enquirytitle!="" && rowid!="")
            {
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        var values_array=JSON.parse(xmlhttp.responseText);
                        $('#tablecontainer').show();
                        $('section').html(values_array);
                        $('#Enquiry_titlelist').DataTable({
                            "aaSorting": [],
                            "pageLength": 10,
                            "responsive": true,
                            "sPaginationType":"full_numbers"
                        });
                        $('#Enquiry_Title').val('');
                        $('#Btn_Enquiry_addrow').attr('disabled','disabled')
                        $('#Btn_Enquiry_updaterow').attr('disabled','disabled')
                        $('#EnquiryTitle').hide();
                        $('#Btn_Enquiry_addnew').show();
                        $('#ET_id').val('');
                        $(".preloader").hide();
                    }
                }
                var Optionvalue="EnquiryUpdate";
                xmlhttp.open("POST","DB_EnquiryTitle.php?Option="+Optionvalue+"&EnquiryTitle="+Enquirytitle+"&rowid="+rowid,true);
                xmlhttp.send();
            }
        });

        $(document).on('click','.Delete',function(){
            $('.preloader').show();
            var rowid=$(this).attr('id');
            var splitrowid=rowid.split('/');
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#tablecontainer').show();
                    $('section').html(values_array);
                    $('#Enquiry_titlelist').DataTable({
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#Enquiry_Title').val('');
                    $('#Btn_Enquiry_addrow').attr('disabled','disabled')
                    $('#Btn_Enquiry_updaterow').attr('disabled','disabled')
                    $('#EnquiryTitle').hide();
                    $('#Btn_Enquiry_addnew').show();
                    $('#ET_id').val('');
                    $(".preloader").hide();
                }
            }
            var Optionvalue="EnquiryDelete";
            xmlhttp.open("POST","DB_EnquiryTitle.php?Option="+Optionvalue+"&Data="+splitrowid[1],true);
            xmlhttp.send();
        });
        $(document).on('click','#Btn_Enquiry_addnew',function(){
            $('#EnquiryTitle').show();
            $('#Btn_Enquiry_addnew').hide();
            $('#Btn_Enquiry_updaterow').hide();
            $('#Btn_Enquiry_addrow').show();
        });
        $(document).on('click','.Edit',function(){
            var rowid=$(this).attr('id');
            var splitrowid=rowid.split('/');
            var tds = $('#'+splitrowid[2]).children('td');
            $('#ET_id').val(splitrowid[1]);
            $('#Enquiry_Title').val($(tds[1]).html());
            $('#Btn_Enquiry_addnew').hide();
            $('#Btn_Enquiry_updaterow').show();
            $('#Btn_Enquiry_addrow').hide();
            $('#EnquiryTitle').show();

        });
    });
</script>
<body>
<div class="container">
    <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <div class="panel-heading" style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold">ENQUIRY TITLE CREATION</h3>
        </div>
        <div class="panel-body">
            <form id="EnquiryTitle" class="form-horizontal">
                <div class="row form-group">
                    <div class="col-md-3">
                        <label>ENQUIRY TITLE</label>
                        <input type="text" id="Enquiry_Title" name="Enquiry_Title" class="form-control enquirytitle">
                        <input type="hidden" id="ET_id" name="ET_id" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3">
                    <button type="button" id="Btn_Enquiry_addrow" class="btn submit_btn" disabled>SAVE</button>
                    <button type="button" hidden id="Btn_Enquiry_updaterow" class="btn submit_btn" disabled>UPDATE</button>
                </div>
            </form>
            <div id="tablecontainer" style="width:800px">
                <button type="button" id="Btn_Enquiry_addnew" class="btn submit_btn">ADD NEW</button>
                <section>
                </section>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>

