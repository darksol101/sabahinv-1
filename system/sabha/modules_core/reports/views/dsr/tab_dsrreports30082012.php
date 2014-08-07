<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.timepicker.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<style>
table td img.ui-datepicker-trigger { padding-left:5px;}
form label{ padding:5px 0!important;}
#reportslist{ position:relative}
#reportslist .loading{position:absolute; left:0px; top:0; width:100%; height:30px; margin:0 auto; text-align:center;}
#sc_select{ height:95px!important;}
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
<span class="message"><span class="message_text"></span></span>
<form onsubmit="return false" id="fname" name="fname" method="post">
  <table width="45%">
  <col width="25%" /><col />
    <tr>
      <th><label><?php echo $this->lang->line('serivcecenter');?>: </label></th>
      <td><?php echo $servicecenter_select;?></td>
	 </tr>
   <tr>
      <td colspan="5">
        <input class="button" type="submit" value="<?php echo $this->lang->line('generate'); ?>" name="btn_submit" id="btn_submit" onClick="getreportslist();"/>&nbsp;<span id="loading"></span></td>
       </tr>
  </table>
<div id="reportslist" style="display:none;"></div>
</form>
