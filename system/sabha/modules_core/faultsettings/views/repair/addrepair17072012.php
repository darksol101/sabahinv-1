<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showrepairlist();
	cancel();
	$("#frmrepair").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){saverepair();}}
	});
})
</script>
<form onsubmit="return false" id="frmrepair" name="frmrepair">
  <table width="40%">
  <col width="30%"/><col />
	 <tr>
	  <th><label><?php echo $this->lang->line('repair'); ?>: </label></th>
      <td><input type="text" id="repair_code" name="repair_code" class="validate[required] text-input" /></td>
    </tr>
    <tr>
	  <th><label><?php echo $this->lang->line('symptom'); ?>: </label></th>
	  <td><?php echo $symptom_select; ?></td>
    </tr> 
  	<tr>
	  <th><label><?php echo $this->lang->line('defect'); ?>: </label></th>
	  <td><span id="defect_select"><?php echo $defect_select; ?></span></td>
    </tr>  
    <tr>
	  <th><label><?php echo $this->lang->line('repair_description'); ?>: </label></th>
      <td><textarea id="repair_description" cols="10" rows="4" name="repair_description"></textarea></td>
    </tr>
	<tr>
		<th><label>Status</label></th>
	<td>
		<select id="repair_status" name="repair_status">
		<option value="1">Active</option>
		<option value="0">InActive</option>
		</select>
	</td>
	</tr>
   <tr>
      <td colspan="2"><input type="hidden" value="0" id="hdnrepair_id" name="hdnrepair_id" />
      	<input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
</form>