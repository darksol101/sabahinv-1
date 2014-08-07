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
<style type="text/css">
#select_product_box,#product_box_search,#symptom_box_search{position:relative;}
#select_product_box .loading,#product_box_search .loading,#symptom_box_search .loading{position:absolute; left:0px; top:0; width:100%; height:30px; margin:0 auto; text-align:center;}
</style>

<form onsubmit="return false" id="frmdefect" name="frmdefect">
  <table width="40%">
  <col width="30%"/><col />
	 <tr>
	  <th><label><?php echo $this->lang->line('defect_code'); ?>: </label></th>
      <td><input type="text" id="defect_code" name="defect_code" class="validate[required] text-input" /></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('brands');?></label></th>
        <td><?php echo $brand_select;?></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('products');?></label></th>
        <td><span id="select_product_box"><?php echo $product_select;?></span></td>
    </tr>
  	<tr>
	  <th><label><?php echo $this->lang->line('symptom_code'); ?>: </label></th>
	  <td><span id="select_symptom_box"><?php echo $symptom_select; ?></span></td>
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