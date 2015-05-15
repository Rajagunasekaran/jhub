<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("adminmenu.php");
?>
<HTML>

<script>
    $(document).ready(function(){
        $('#Adminnotifications').css("color", "#73c20e");
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
                $('#enquirycontainer').show();
                $('section').html(value_array[0]);
                $('#example').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#conformcontainer').show();

                $('section2').html(value_array[1]);
                $('#example1').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#dashboard').show();
                $('section1').html(value_array[2]);
                $('#example2').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#dashboard').show();
                $('section4').html(value_array[3]);
                $('#example3').DataTable( {
                    "aaSorting": [],
                    "pageLength": 10,
                    "responsive": true,
                    "sPaginationType":"full_numbers"
                });
                $('#table_Quotationview').hide();
                $(".preloader").hide();
            }
        }
        var Option="AdminNotificationList";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option);
        xmlhttp.send();
    });
    </script>
<body class="bg-theme">
<div class="container">
    <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
        <div class="panel-heading" style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold">ADMIN NOTIFICATIONS</h3>
        </div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#newenquiry" data-toggle="tab" aria-expanded="false" style="font-weight: bold;font-size: 17px">NEW ENQUIRY</a></li>
                <li class=""><a href="#confirmed" data-toggle="tab" aria-expanded="true" style="font-weight: bold;font-size: 17px">CONFIRMED ORDER</a></li>
                <li class=""><a href="#cancel" data-toggle="tab" aria-expanded="true" style="font-weight: bold;font-size: 17px">CANCELLED ORDER</a></li>
                <li class=""><a href="#delivered" data-toggle="tab" aria-expanded="true" style="font-weight: bold;font-size: 17px">DELIVERED ORDER</a></li>
            </ul>
            <br>
            <br>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="newenquiry">
                    <div  id="enquirycontainer" style="max-width:1000px;padding-left: 70px">
                        <section>

                        </section>
                    </div>
                </div>
                <div class="tab-pane fade" id="confirmed">
                    <div id="reordercontainer" style="max-width:1000px;padding-left: 70px">
                        <section2>

                        </section2>
                    </div>
                </div>
                <div class="tab-pane fade" id="cancel">
                    <div id="conformcontainer" style="max-width:1000px;padding-left: 70px">
                        <section1>

                        </section1>
                    </div>
                </div>
                <div class="tab-pane fade" id="delivered">
                    <div id="deliveredcontainer" style="max-width:1000px;padding-left: 70px">
                        <section4>

                        </section4>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</HTML
