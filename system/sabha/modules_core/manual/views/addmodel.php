<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<?php $this->load->view('script');?>
<script language="javascript">
$(document).ready(function(){
	showProductmodelList();
	$("#modelForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ saveProductModel();}}  
	});
	$("#brand_select").change(function(){
		checkModelNumber($("#modelnumber").val());					   
	});
	$("#product_select").change(function(){
		checkModelNumber($("#modelnumber").val());   
	});
	
})
</script>
<form method="post" name="modelForm" id="modelForm" onsubmit="return false" enctype="multipart/form-data">
  <table class="tblForm" width="75%">
  	<tr>
    	<td style="vertical-align:top;">
        	<table>
                <tr>
                    <th> <label><?php echo $this->lang->line('brand');?>:</label></th>
                    <td><?php echo $brandlist;?></td>
                </tr>
                <tr>
                    <th><label><?php echo $this->lang->line('products'); ?>: </label></th>
                    <td><span id="select_product_box"><?php echo $productlist;?></span></td>
                </tr>
                <tr>
                    <th><label><?php echo $this->lang->line('modelnumber'); ?>: </label></th>
                    <td><input onKeydown="javascript: if (event.keyCode==13)checkModelNumber(this.value); " type="text" value="" id="modelnumber" name="modelnumber" class="validate[required] text-input" onfocus="checkModelNumber(this.value);" onblur="checkModelNumber(this.value);" /><span id="msg_check"></span></td>
                </tr>
                <tr>
                	<td style="vertical-align:top;"><label>Documents</label></td>
                    <td><a rel="modal" class="upload-file" href="<?php echo site_url();?>productmodel/uploadform"></a></td>
                </tr>
            </table>
        </td>
        <td style="vertical-align:top;">
        	<table>
                <tr>
                    <th style="vertical-align:middle;"><label><?php echo $this->lang->line('description'); ?>: </label></th>
                    <td><textarea name="model_desc" id="model_desc" rows="2" cols="10" style="width:200px;"></textarea></td>
                </tr>
                <tr>
                    <th><label><?php echo $this->lang->line('status'); ?>: </label></th>
                    <td><?php echo $model_status;?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
      <td align="right" colspan="2">
          <input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
          <input type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" class="button" onclick="ClearModelForm();" /></label>
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
        </td>
    </tr>
  </table>
  <input type="hidden" name="productpage" id="productpage" value="1" />
  <input type="hidden" name="currentpage" id="currentpage" value="0"  />
  <input type="hidden" name="hdnmodelid" id="hdnmodelid" value="0" />
   <input type="hidden" name="manual_id" id="manual_id" value="0" />
  <div class="clear">&nbsp;</div>
</form>

