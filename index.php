<?php
include('login.php'); // Includes Login Script
include "HEADER.php";
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
</head>
<script>
    $(document).ready(function(){
        $(document).on("change",'.Validation', function (){
           if($('#username').val()!='' && $('#password').val()!='')
           {
               $("#submit").removeAttr("disabled");
           }
           else
           {
               $("#submit").attr("disabled", "disabled");
           }
        });
    });
</script>
<body class="bg-theme" style="padding-top: 100px;">
<div style="text-align: center;margin-bottom:-80px"><img src="images/JHUB.png" style="max-height:250px;max-width:250px"></div>
<div class="lg-container" >

    <h2 style="background: #73c20e;color:#ffffff">LOGIN</h2>
    <form action="" id="lg-form" name="lg-form" method="post">

        <div>
            <label>USER NAME<span style="color:red">*</span></label>
            <input id="username" name="username" placeholder="User Name" maxlength="40" class="form-control Validation" type="text">
        </div>

        <div>
            <label>PASSWORD<span style="color:red">*</span></label>
            <input id="password" name="password" class="form-control Validation" placeholder="*********" maxlength="40" type="password">

        </div>

        <div>
            <button type="submit" id="submit" name="submit"  class="btn submit_btn" disabled>LOGIN</button>
        </div>

        <span> <?php echo $error; ?></span>
    </form>
</div>
</body>
</html>