<?php
require_once("adminmenu.php");
?>
<HTML>
<body>
    <div class="panel panel-success" >
        <div class="panel-heading" style="background:#FFE4C4;color:black;">
            <h3 class="panel-title">Login Creation</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                    <div class="row form-group">
                        <div class="col-md-4">
                            <label>USER NAME</label>
                            <input class="form-control" name="username" id="username" placeholder="UserName"/>
                        </div>
                        <div class="col-md-4">
                            <label>PASSWORD</label>
                            <input class="form-control" name="password" id="password" placeholder="Password"/>
                        </div>

                        <div class="col-md-4">
                            <label>CONFORM PASSWORD</label>
                            <input type="text" class="form-control" name="conformpassword" id="conformpassword" placeholder="Conform Password">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-4">
                            <label>EMAIL</label>
                            <input type="text" class="form-control" name="useremail" id="useremail" placeholder="Email">
                        </div>
                        <div class="col-md-4 selectContainer">
                            <label>STATUS</label>
                            <select class="form-control" id="userstatus" name="userstatus">
                                <option>SELECT</option>
                                <option>ADMIN</option>
                                <option>USER</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-8 col-lg-offset-9">
                        <button type="button" id="sv_btn_addrow" class="btn btn-success">CREATE</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</body>
</HTML>