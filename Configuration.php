<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("adminmenu.php");
?>
<script>
    $(document).ready(function(){
        $('#Configuration').css("color", "#73c20e");
        var emailmessage;
        $.ajax({
            type: "POST",
            url: "DB_Error_Msg.php",
            data:{"Option":'ERROR'},
            success: function(data){
                $('.preloader').hide();
                var value_array=JSON.parse(data);
                emailmessage=value_array[10];
            },
            error: function(data){
                alert('error in getting'+JSON.stringify(data));
            }
        });
        //********************SETTINGS OPTION SELECT fUNCTION START**********************//
        $(document).on("click",'.Configchange', function (){
        $('.preloader').show();
        var value=$("input[name='Config']:checked").val();
        if(value=='Email_template')
            {
                $.ajax({
                    type: "POST",
                    url: "DB_EnquiryDetails.php",
                    data:{"Option":'EMAILTEMPLATE'},
                    success: function(data){
                     var value_array=data;
                     $('section').html(value_array);
                     $('#emailtemplate').DataTable( {
                          "aaSorting": [],
                          "pageLength": 10,
                          "responsive": true,
                          "sPaginationType":"full_numbers"
                        });
                      $('#Settingheader').text('EMAIL TEMPLATE DETAILS');
                        $('.preloader').hide();
                    },
                    error: function(data){
                        alert('error in getting'+JSON.stringify(data));
                        $('.preloader').hide();
                    }
                });
            }
           else if(value=='confirmmessage')
            {
                $.ajax({
                    type: "POST",
                    url: "DB_EnquiryDetails.php",
                    data:{"Option":'CONFIRMMESSAGE'},
                    success: function(data){
                        var value_array=data;
                        $('section').html(value_array);
                        $('#Confirm_message_table').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "responsive": true,
                            "sPaginationType":"full_numbers"
                        });
                        $('#Settingheader').text('CONFIRM MESSAGE DETAILS');
                        $('.preloader').hide();
                    },
                    error: function(data){
                        alert('error in getting'+JSON.stringify(data));
                        $('.preloader').hide();
                    }
                });
            }
        else if(value=='mailnotification')
        {
            $.ajax({
                type: "POST",
                url: "DB_EnquiryDetails.php",
                data:{"Option":'MAIL_NOTIFICATION'},
                success: function(data){
                    var value_array=data;
                    $('section').html(value_array);
                    $('#Mail_Notification_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#Settingheader').text('MAIL CONFIGURATION DETAILS');
                    $('.preloader').hide();
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                    $('.preloader').hide();
                }
            });
        }
        });
        //********************SETTINGS OPTION SELECT fUNCTION END**********************//
        var combineid;
        var previous_id;
        var cval;
        var ifcondition;
        //*************EMAIL TEMPLATE INLINE EDIT FUNCTION START**********************//
        $(document).on("click",'.ET_Edit', function (){
            $('.preloader').show();
            if(previous_id!=undefined){
                $('#'+previous_id).replaceWith("<td align='left' class='ET_Edit' id='"+previous_id+"' >"+cval+"</td>");
            }
            var cid = $(this).attr('id');
            var id=cid.split('_');
            ifcondition=id[0];
            combineid=id[1];
            previous_id=cid;
            cval = $(this).text();
            if(ifcondition=='Sub')
            {
                $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><textarea  class='ET_Update form-control' id=Email_Subject_"+combineid+">"+cval+"</textarea></td>");
                $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
            }
            if(ifcondition=='Body')
            {
                $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><textarea  class='ET_Update form-control' id=Email_Body_"+combineid+">"+cval+"</textarea></td>");
                $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
            }
            $('.preloader').hide();
        });
        //*************EMAIL TEMPLATE INLINE EDIT FUNCTION END**********************//
        //*************EMAIL TEMPLATE INLINE UPDATE FUNCTION START**********************//
        $(document).on("change blur",'.ET_Update', function (){
          var id=this.id;
          $('.preloader').show();
          var splittedid=id.split('_');
          var data=$('#'+id).val();
          if(splittedid[1]=='Subject')
          {
          var data={'Option':"EMAILTEMPLATEUPDATE",Title:'SUBJECT',DATA:data,ID:splittedid[2]}
          }
          if(splittedid[1]=='Body')
          {
          data={'Option':"EMAILTEMPLATEUPDATE",Title:'BODY',DATA:data,ID:splittedid[2]}
          }
            $.ajax({
                type: "POST",
                url: "DB_EnquiryDetails.php",
                data:data,
                success: function(data){
                    var value_array=data;
                    $('section').html(value_array);
                    $('#emailtemplate').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#Settingheader').text('EMAIL TEMPLATE DETAILS');
                    $('.preloader').hide();
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                    $('.preloader').hide();
                }
            });
        });
        //*************EMAIL TEMPLATE INLINE UPDATE FUNCTION END**********************//
        //*************CONFIRM MESSAGE INLINE EDIT FUNCTION START**********************//
        $(document).on("click",'.EMC_Edit', function (){
            $('.preloader').show();
            if(previous_id!=undefined){
                $('#'+previous_id).replaceWith("<td align='left' class='EMC_Edit' id='"+previous_id+"' >"+cval+"</td>");
            }
            var cid = $(this).attr('id');
            var id=cid.split('_');
            ifcondition=id[0];
            combineid=id[1];
            previous_id=cid;
            cval = $(this).text();
            if(ifcondition=='Title')
            {
                $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><textarea  class='EMC_Update form-control' id=EMC_Title_"+combineid+">"+cval+"</textarea></td>");
            }
            if(ifcondition=='Message')
            {
                $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><textarea  class='EMC_Update form-control' id=EMC_Message_"+combineid+">"+cval+"</textarea></td>");
            }
            $( "textarea" ).autogrow( { vertical : true, horizontal : true } );
            $('.preloader').hide();
        });
        //*************CONFIRM MESSAGE INLINE EDIT FUNCTION END**********************//
        //*************CONFIRM MESSAGE INLINE UPDATE FUNCTION START**********************//
        $(document).on("change blur",'.EMC_Update', function (){
            $('.preloader').show();
            var id=this.id;
            var splittedid=id.split('_');
            var data1=$('#'+id).val();
            if(splittedid[1]=='Title')
            {
                var data={'Option':"CONFIRM_MESSAGE_UPDATE",Title:'TITLE',DATA:data1,ID:splittedid[2]}
            }
            if(splittedid[1]=='Message')
            {
                data={'Option':"CONFIRM_MESSAGE_UPDATE",Title:'MESSAGE',DATA:data1,ID:splittedid[2]}
            }
            $.ajax({
                type: "POST",
                url: "DB_EnquiryDetails.php",
                data:data,
                success: function(data){
                    var value_array=data;
                    $('section').html(value_array);
                    $('#Confirm_message_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#Settingheader').text('CONFIRM MESSAGE DETAILS');
                    $('.preloader').hide();
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                    $('.preloader').hide();
                }
            });
        });
        //*************CONFIRM MESSAGE INLINE UPDATE FUNCTION END**********************//
        //*************MAIL NOTIFICATION INLINE EDIT FUNCTION START**********************//
        $(document).on("click",'.Mail_Edit', function (){
            $('.preloader').show();
            if(previous_id!=undefined){
                $('#'+previous_id).replaceWith("<td align='left' class='Mail_Edit' id='"+previous_id+"' >"+cval+"</td>");
            }
            var cid = $(this).attr('id');
            var id=cid.split('_');
            ifcondition=id[0];
            combineid=id[1];
            previous_id=cid;
            cval = $(this).text();
            if(ifcondition=='Data')
            {
                $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><input type='text' class='Mail_Update form-control' id=Mail_Data_"+combineid+"  value='"+cval+"'></td>");
            }
            $('.preloader').hide();
        });
        //*************MAIL NOTIFICATION INLINE EDIT FUNCTION END**********************//
        //*************MAIL NOTIFICATION INLINE UPDATE FUNCTION START**********************//
        $(document).on("change blur",'.Mail_Update', function (){
            $('.preloader').show();
            var id=this.id;
            var splittedid=id.split('_');
            var data1=$('#'+id).val();
            var mailid=mail_validate(data1);
            if(mailid=='valid')
            {
            data={'Option':"MAIL_NOTIFICATION_UPDATE",DATA:data1,ID:splittedid[2]}
            $.ajax({
                type: "POST",
                url: "DB_EnquiryDetails.php",
                data:data,
                success: function(data){
                    var value_array=data;
                    $('section').html(value_array);
                    $('#Mail_Notification_table').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "responsive": true,
                        "sPaginationType":"full_numbers"
                    });
                    $('#Settingheader').text('MAIL CONFIGURATION DETAILS');
                    $('.preloader').hide();
                },
                error: function(data){
                    alert('error in getting'+JSON.stringify(data));
                    $('.preloader').hide();
                }
            });
            }
            else
            {
                show_msgbox("JHUB",emailmessage,"success",false);
                $('.preloader').hide();
            }
        });
        //*************MAIL NOTIFICATION INLINE UPDATE FUNCTION END**********************//
        //***************MAIL ID VALIDATION*******************//
       function mail_validate(data1)
       {
           var CCRE_emailid=data1;
           var CCRE_atpos=CCRE_emailid.indexOf("@");
           var CCRE_dotpos=CCRE_emailid.lastIndexOf(".");
           if(CCRE_emailid.length>0)
           {
               if ((/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(CCRE_emailid) || "" == CCRE_emailid)&&(CCRE_atpos-1!=CCRE_emailid.indexOf(".")))
               {
                   var CCRE_emailchk="valid";
               }
               else
               {
                   CCRE_emailchk="invalid"
               }
           }
           else
           {
               CCRE_emailchk="invalid"
           }
           return CCRE_emailchk;
       }
    });
