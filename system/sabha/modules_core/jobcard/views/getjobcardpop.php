<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style>

#list{position:relative;}
#list .loading{position:absolute; left:0px; top:0; width:100%; height:30px; margin:0 auto; text-align:center;}
#city_select{ width:77%!important;}
.basic_button{margin:0; padding:0; font-size:9px;}
</style>
<script language="javascript">
$(document).ready(function(){
	getJobCardPreview();
});
function getJobCardPreview(){
	var call_id = new Array();
	$("input.chkb").each(function(index){
		if(this.checked){
			call_id[index] = this.value;
		}
	});
	$("#list").html('<?php echo icon('loading','loading','gif'); ?>');
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var params="call_id="+call_id+"&from="+from_date+"&to="+to_date+"&unq="+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>jobcard/getjobcardpreview",
			data	:	params,
			success	:	function (data){
				$("#list").html(data);
				}								
		});//end  ajax
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
</script>
<div id="list" style="width:1100px;" style="display:block;"></div>