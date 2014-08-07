 <script language='javascript'>
$(document).ready(function(){
	showpartbinlist();
	$("#addbin").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status == true){ savebin();}}  
	});
})
</script>

<form  onSubmit="return false" id="addbin" name="addbin">
  <table width="35%" cellpadding="0" cellspacing="0">
  <col width="35%" /><col  />
    <tr>
      <td><label> <?php echo $this->lang->line('service_center')?></label></td>
      <td><?php echo $servicecenters;?></td>
    </tr>
    <tr>
      <td><label> <?php echo $this->lang->line('bin_name')?></label></td>
      <td><input type="text" name="name" id="name" value="" class="validate[required] text-input"></td>
    </tr>
    <tr>
      <td><label><?php echo $this->lang->line('bin_description')?> </label></td>
      <td><input type="text" name="desc" id="desc" value="" class="validate[required] text-input"></td>
    </tr>
    <tr>
    <td></td>
      <td><input type="hidden" value="0" id="partbin_id" name="partbin_id" />
        <input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit"	value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button"	value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancelform()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
        </td>
    </tr>
  </table>
</form>
