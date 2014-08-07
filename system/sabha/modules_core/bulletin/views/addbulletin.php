<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showbulletinlist();
	cancel();
	$("#frmbulletin").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){savebulletin();}}
	});
})
</script>

<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.timepicker.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<style>
table td img.ui-datepicker-trigger { padding-left:5px;}
form label{ padding:5px 0!important;}
</style>
<script type="text/javascript">
	$(function() {
		$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
		});
	});
</script>

<form onsubmit="return false" id="frmbulletin" name="frmbulletin">
  <table width="40%">
  <col width="30%"/><col />
    <tr>
	  <th><label><?php echo $this->lang->line('bulletin_board_name'); ?>: </label></th>
      <td><input type="text" id="bulletin_board_name" name="bulletin" class="validate[required] text-input" /></td>
    </tr>
    <tr>
	  <th><label><?php echo $this->lang->line('bulletin_board_desc'); ?>: </label></th>
      <td><textarea id="bulletin_board_desc" cols="10" rows="4" name="bulletin_board_desc"></textarea></td>
    </tr>
    <tr>
	  <th><label><?php echo $this->lang->line('bulletin_board_start_dt'); ?>: </label></th>
      <td><input type="text" readonly="readonly" name="bulletin_board_start_dt" id="bulletin_board_start_dt" class="text-input datepicker" value="<?php echo format_date(strtotime(date("Y-m-d")));?>" style="width:70%;" /></td>
    </tr>
    <tr>
	  <th><label><?php echo $this->lang->line('bulletin_board_end_dt'); ?>: </label></th>
      <td><input type="text" readonly="readonly" name="bulletin_board_end_dt" id="bulletin_board_end_dt" class="text-input datepicker" value="<?php echo format_date(strtotime(date("Y-m-d")));?>" style="width:70%;" /></td>
    </tr> 
	<tr>
		<th><label>Status</label></th>
	<td>
		<select id="bulletin_board_status" name="bulletin_board_status">
		<option value="1">Active</option>
		<option value="0">InActive</option>
		</select>
	</td>
	</tr>
   <tr>
      <td colspan="2"><input type="hidden" value="0" id="hdnbulletin_board_id" name="hdnbulletin_board_id" />
      	<input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" />
        <input class="button" type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" onclick="cancel()" />
        <input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close_group" class="button" onclick="closeform();" /></td>
    </tr>
  </table>
</form>