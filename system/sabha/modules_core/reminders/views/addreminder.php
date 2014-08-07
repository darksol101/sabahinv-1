<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script type="text/javascript">
$("#reminderForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ saveReminder();}}  
	});
</script>
<div>
<form method="post" name="reminderForm" id="reminderForm">
<table width="60%">
	<tr>
		<td><label><?php echo $this->lang->line('remarks'); ?>: </label></td>
		<td><textarea rows="4" cols="20" id="reminder_remarks"
			name="reminder_remarks" class="validate[required] text-input"></textarea></td>
		<td><input type="button" name="savereminder" id="savereminder"
			value="<?php echo $this->lang->line('add');?>" class="button"
			onclick="saveReminder();" /></td>
	</tr>
</table>
<input type="hidden" name="hdnreminderid" id="hdnreminderid" value="0" />
<div class="clear">&nbsp;</div>
</form>
</div>
