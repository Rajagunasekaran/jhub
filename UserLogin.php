            <?php
            //ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
            require_once("adminmenu.php");
            ?>
            <html>
            <script>
                $(document).ready(function(){
                    $('#Loginform').css("color", "#73c20e");
                    $('.preloader').show();
                $(".alphanumeric").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:false,autosize:true}});

                $('#update_btn_addrow').hide();
                $('#user_form').hide();
                $('#cancel_btn_addrow').show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        var values_array=JSON.parse(xmlhttp.responseText);
                        $('#userrole').append($('<option> SELECT </option>'));
                      for(var i=0;i<values_array[1].length;i++)
                      {
                          var data=values_array[1][i];
                          $('#userrole').append($('<option>').text(data[1]).attr('value', data[1]));
                      }
                        $('#tablecontainer').show();
                        $('section').html(values_array[0]);
                        $('#user_table').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "responsive": true,
                            "sPaginationType":"full_numbers"
                        });
                    }
                }
                    var Option="Role";
                    xmlhttp.open("POST","DB_UserLogin.php?Option="+Option);
                    xmlhttp.send();
                    var user_name;
                    var username_flag=0;
                    var emailerror;
                    var imageerror;
                    $.ajax({
                        type: "POST",
                        url: "DB_Error_Msg.php",
                        data:{"Option":'ERROR'},
                        success: function(data){
                            $('.preloader').hide();
                            var value_array=JSON.parse(data);
                            user_name=value_array[9];
                            emailerror=value_array[10];
                            imageerror=value_array[12];
                            $('.preloader').hide();
                        },
                        error: function(data){
                            alert('error in getting'+JSON.stringify(data));
                        }
                    });


                    $(document).on('click','#sv_btn_addrow',function(){
                        var FormElement=document.getElementById('loginformdetails');
                        $('.preloader').show();
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function()
                        {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                            {
                                var values_array=JSON.parse(xmlhttp.responseText);
                                $('#tablecontainer').show();
                                $('section').html(values_array);
                                $('#user_table').DataTable( {
                                    "aaSorting": [],
                                    "pageLength": 10,
                                    "responsive": true,
                                    "sPaginationType":"full_numbers"
                                });
                                UserFormClear();
                                $('#update_btn_addrow').hide();
                                $('#user_form').hide();
                                $('#sv_btn_addrow').hide();
                                $('#cancel_btn_addrow').hide();
                                $('#AddNewRow').show();
                                $('.preloader').hide();
                            }
                        }
                        var Optionvalue="UserInsert";
                        xmlhttp.open("POST","DB_UserLogin.php?Option="+Optionvalue,true);
                        xmlhttp.send(new FormData(FormElement));
                    });
                    $(document).on('click','#update_btn_addrow',function(){
                        $('.preloader').show();
                        var FormElement=document.getElementById('loginformdetails');
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function()
                        {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                            {
                                var values_array=JSON.parse(xmlhttp.responseText);
                                $('#tablecontainer').show();
                                $('section').html(values_array);
                                $('#user_table').DataTable( {
                                    "aaSorting": [],
                                    "pageLength": 10,
                                    "responsive": true,
                                    "sPaginationType":"full_numbers"
                                });
                                UserFormClear();
                                $('#update_btn_addrow').hide();
                                $('#user_form').hide();
                                $('#sv_btn_addrow').hide();
                                $('#cancel_btn_addrow').hide();
                                $('#AddNewRow').show();
                                $('.preloader').hide();
                            }
                        }
                        var Optionvalue="UserInsert";
                        xmlhttp.open("POST","DB_UserLogin.php?Option="+Optionvalue,true);
                        xmlhttp.send(new FormData(FormElement));
                    });
                    $(document).on('click','#AddNewRow',function(){
                        $('#update_btn_addrow').hide();
                        $('#user_form').show();
                        $('#sv_btn_addrow').show();
                        $('#cancel_btn_addrow').show();
                        $('#AddNewRow').hide();
                        UserFormClear();
                    });
                    $(document).on('click','#cancel_btn_addrow',function(){
                        $('#update_btn_addrow').hide();
                        $('#user_form').hide();
                        $('#sv_btn_addrow').hide();
                        $('#cancel_btn_addrow').hide();
                        $('#AddNewRow').show();
                        UserFormClear();
                    });
                    function UserFormClear()
                    {
                        $('#username').val('');
                        $('#password').val('');
                        $('#useremail').val('');
                        $('#companyname').val('');
                        $('#contactperson').val('');
                        $('#nric_no').val('');
                        $('#userrole').val('SELECT');
                        $('#uid').val();
                        $( "#imagediv" ).empty();
                        $('#loginformdetails')[0].reset();
                        $('#sv_btn_addrow').attr('disabled','disabled');
                        $('#update_btn_addrow').attr('disabled','disabled');
                    }
                    $(document).on('change','.validation',function(){
                    if($('#useremail').val()!='')
                    {
                    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
                    var valid = emailReg.test( $('#useremail').val());

                    if(!valid) {
                        var flag=0;
                        show_msgbox("JHUB",emailerror,"error",false)
                    } else {
                        var mailid= $('#useremail').val();
                        $('#useremail').val(mailid.toLowerCase());
                        flag=1;
                    }
                    }
                    if($('#username').val()!="" && $('#password').val()!="" && flag==1 && $('#designation').val()!="" && $('#userrole').val()!='SELECT'
                        && $('#companyname').val() && $('#contactperson').val())
                    {
                        $('#sv_btn_addrow').removeAttr("disabled");
                        $('#update_btn_addrow').removeAttr("disabled");
                    }
                   else
                    {
                        $('#sv_btn_addrow').attr('disabled','disabled');
                        $('#update_btn_addrow').attr('disabled','disabled');
                    }
                    });

                    $(document).on('click','.Edit',function(){
                        var rowid=$(this).attr('id');
                        var splitrowid=rowid.split('/');
                        var tds = $('#'+splitrowid[1]).children('td');
                        $('#uid').val(splitrowid[1]);
                        $('#username').val($(tds[1]).html());
                        $('#password').val($(tds[2]).html());
                        $('#companyname').val($(tds[3]).html());
                        $('#contactperson').val($(tds[4]).html());
                        $('#useremail').val($(tds[5]).html());
                        $('#nric_no').val($(tds[6]).html());
                        $('#userrole').val($(tds[7]).html());
                        $( "#imagediv" ).empty();
                        var imageurl='images/'+splitrowid[2];
                        if(splitrowid[2]!='')
                        {
                        var image="<img src='"+imageurl+"' style='max-width:50px;max-height:50px'>";
                        $('#imagediv').append(image);
                        }
                        $('#update_btn_addrow').show();
                        $('#user_form').show();
                        $('#sv_btn_addrow').hide();
                        $('#cancel_btn_addrow').show();
                        $('#AddNewRow').hide();

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
                                $('#user_table').DataTable( {
                                    "aaSorting": [],
                                    "pageLength": 10,
                                    "responsive": true,
                                    "sPaginationType":"full_numbers"
                                });
                                UserFormClear();
                                $('#update_btn_addrow').hide();
                                $('#user_form').hide();
                                $('#sv_btn_addrow').hide();
                                $('#cancel_btn_addrow').hide();
                                $('#AddNewRow').show();
                                $('.preloader').hide();
                            }
                        }
                        var Optionvalue="Delete";
                        xmlhttp.open("POST","DB_UserLogin.php?Option="+Optionvalue+"&Data="+splitrowid[1],true);
                        xmlhttp.send();
                    });
                    $(document).on("change",'.fileextensionchk', function (){
                        var data= $('#fileToUpload').val();
                        var datasplit=data.split('.');
                        var ext=datasplit[1].toUpperCase();
                        if(ext=='JPG'|| ext=='PNG' || ext=='JPEG' || data==undefined || data=="")
                        {   }
                        else
                        {
                            $("#fileToUpload").val('');
                            show_msgbox("JHUB",imageerror,"error",false);
                        }
                    });
                    $('#username').change(function(){
                        var username=$('#username').val();
                        if(username!="")
                        {
                            var xmlhttp=new XMLHttpRequest();
                            xmlhttp.onreadystatechange=function()
                            {
                                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                                {
                                    var value=xmlhttp.responseText;
                                    if(value!="")
                                    {
                                        show_msgbox("JHUB",user_name,"error",false);
                                        $('#update_btn_addrow').attr('disabled','disabled');
                                        $('#sv_btn_addrow').attr('disabled','disabled');
                                        $('#username').val('');
                                    }
                                }
                            }
                            var Optionvalue="Usercheck";
                            xmlhttp.open("POST","DB_UserLogin.php?Option="+Optionvalue+"&User="+username,true);
                            xmlhttp.send();
                        }
                    });
                    $('.alpa_numeric').keyup(function() {
                        var $th = $(this);
                        $th.val( $th.val().replace(/[^a-zA-Z0-9 ]/g, function(str) {
                            return '';
                        } ) );
                    });
                });

            </script>
            <body class="bg-theme">
            <div class="container">
                <div class="panel panel-info" >
                    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
                    <div class="panel-heading" style="background:#73c20e;color:black;">
                        <h3 class="panel-title" style="color:#ffffff;font-weight: bold">LOGIN CREATION</h3>
                    </div>
                    <div class="panel-body">
                        <div id="enquiry_heading">
                        </div>
                        <form id="loginformdetails" class="form-horizontal">
                            <fieldset id="user_form">
                                <div class="row form-group">
                                    <div class="col-md-3">
                                        <label>USER NAME<span class="labelrequired">*</span></label>
                                        <input class="form-control alpa_numeric usercheck validation textboxwidth" name="username" maxlength="40" required id="username" placeholder="UserName"/>
                                    </div>
                                    <div class="col-md-3">
                                        <label>PASSWORD<span class="labelrequired">*</span></label>
                                        <input class="form-control special validation textboxwidth" name="password" id="password" maxlength="40" required type="password" placeholder="Password"/>
                                    </div>
                                    <div class="col-md-3">
                                        <label>COMPANY NAME<span class="labelrequired">*</span></label>
                                        <input type="text" class="form-control validation textboxwidth" name="companyname" required id="companyname" maxlength="50" placeholder="ContactName">
                                    </div>
                                    <input type="hidden" id="uid" name="uid">
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-3">
                                        <label>CONTACT PERSON<span class="labelrequired">*</span></label>
                                        <input type="text" class="form-control validation textboxwidth" name="contactperson" required id="contactperson" maxlength="50" placeholder="ContactNo">
                                    </div>
                                    <div class="col-md-3">
                                        <label>EMAIL<span class="labelrequired">*</span></label>
                                        <input type="text" class="form-control validation textboxwidth" name="useremail" required id="useremail" maxlength="50" placeholder="Email">
                                    </div>
                                    <div class="col-md-3 selectContainer">
                                        <label>NRIC NO</label>
                                        <input type="text" class="form-control validation textboxwidth alpa_numeric" id="nric_no" MAXLENGTH="10" name="nric_no" placeholder="NRIC No">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-3 selectContainer">
                                        <label>ROLE<span class="labelrequired">*</span></label>
                                        <select class="form-control validation textboxwidth" id="userrole" name="userrole">
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>IMAGE</label>
                                        <input type="file" name="fileToUpload" onclick="imagediv()" class="validation fileextensionchk" id="fileToUpload">
                                    </div>
                                    <div id="imagediv">

                                    </div>
                                </div>
                                <div class="col-lg-7 col-lg-offset-8">
                                    <button type="button" id="sv_btn_addrow" class="btn submit_btn" disabled>SAVE</button>
                                    <button type="button" id="update_btn_addrow" class="btn submit_btn" disabled>UPDATE</button>
                                    <button type="button" id="cancel_btn_addrow" class="btn submit_btn" >CANCEL</button>
                                </div>
                            </fieldset>
                            <div style="padding-left: 25px" id="tablecontainer" hidden>
                                <div class="col-lg-9">
                                    <button type="button" id="AddNewRow" class="btn submit_btn">ADD NEW</button>
                                </div>
                                <section >
                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
            </body>
            </html>