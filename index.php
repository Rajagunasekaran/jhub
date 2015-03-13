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
<body>

<div class="lg-container">
    <h2 style="background: #73c20e;color:#ffffff">Login Form</h2>

    <form action="" id="lg-form" name="lg-form" method="post">

        <div>
            <label>USERNAME<span style="color:red">*</span></label>
            <input id="username" name="username" placeholder="UserName" class="form-control" type="text">
        </div>

        <div>
            <label>PASSWORD<span style="color:red">*</span></label>
            <input id="password" name="password" class="form-control" placeholder="*********" type="password">

        </div>

        <div>
            <button type="submit"  name="submit"  class="btn submit_btn">Login</button>
        </div>

        <span> <?php echo $error; ?></span>
    </form>
</div>
</body>
</html>