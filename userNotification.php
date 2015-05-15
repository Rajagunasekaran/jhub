<?php
//ver:0.01:Initial Version :SD    &     ED :31-03-2015 Done By Kumar R
require_once("usermenu.php");
?>
<html>
<script>
    $(document).ready(function(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {

                var value_array=JSON.parse(xmlhttp.responseText);
                if(value_array!=null)
                {
                for(var i=0;i<value_array.length;i++)
                {
                var imageurl="images/"+value_array[i][2];
                var tabledata='<div class="well"  style="max-height:110px;"><div class="col-sm-1" ><img  style="max-height:75px;max-width:75px" src='+imageurl+'></div><div class="col-sm-6">';
                tabledata+='<div><label style="color:#6495ED">'+value_array[i][0]+'</label></div>';
                tabledata+='<div><label style="color:#00008B">'+value_array[i][1]+'</label></div></div></div>';
                $('#notificationcontent').append(tabledata);
                var empltydiv='<div style="max-height:25px;"><label>              </label></div>';
                $('#notificationcontent').append(empltydiv);
                }
                }
                else
                {
                 var emptydiv='<div style="max-height:25px;color:red;><label style="text-align:center;">*****************There is no New Transactions************** </label></div>';
                 $('#notificationcontent').append(emptydiv);
                }
            }
        }
        var Option="UserNotification";
        xmlhttp.open("POST","DB_EnquiryDetails.php?Option="+Option);
        xmlhttp.send();
    });
</script>
<body>
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading"  style="background:#73c20e;color:black;">
            <h3 class="panel-title" style="color:#ffffff;font-weight: bold"><span class="glyphicon glyphicon-envelope" style="color:#000000"></span>   NOTIFICATIONS</h3>
        </div>
        <div class="panel-body">
           <div id="notificationcontent">

           </div>
        </div>
    </div>
    </div>
</body>
</html>