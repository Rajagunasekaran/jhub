<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("adminmenu.php");
?>
<HTML>
<script>
    $(document).ready(function(){
        $('#adminconformedlist').css("color", "#73c20e");
        $(".preloader").show();
        $.ajax({
            type: "POST",
            url: "DB_EnquiryDetails.php",
            data:{"Option":'AdminRevisedQuotationList'},
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
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
    });
</script>
<body>
<div class="panel panel-success" >
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#73c20e;color:black;">
        <h3 class="panel-title" style="color:#000080;font-weight: bold">REVISED QUOTATION DETAILS</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div  id="tablecontainer"  hidden>
                <section >
                </section>
            </div>
            </form>
        </div>
    </div>
</body>
</HTML>