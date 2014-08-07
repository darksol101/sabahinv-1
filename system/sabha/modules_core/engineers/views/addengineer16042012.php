<script language="javascript">
$(document).ready(function(){
	cancel();
	showEngineerTable();
	var engineerid = $("#hdnengineerid").val();
	$("#frmengineers").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){saveengineer(engineerid);}}  
	});
})
</script>
<form onsubmit="return false" id="frmengineers">
  <table width="40%">
    <tr>
      <th><label><?php echo $this->lang->line('engineername'); ?>: </label></th>
      <td><input type="text" value="" id="engineer_name" name="engineer_name" class="validate[required,length[0,100]] text-input"/></td>
    </tr>
    <tr>
      <th><label><?php echo $this->lang->line('description'); ?>: </label></th>
      <td><textarea id="engineer_desc" name="engineer_desc"></textarea></td>
    </tr>
    <tr>
      <th><label><?php echo $this->lang->line('status'); ?>: </label></th>
      <td><?php echo $status;?></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="hidden" value="0" id="hdnengineer_id" name="hdnengineer_id" />
      <input type="hidden" value="0" id="currentpage" name="currentpage" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit"/>
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td></td>
    </tr>
  </table>
</form>
