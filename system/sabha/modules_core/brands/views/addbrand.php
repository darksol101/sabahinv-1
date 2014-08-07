<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script>
$(document).ready(function(){
	showBrandList();
});
</script>
<form method="post" name="brandForm" id="brandForm">
  <table width="40%">
    <tr>
      <th><label><?php echo $this->lang->line('brandname'); ?>: </label></th>
      <td><input type="text" value="" id="brandname" name="brandname" class="validate[required] text-input" /></td>
    </tr>
    <tr>
      <th><label><?php echo $this->lang->line('description'); ?>: </label>
      </th>
      <td><textarea name="description" id="description" rows="4" cols="10"></textarea></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('status');?></label></th>
        <td><?php echo $brand_status;?></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    	<td align="right"><label>
    <input type="button" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" onclick="saveBrand();" /></label>
    	<input type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" class="button" onclick="cancelform();" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
  </label></td>
    </tr>
  </table>
  <input type="hidden" name="bcurrentpage" id="bcurrentpage" value="0" />
  <input type="hidden" name="hdnid" id="hdnid" value="0" />
  <div class="clear">&nbsp;</div>
</form>
