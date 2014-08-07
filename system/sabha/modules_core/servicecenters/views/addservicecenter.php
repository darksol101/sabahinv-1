<script language="javascript">
$(document).ready(function(){
	cancel();
	showServiceCenterList();
	$("#frm_sc").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){saveServiceCenter();}}  
	});

  $("#sc_phone2,#sc_phone3,#sc_phone1,#sc_fax").bind({
    

    keydown: function () {
      var val = $(this).val();
      if(val =='0'){
        $(this).val('');
      }
    },

    keyup: function () {
       var val = $(this).val();
      if(val =='0'){
        $(this).val('');
      }
  
    },

    blur: function () {
    var val = $(this).val();
      if(val =='0'){
        $(this).val('');
      }
  
    
    }

  });
})
</script>
<form method="post" name="frm_sc" id="frm_sc" onSubmit="return false;">
  <table width="70%">
    <tr>
      <th><label><?php echo $this->lang->line('sc_name'); ?>: </label></th>
      <td><input type="text" value="" id="sc_name" name="sc_name" class="validate[required] text-input" /></td>
      <th><label>City</label></th>
      <td><?php echo $city_select;?></td>
	 </tr>
	 <tr>
      <th><label><?php echo $this->lang->line('sc_add'); ?>: </label></th>
      <td><input type="text" value="" id="sc_address" name="sc_address" class="validate[required] text-input"/></td>
	  <th><label><?php echo $this->lang->line('sc_phn1'); ?>: </label></th>
      <td><input type="text" value="" id="sc_phone1" name="sc_phone1" class="validate[custom[phone]] text-input"/></td>
    </tr>
	 <tr>
      <th><label><?php echo $this->lang->line('sc_phn2'); ?>: </label></th>
      <td><input type="text" value="" id="sc_phone2" name="sc_phone2" class="validate[custom[phone]] text-input"/></td>
	  <th><label><?php echo $this->lang->line('sc_phn3'); ?>: </label></th>
      <td><input type="text" cols="" id="sc_phone3" name="sc_phone3" class="validate[custom[phone]] text-input"/></td>
    </tr>
	 <tr>
      <th><label><?php echo $this->lang->line('sc_fax'); ?>: </label></th>
      <td><input type="text" value="" id="sc_fax" name="sc_fax" class="validate[custom[phone]] text-input"/></td>
	     <th><label><?php echo $this->lang->line('sc_email'); ?>: </label></th>
       <td><input type="text" cols="" id="sc_email" name="sc_email" class="validate[custom[email]] text-input"/></td>
       <th><label><?php echo $this->lang->line('sc_code'); ?>: </label></th>
       <td><input type="text" cols="" id="sc_code" name="sc_code" class="validate[required,custom[onlyLetterSp]] text-input" maxlength="3"/></td>
    </tr>

    <tr>
      <td></td>
      <td><input type="hidden" value="0" id="hdnsc_id" name="hdnsc_id" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit"/>
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td></td>
    </tr>
  </table>
  <input type="hidden" name="hdnsc_id" id="hdnsc_id" value="0" />
  <div class="clear">&nbsp;</div>
</form>
