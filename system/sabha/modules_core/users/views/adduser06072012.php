<script language="javascript">
$(document).ready(function(){
	cancel('<?php echo $userID;?>');
	showTable();
	var userid = $("#hdnuserid").val();
	$("#frmuser").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ save();}}  
	});
})
</script>

<form onsubmit="return false" id="frmuser">
  <table class="tbl" width="300px">
    <tr>
      <th> <label><?php echo $this->lang->line('username'); ?>: </label></th>
      <td><input type="text" value="" id="username" name="username" class="validate[required,length[0,100]] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('userid'); ?>: </label></th>
      <td><input type="text" value="<?php echo $userID;?>" id="userid" name="userid" readonly="readonly" class="text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('email'); ?>: </label></th>
      <td><input type="text" value="" id="email" name="email" class="validate[required,custom[email]] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('mobile_number'); ?>: </label></th>
      <td><input type="text" value="" id="mobile_number" name="mobile_number" class="validate[custom[integer]] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('password'); ?>: </label></th>
      <td><input type="password" value="" id="password" name="password" class="validate[required] text-input" /></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('repassword'); ?>: </label></th>
      <td><input type="password" value="" id="rpassword" name="rpassword" class="validate[required,equals[password]] text-input"/></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('status'); ?>: </label></th>
      <td><?php echo $status;?></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('usergroup'); ?>: </label></th>
      <td><?php echo $usergroup;?></td>
    </tr>
    <tr>
      <th> <label><?php echo $this->lang->line('service_center'); ?>: </label>
      </th>
      <td><?php echo $scenters;?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel('<?php echo $userID;?>')" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
  <input type="hidden" value="0" id="hdnuserid" name="hdnuserid" />
</form>
