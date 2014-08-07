<script language='javascript'>
$(document).ready(function(){
	showVendorList();
	$("#addVendor").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ saveVendor();}}  
	});
})
</script>

<form  onSubmit="return false" id="addVendor" name="addVendor">
  <table width="35%" cellpadding="0" cellspacing="0">
  <col width="35%" /><col  />
    <tr>
      <td><label> <?php echo $this->lang->line('Vendors name')?></label></td>
      <td><input type="text" name="name" id="name" value="" class="validate[required] text-input"></td>
    </tr>
    <tr>
      <td><label><?php echo $this->lang->line('Vendors address')?> </label></td>
      <td><input type="text" name="address" id="address" value="" class="validate[required] text-input"></td>
    </tr>
    <tr>
      <td><label> <?php echo $this->lang->line('phone')?></label></td>
      <td><input type="text" name="phone" id="phone" value="" class="validate[required] text-input" ></td>
    </tr>
    <tr>
      <td><label> <?php echo $this->lang->line('Contact')?></label></td>
      <td><input type="text" name="contact" id="contact" value="" class="text-input"></td>
    </tr>
    <tr>
    <td></td>
      <td><input type="hidden" value="0" id="vendor_id" name="vendor_id" />
        <input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit"	value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button"	value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancelform()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
        </td>
    </tr>
  </table>
</form>
