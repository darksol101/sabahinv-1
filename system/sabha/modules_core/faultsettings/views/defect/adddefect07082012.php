 <?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showdefectlist();
	cancel();
	$("#frmdefect").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){savedefect();}}
	});
})
</script>
<form onsubmit="return false" id="frmdefect" name="frmdefect">
  <table width="40%">
  <col width="30%"/><col />
	 <tr>
	  <th><label><?php echo $this->lang->line('defect_code'); ?>: </label></th>
      <td><input type="text" id="defect_code" name="defect_code" class="validate[required] text-input" /></td>
    </tr>
  	<tr>
	  <th><label><?php echo $this->lang->line('symptom_code'); ?>: </label></th>
	  <td><?php echo $symptom_select; ?></td>
    </tr>   
    <tr>
	  <th><label><?php echo $this->lang->line('defect_description'); ?>: </label></th>
      <td><textarea id="defect_description" cols="10" rows="4" name="defect_description"></textarea></td>
    </tr>
	<tr>
		<th><label>Status</label></th>
	<td><?php echo $defect_status;?></td>
	</tr>
   <tr>
      <td colspan="2"><input type="hidden" value="0" id="hdndefect_id" name="hdndefect_id" />
      	<input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
</form>