<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showwarrantylist();
	cancel();
	$("#frmwarranty").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){savewarranty();}}
	});
})
</script>
<form onsubmit="return false" id="frmwarranty" name="frmwarranty">
  <table width="50%">
  <col width="30%" /><col />
    <tr>
	  <th><label><?php echo $this->lang->line('warranty'); ?>: </label></th>
      <td><input type="text" id="warranty" name="warranty" class="validate[required] text-input" /></td>
    </tr>
   <tr>
      <td colspan="5"><input type="hidden" value="0" id="hdnwarranty_id" name="hdnwarranty_id" />
      	<input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
</form>
