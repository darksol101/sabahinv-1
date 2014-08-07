<script language="javascript">
$(document).ready(function(){
	cancel();
	showGroupTable();
	var groupid = $("#hdngroupid").val();
	$("#frmusergroup").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){savegroup(groupid);}}  
	});
})
</script>

<form onsubmit="return false" id="frmusergroup">
  <table width="40%">
    <tr>
      <th><label><?php echo $this->lang->line('groupname'); ?>: </label></th>
      <td><input type="text" value="" id="groupname" name="groupname" class="validate[required,length[0,100]] text-input"/></td>
    </tr>
    <tr>
      <th><label><?php echo $this->lang->line('description'); ?>: </label></th>
      <td><textarea id="description" name="description"></textarea></td>
    </tr>
    <tr>
      <th><label><?php echo $this->lang->line('status'); ?>: </label></th>
      <td><?php echo $status;?></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="hidden" value="0" id="hdngroupid" name="hdngroupid" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td></td>
    </tr>
  </table>
</form>
