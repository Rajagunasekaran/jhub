<?php
include('session.php');
include "HEADER.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
</head>

<body>
<!-- <div class="container">-->
<img src="images/logo.png" alt="StarHub">
<div class="bs-example">

    <nav id="myNavbar" class="navbar navbar-default" role="navigation">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!--            <ul class="nav navbar-nav">-->
            <ul class="nav navbar-nav">
                <li><a href="DashBoard.php" ><span class="glyphicon glyphicon-home" style="color:#ffffff"></span>  DASHBOARD</a></li>
                <li><a href="profile.php"  class="profileform">LOGIN CREATION</a></li>
                <li><a href="Admin_EnquiryList.php"  class="entrylist">ENQUIRY</a></li>
                <li><a href="Admin_QuotationList.php" >QUOTATION</a></li>
            </ul>
            <!--            </ul>-->
            <ul class="nav navbar-nav navbar-right">
                <li><a><span class="glyphicon glyphicon-user" style="color:#ffffff";></span><?php echo strtoupper($login_session) ?></a></li>
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