</script>
  <body class="bg-theme">
        <div class="container">
        <div class="panel panel-info" >
        <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"   /> </div>
          <div class="panel-heading" style="background:#73c20e;color:black;">
             <h3 class="panel-title" style="color:#ffffff;font-weight: bold">CONFIGURATION SETTINGS</h3>
          </div>
          <div class="panel-body">
              <form style="padding-left: 30px;" >
                  <fieldset>
                      <div class="row form-group">
                          <div class="col-md-6">
                              <input type="radio" class="Configchange" id="Confirm_message" name="Config" value="confirmmessage">CONFIRMATION MESSAGE DETAILS
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-6">
                              <input type="radio" class="Configchange" id="Emailt_emplate" name="Config" value="Email_template">EMAIL TEMPLATE DETAILS
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-6">
                              <input type="radio" class="Configchange" id="Mail_notification" name="Config" value="mailnotification">NOTIFICATION MAIL DETAILS
                          </div>
                      </div>
                      <div  id="ConfigurationSetting" class="table-responsive" style="max-width:900px;padding-left: 20px;overflow-x: hidden;">
                          <div><h3 style="color:#337ab7;font-weight: bold" id="Settingheader"></h3></div>
                          <section>

                          </section>
                      </div>
                  </fieldset>
              </form>
          </div>
        </div>
    </div>
  </body>