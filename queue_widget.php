<?php include "HEADER.php";?>
<head>
<!--<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>-->

<!--<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />-->
<!--<link rel="stylesheet" href="../../js_upload/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />-->

<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>-->

<!--&lt;!&ndash; production &ndash;&gt;-->
<!--<script type="text/javascript" src="../../js_upload/plupload.full.min.js"></script>-->
<!--<script type="text/javascript" src="../../js_upload/jquery.ui.plupload/jquery.ui.plupload.js"></script>-->

</head>
<body style="font: 13px Verdana; background: #eee; color: #333">
<form id="form" method="post" action="../dump.php">
	<div id="uploader" style="max-width: 800px">
	</div>
	<br />
	<input type="submit" value="Submit" />
</form>

<script type="text/javascript">

$(function() {
	$("#uploader").plupload({
		// General settings
		runtimes : 'html5,flash,silverlight,html4',
		url : 'upload.php',

		// User can upload no more then 20 files in one go (sets multiple_queues to false)
		max_file_count: 20,
		
		chunk_size: '1mb',

		// Resize images on clientside if we can
		resize : {
			width : 200, 
			height : 200, 
			quality : 90,
			crop: true // crop to exact dimensions
		},
		
		filters : {
			// Maximum file size
			max_file_size : '1000mb',
			// Specify what files to browse for
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png,pdf"},
				{title : "Zip files", extensions : "zip"}
			]
		},

		// Rename files by clicking on their titles
		rename: true,
		
		// Sort files
		sortable: true,

		// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
		dragdrop: true,

		// Views to activate
		views: {
			list: true,
			thumbs: true, // Show thumbs
			active: 'thumbs'
		},

		// Flash settings
		flash_swf_url : '../../js/Moxie.swf',

		// Silverlight settings
		silverlight_xap_url : '../../js/Moxie.xap'
	});


	// Handle the case when form was submitted before uploading has finished
    $('.Btn_validation').click(function(){
    });
	$('#form').submit(function(e) {
		// Files in queue upload them first
        var files=$('#uploader').plupload('getFiles');
        alert(files.length);
        for(var i=0;i<files.length;i++)
        {
         var name=files[0].name;
            alert(name)
        }
		if ($('#uploader').plupload('getFiles').length > 0) {

			// When all files are uploaded submit form
			$('#uploader').on('complete', function() {
                alert('enter');
				$('#form')[0].submit();
			});

			$('#uploader').plupload('start');
		} else {
			alert("You must have at least one file in the queue.");
		}
		return false; // Keep the form from submitting
	});
});
</script>
</body>
</html>
