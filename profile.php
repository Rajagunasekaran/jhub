<?php
require_once("adminmenu.php");
?>
<!DOCTYPE html>
<HTML>
<head>
    <script>
    $(document).ready(function(){


        $('.preloader').show();
        var lg_create;
        var lg_update;

        $.ajax({
            type: "POST",
            url: "DB_Error_Msg.php",
            data:{"Option":'ERROR'},
            success: function(data){
                $('.preloader').hide();

                var value_array=JSON.parse(data);
                lg_create=value_array[3];
                lg_update=value_array[4];
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });

        $(".vali").change(function(){

            if(($('#username').val()!='')&&($('#password').val()!='')&&($('#useremail').val()!= '')&&($('#userstatus').val()!='SELECT'))
            {
                $('#sv_btn_addrow').removeAttr("disabled");
            }
            else
            {
                $('#sv_btn_addrow').attr('disabled','disabled');
            }
        })

        $('.special').change(function () {
            var str = $('#username').val();
            var str1 = $('#password').val();
            if (/^[a-zA-Z0-9- ]*$/.test(str) == false) {
                alert('Your String Contains illegal Characters.');  }
            if (/^[a-zA-Z0-9- ]*$/.test(str1) == false) {
                alert('Your String Contains illegal Characters.');  }
        })

        $('#tablecontainer').show();
        $('#add_btn_add').hide();
        $('#user_form').hide();
        $('#sv_btn_addrow').hide();
        $('#add_btn_addrow').hide();
        $('#update_btn_addrow').hide();

        $('#useremail').blur(function(){
            var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
            var valid = emailReg.test( $('#useremail').val());

            if(!valid) {
                show_msgbox("LOGIN CREATION",'ENTER VALID EMAIL',"success",false)
            } else {
               var mailid= $('#useremail').val();
                $('#useremail').val(mailid.toLowerCase());
            }
        })
        $(".preloader").show();
        $.ajax({
            type: 'POST',
            url: 'profile_db.php',
            data:{flag:4},
            success: function(data){
                $(".preloader").hide();
                $('.preloader').hide();
                var retdata=JSON.parse(data);
                $('#userstatus').append($('<option> SELECT </option>'));
                $.each(retdata, function(i, value) {
                    $('#userstatus').append($('<option>').text(value[1]).attr('value', value[1]));
//                        $('#userstatus').append($('<option>').text(value[1].attr('value', value[1])));
                })
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        })
        $(".preloader").show();
        $.ajax({
            type: 'POST',
            url: 'profile_db.php',
            data:{flag:1},
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

                $('#add_btn_addrow').removeAttr("disabled");
                $('#add_btn_addrow').show();;
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        })

        $(document).on('click','.ajaxedit',function(){
            var rowid=$(this).attr('id');
            $('#uid').val(rowid);
            var tds = $('#'+rowid).children('td');

            $('#username').val($(tds[1]).html());
            $('#password').val($(tds[2]).html());
            $('#useremail').val($(tds[3]).html());
            $('#userstatus').val($(tds[4]).html());


            $('#user_form').show();
            $('#add_btn_addrow').hide();
            $('#sv_btn_addrow').hide();
            $('#update_btn_addrow').show();
        });

        $('#add_btn_addrow').click(function(){
            $('#user_form').show();
            $('#add_btn_addrow').hide();
            $('#sv_btn_addrow').show();
            $('#username').val('');
            $('#password').val('');
            $('#useremail').val('');
            $('#userstatus').val('SELECT');

        });



        $('#sv_btn_addrow').click(function(){
            $(".preloader").show();
            $('#user_form').show();
            $('#add_btn_addrow').show();
            var username = $('#username').val();
            var password = $('#password').val();
            var useremail = $('#useremail').val();
            var userstatus = $('#userstatus').val();
            $.ajax({
                type: 'POST',
                url: 'profile_db.php',
                data:{flag:2,username:username,password:password,useremail:useremail,userstatus:userstatus},
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
                    show_msgbox("LOGIN CREATION",lg_create,"success",false)
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            })
            $('#username').val('');
            $('#password').val('');
            $('#useremail').val('');
            $('#userstatus').val('');
            $('#user_form').hide();
            $('#sv_btn_addrow').hide();
        });

        $('#update_btn_addrow').click(function(){
            $(".preloader").show();
            var rid = $('#uid').val();
            var username = $('#username').val();
            var password = $('#password').val();
            var useremail = $('#useremail').val();
            var userstatus = $('#userstatus').val();
            $.ajax({
                type: 'POST',
                url: 'profile_db.php',
                data:{flag:3,uid:rid,username:username,password:password,useremail:useremail,userstatus:userstatus},
                success: function(data){
                    $(".preloader").hide();
                    var values_array=data;
                    $('#tablecontainer').show();
                    $('#add_btn_addrow').show();
                    $('#user_form').hide();
                    $('#sv_btn_addrow').hide();
                    $('#update_btn_addrow').hide();

                    $('section').html(values_array);
                    $('#user_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    show_msgbox("LOGIN CREATION",lg_update,"success",false)
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                }
            })
        });
    });
    </script>
</head>
<body>
<div class="panel panel-info" >
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
    <div class="panel-heading" style="background:#FFE4C4;color:black;">
        <h3 class="panel-title" style="color:#000080;font-weight: bold">LOGIN CREATION</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal">

            <fieldset id="user_form">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label>USER NAME<span class="labelrequired">*</span></label>
                        <input class="form-control special vali " name="username" required id="username" placeholder="UserName"/>
                    </div>
                    <div class="col-md-4">
                        <label>PASSWORD<span class="labelrequired">*</span></label>
                        <input class="form-control special vali " name="password" id="password"  required type="password" placeholder="Password"/>
                    </div>

                    <input type="hidden" id="uid">

                </div>

                <div class="row form-group">
                    <div class="col-md-4">
                        <label>EMAIL<span class="labelrequired">*</span></label>
                        <input type="text" class="form-control vali" name="useremail" required id="useremail" placeholder="Email">
                    </div>
                    <div class="col-md-4 selectContainer">
                        <label>ROLE<span class="labelrequired">*</span></label>
                        <select class="form-control vali" id="userstatus" name="userstatus">
                        </select>
                    </div>
                </div>

            </fieldset>

            <div class="col-md-4">
                <button type="button" id="add_btn_addrow" class="btn submit_btn" disabled >ADD NEW USER</button>
            </div>
            <div class="col-lg-6">
                <button type="button" id="sv_btn_addrow" class="btn submit_btn" disabled >SAVE</button>
                <button type="button" id="update_btn_addrow" class="btn submit_btn" >UPDATE</button>
            </div>

            <div  id="tablecontainer"  hidden>
                <section >
                </section>
            </div>

        </form>
    </div>

</div>
</body>
</HTML>