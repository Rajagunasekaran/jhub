<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("usermenu.php");
?>
<HTML>

<script>
    $(document).ready(function(){

        $('#user_dashboard').css("color", "#73c20e");

        $(".preloader").show();
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

        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var value_array=JSON.parse(xmlhttp.responseText);
                $('#quotationupdatedcontainer').show();
                $('section').html(value_array[0]);
                $('#example').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#CanceledQuotationcontainer').show();
                $('section2').html(value_array[1]);
                $('#example1').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('section1').html(value_array[2]);
                $('#example2').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $(".preloader").hide();
            }
        }
        var Option="User_NotificationList";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option);
        xmlhttp.send();
    });
</script>
<body class="bg-theme">
<div class="container">
    <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <div class="panel-heading" style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold">USER NOTIFICATIONS</h3>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#newquotation" data-toggle="tab" aria-expanded="true" style="font-weight: bold;font-size: 17px">NEW QUOTATION</a></li>
                <li class=""><a href="#cancelquotation" data-toggle="tab" aria-expanded="true" style="font-weight: bold;font-size: 17px">CANCELLED ORDER</a></li>
                <li class=""><a href="#deliveredquotation" data-toggle="tab" aria-expanded="true" style="font-weight: bold;font-size: 17px">DELIVERED ORDER</a></li>
            </ul>
            <br>
            <br>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="newquotation">
                    <div  id="quotationupdatedcontainer" style="max-width:1000px;padding-left: 70px">
                        <section>

                        </section>
                    </div>
                </div>
                <div class="tab-pane fade" id="cancelquotation">
                    <div id="CanceledQuotationcontainer" style="max-width:1000px;padding-left: 70px">
                        <section2>

                        </section2>
                    </div>
                </div>
                <div class="tab-pane fade" id="deliveredquotation">
                    <div id="DeliveredquotationContainer" style="max-width:1000px;padding-left: 70px">
                        <section1>

                        </section1>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
</body>
</HTML
