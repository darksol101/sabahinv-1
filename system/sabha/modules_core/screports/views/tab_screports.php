<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.timepicker.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<style>
table td img.ui-datepicker-trigger { padding-left:5px;}
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
<form onsubmit="return false" id="frmcity" name="frmcity">
  <table width="60%">
  <col width="10%" /><col width="40%" /><col width="10%" /><col width="40%" />
    <tr>
      <th><label><?php echo $this->lang->line('from'); ?>: </label></th>
      <td><input type="text" readonly="readonly" name="from_date" id="from_date" class="text-input datepicker" value="<?php echo format_date(strtotime(date("Y-m-d")));?>" style="width:70%" /></td>
	  <th><label><?php echo $this->lang->line('to'); ?>: </label></th>
	  <td><input type="text" readonly="readonly" name="to_date" id="to_date" class="text-input datepicker" value="<?php echo format_date(strtotime(date("Y-m-d")));?>" style="width:70%"/></td>
	 </tr>
   <tr>
      <td colspan="5"><input type="hidden" value="0" id="hdncity_id" name="hdncity_id" />
      	<input type="hidden" name="currentpage" id="currentpage" value="0" />
        <input class="button" type="submit" value="<?php echo $this->lang->line('generate'); ?>" name="btn_submit" id="btn_submit" onClick="getscreportslist();"/>
       </tr>
  </table>
</form>

<div id="screportslist" style="display:none;"></div>