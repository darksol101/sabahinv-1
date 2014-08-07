<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showProductList();
	$("#productForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ saveProduct();}}  
	});
})
</script>
<form method="post" name="productForm" id="productForm" onsubmit="return false">
  <table width="75%" cellpadding="0" cellspacing="0">
  	<tr>
    	<td style="vertical-align:top">
        	<table>
                <tr>
                    <th><label><?php echo $this->lang->line('productname'); ?>: </label></th>
                    <td><input type="text" value="" id="product_name" name="product_name" class="validate[required] text-input" /></td>
                </tr>
                <tr>
                    <th> <label><?php echo $this->lang->line('brand');?>:</label></th>
                    <td><?php echo $brandlist;?></td>
                </tr>
                <tr>
                    <th> <label><?php echo $this->lang->line('category');?>:</label></th>
                    <td><?php echo $category_select;?></td>
                </tr>
            </table>
        </td>
        <td style="vertical-align:top;">
        	<table>
                <tr>
                    <th style="vertical-align:middle;"><label><?php echo $this->lang->line('description'); ?>: </label></th>
	                <td><textarea name="product_description" class="validate[required]"id="product_description" rows="2" cols="10" style="width:200px;"></textarea></td>
                </tr>
                <tr>
                    <th><label><?php echo $this->lang->line('status'); ?>: </label></th>
                    <td><?php echo $product_status;?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
      <td align="right" colspan="2">
          <input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
          <input type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" class="button" onclick="cancelProductform();" /></label>
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
        </td>
    </tr>
  </table>
  <input type="hidden" name="pcurrentpage" id="pcurrentpage" value="0" />
  <input type="hidden" name="hdnproductid" id="hdnproductid" value="0" />
  <div class="clear">&nbsp;</div>
</form>
