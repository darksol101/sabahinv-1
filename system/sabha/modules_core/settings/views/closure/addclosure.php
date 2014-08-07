<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showclosurelist();
	cancel();
	$("#frmclosure").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){saveclosure();}}
	});
})
</script>
<form onsubmit="return false" id="frmclosure" name="frmclosure">
  <table width="50%">
  <col width="30%" /><col />
    <tr>
	  <th><label><?php echo $this->lang->line('closure'); ?>: </label></th>
      <td><input type="text" id="closure" name="closure" class="validate[required] text-input" /></td>
    </tr>
   <tr>
      <td colspan="5"><input type="hidden" value="0" id="hdnclosure_id" name="hdnclosure_id" />
      	<input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
</form>
