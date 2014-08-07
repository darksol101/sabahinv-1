<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>

<div id="fileupload">
    <form action="<?php echo site_url();?>productmodel/upload" method="POST" enctype="multipart/form-data">
        <div class="fileupload-buttonbar">
            <label class="fileinput-button">
                <span><?php echo $this->lang->line('add_file');?></span>
                <input type="file" name="userfile" multiple />
            </label>
            <!--<button type="button" class="start">Start upload</button>
            <button type="reset" class="cancel">Cancel upload</button>
            <button type="button" class="delete">Delete files</button>-->
        </div>
    </form>
    <div class="fileupload-content">
        <table class="files"></table>
        <div class="fileupload-progressbar"></div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.iframe-transport.js"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.fileupload.js"></script>
<script>
$(function () {
    'use strict';
    $('#fileupload').fileupload({
    drop: function (e, data) {
        $.each(data.files, function (index, file) {
            //alert('Dropped file: ' + file.name);
        });
    },
	change: function (e, data) {
		$.each(data.files, function (index, file) {
            $(".fileupload-content").append('Selected file: <div>' + file.name+'</div>');
        })
	},
	progress: function (ev, data) {
		showloading();
	},
	formData :[{name: 'model_id',value: '<?php echo $model_id;?>'}],
	done: function (e, data) {
		$(".fileupload-content").html('');
		var arr = eval("("+data.result+")");
		if(arr.error==false){
			$("#manual_id").val(arr.manual_id);
			$(".fileupload-content").html('Uploaded successfully');
		}else{
			$(".fileupload-content").html(arr.error);
		}
		//$(document).trigger('close.facebox');
		hideloading();
	}
});
    // Load existing files:
    /*$.getJSON($('#fileupload form').prop('action'), function (files) {
        var fu = $('#fileupload').data('fileupload');
        fu._adjustMaxNumberOfFiles(-files.length);
        fu._renderDownload(files)
            .appendTo($('#fileupload .files'))
            .fadeIn(function () {
                // Fix for IE7 and lower:
                $(this).show();
            });
    });*/
    $('#fileupload .files a:not([target^=_blank])').live('click', function (e) {
        e.preventDefault();
        $('<iframe style="display:none;"></iframe>')
            .prop('src', this.href)
            .appendTo('body');
    });
});
</script>
