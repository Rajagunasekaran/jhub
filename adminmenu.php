<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
include('session.php');
include "HEADER.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
</head>

<body style="padding-left: 50px; max-width: 1400px">
<!-- <div class="container">-->
<!--<img src="images/logo.png" alt="StarHub">-->
<div class="row">
    <div class="col-sm-6" style="text-align:left;"><img src="images/JHUB.png" style="max-height:100px;max-width:200px;"></div>

    <?php
    if($imgname!="")
    {
        ?>
        <div class="col-sm-6" style="text-align:right;"><img src="<?php echo $img_url ?>" style="max-height:100px;max-width:75px"><br><span style="font-size: 16px;padding-right: 20px;"><?php echo strtoupper($login_session) ?></span></div>
    <?php
    }
    else{?>
        <div class="col-sm-6" style="text-align:right;"><img style="max-height:100px;max-width:75px"/><br><br><br><br><span style="font-size: 16px;"><?php echo strtoupper($login_session) ?></span></div>
    <?php
    }
    ?>
</div>
<div class="bs-example pagesize" >

    <nav  id="myNavbar" class="navbar navbar-default" role="navigation">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div  class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!--            <ul class="nav navbar-nav">-->
            <ul class="nav navbar-nav">
                <li><a href="AdminNotifications.php" id="Adminnotifications"><span class="glyphicon glyphicon-home" style="color:#ffffff"></span>  DASHBOARD</a></li>
                <li><a href="UserLogin.php" id="Loginform"  class="profileform">LOGIN CREATION</a></li>
                <li><a href="Admin_EnquiryList.php" id="Adminenquirylist" class="entrylist">NEW ENQUIRY</a></li>
                <li><a href="Admin_QuotationList.php" id="adminquotationlist">QUOTATION STATUS</a> </li>
                <li><a href="Admin_Cancelled_quotationList.php" id="admincancelledlist">CANCELLED ORDER</a></li>
                <li><a href="Admin_Confirmed_quotationList.php" id="adminconformedlist">CONFIRMED ORDER</a></li>
                <li><a href="Admin_Delivered_quotationList.php" id="admindeliveredlist">DELIVERED ORDER</a></li>
                <li><a href="Configuration.php" id="Configuration">SETTINGS</a></li>
            </ul>
            <!--            </ul>-->
            <ul class="nav navbar-nav navbar-right">
<!--                <li><a><span class="glyphicon glyphicon-user" style="color:#ffffff";></span>--><?php //echo strtoupper($login_session) ?><!--</a></li>-->
                <li><a href="logout.php">LOGOUT  <i class="fa fa-power-off" style="color: white"></i></a></li>

            </ul>

        </div><!-- /.navbar-collapse -->
    </nav>
</div>
<div id="formloading">

</div>
<!-- </div>-->
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);


        $(document).on("click",'.profileform', function (){
//                $( "#formloading").css('display','none');
            $('#formloading').load('profile.php')
//                $( "#formloading").removeAttr('style');
        });
        $(document).on("click",'.entrylist', function (){
            $('#formloading').load('Admin_EnquiryList.php')
        });
    })();

</script>
</body>
</html>