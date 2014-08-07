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
<style type="text/css">
#product_box_search,#select_product_box,#select_symptom_box,#select_defect_box{position:relative;}
#product_box_search .loading,#select_product_box .loading,#select_symptom_box .loading,#select_defect_box .loading{position:absolute; left:0px; top:0; width:100%; height:30px; margin:0 auto; text-align:center;}
</style>
<form onsubmit="return false" id="frmrepair" name="frmrepair">
  <table width="40%">
  <col width="30%"/><col />
	 <tr>
	  <th><label><?php echo $this->lang->line('repair'); ?>: </label></th>
      <td><input type="text" id="repair_code" name="repair_code" class="validate[required] text-input" /></td>
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
	  <th><label><?php echo $this->lang->line('symptom'); ?>: </label></th>
	  <td><span id="select_symptom_box"><?php echo $symptom_select; ?></span></td>
    </tr> 
  	<tr>
	  <th><label><?php echo $this->lang->line('defect'); ?>: </label></th>
	  <td><span id="select_defect_box"><?php echo $defect_select; ?></span></td>
    </tr>  
    <tr>
	  <th><label><?php echo $this->lang->line('repair_description'); ?>: </label></th>
      <td><textarea id="repair_description" cols="10" rows="4" name="repair_description"></textarea></td>
    </tr>
	<tr>
		<th><label>Status</label></th>
		<td><?php echo $repair_status;?></td>
	</tr>
   <tr>
      <td colspan="2">
      	<input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
  <input type="hidden" value="0" id="hdnrepair_id" name="hdnrepair_id" />
  <input  type="hidden" name="sort_field" id="sort_field" value="repair_code" />
  <input type="hidden" name="sort_type" id="sort_type" value="ASC" />
</form>