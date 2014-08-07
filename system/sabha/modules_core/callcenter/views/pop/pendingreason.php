<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
#facebox .footer {
	visibility: visible;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	callreasonpendinglist('<?php echo $this->input->post('call_id');?>');
});
function callreasonpendinglist(call_id){
	var currentpage = $("#currentpage").val();
	var params = 'call_id='+call_id+"&currentpage="+currentpage+"&unq"+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>callcenter/pop/getcallreasonpendinglist",
			data	:	params,
			success	:	function (data){
					$("#list_pending_reasons").html(data);
				}								
	});
}
</script>
<div style="width: 700px;" id="list_pending_reasons"></div>
<input type="hidden" name="currentpage" id="currentpage" value="0" />
