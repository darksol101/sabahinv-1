<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div class="toolbar1">
<form onsubmit="return false;">
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><label>Search</label></td>
		<td><input type="text" name="searchtxt" id="searchtxt" value=""
			class="text-input"
			onKeydown="Javascript: if (event.keyCode==13) showReminderList();" /><img
			src="<?php echo base_url();?>assets/style/img/icons/search.gif"
			class="searchbtn" onclick="showReminderList();" /><span
			class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
		<td style="text-align: center;"><span class="message"><span
			class="message_text"></span></span></td>
	</tr>
</table>
<input type="hidden" name="currentpage" id="currentpage" value="0" /> <input
	type="hidden" name="hdncallid" id="hdncallid" value="" /></form>
</div>
<div id="reminderlist"
	style="width: 100%;"></div>
