<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style>
#facebox .footer {
	visibility: visible;
}
</style>
<?php $this->load->view('reminders/script');?>
<script type="text/javascript">
$(document).ready(function(){
	$(".footer").show();
})
function addReminderForm(){
	var call_id = $('#hdncallid').val();
	$.facebox(function() { 
	  $.post('<?php echo site_url();?>reminders/addreminder', { ajaxaction: "getpartlist" ,call_id:call_id,unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
</script>
<div style="width: 700px;"><?php $this->load->view('reminders/addreminder');?>
<div class="toolbar1">

<form onsubmit="return false;">
<table width="100%">
	<col width="1%" />
	<col width="10%" />
	<col />
	<col />
	<tr>
		<td><label>Search</label></td>
		<td><input type="text" name="searchtxt" id="searchtxt" value=""
			class="text-input"
			onKeydown="Javascript: if (event.keyCode==13) showReminderList();" /></td>
		<td><img
			src="<?php echo base_url();?>assets/style/img/icons/search.gif"
			class="searchbtn" onclick="showReminderList();" /></td>
		<td><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
		<span class="message"><span class="message_text"></span></span></td>
	</tr>
</table>
<input type="hidden" name="currentpage" id="currentpage" value="0" /></form>
</div>
<div id="reminderlist" style="width: 100%;"></div>
</div>
