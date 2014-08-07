<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script>
$(document).ready(function(){
	showCategoryList();
	$("#categoryForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ saveCategory();}}  
	});
});
</script>
<form method="post" name="categoryForm" id="categoryForm" onsubmit="return false;">
  <table width="40%">
    <tr>
      <th><label><?php echo $this->lang->line('categoryname'); ?>: </label></th>
      <td><input type="text" value="" id="categoryname" name="categoryname" class="validate[required] text-input" /></td>
    </tr>
    <tr>
      <th><label><?php echo $this->lang->line('description'); ?>: </label>
      </th>
      <td><textarea name="category_desc" id="category_desc" rows="4" cols="10"></textarea></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('status');?></label></th>
        <td><?php echo $category_status;?></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    	<td align="right">
    <input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
    	<input type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" class="button" onclick="cancelform();" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
  <input type="hidden" name="bcurrentpage" id="bcurrentpage" value="0" />
  <input type="hidden" name="ccurrentpage" id="ccurrentpage" value="0" />
  <input type="hidden" name="hdncid" id="hdncid" value="0" />
  <div class="clear">&nbsp;</div>
</form>
