<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.timepicker.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<style>
table td img.ui-datepicker-trigger {
	padding-left:5px;
}
form label {
	padding:5px 0!important;
}
#reminderreportlist {
	position:relative
}
#reminderreportlist .loading {
	position:absolute;
	left:0px;
	top:0;
	width:100%;
	height:30px;
	margin:0 auto;
	text-align:center;
}
#product_box{position:relative;}
#product_box .loading{position:absolute; left:0px; top:0; width:100%; height:30px; margin:0 auto; text-align:center;}
#main-content frmcallclosed table td, #main-content frmcallclosed table th { padding: 2px 0px!important;}
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
<div class="toolbar1">
  <form onsubmit="return false" id="frmreminder" name="frmreminder">
    <table width="100%">
    <col width="9%" /><col width="15%" /><col width="7%" /><col width="15%" /><col width="5%" /><col /><col width="7%" /><col width="15%" />
      <tr>
		<th><label><?php echo $this->lang->line('call_id');?>:</label></th>
        <td><input type="text" class="text-input" style = "width: 75%" "value="<?php echo $this->input->get('cid');?>" name="call_uid" id="call_uid" onkeydown="Javascript: if (event.keyCode==13) javascript:getreminderlist();" /></td>
        <th><label><?php echo $this->lang->line('servicecenter');?>: </label></th>
        <td><?php echo $servicecenter_select; ?></td>
        <th><label><?php echo $this->lang->line('brand');?>:</label></th>
        <td><?php echo $brand_select;?></td>
        <th><label><?php echo $this->lang->line('product');?>:</label></th>
        <td><span id="product_box"><?php echo $product_select;?></span></td>
      </tr>
      <tr>
        <th><label><?php echo $this->lang->line('from'); ?>: </label></th>
        <td><input type="text" readonly="readonly" name="from_date" id="from_date" class="text-input datepicker" value="<?php echo format_date(strtotime(date("Y-m-d")));?>" style="width:70%;" /></td>
        <th><label><?php echo $this->lang->line('to'); ?>: </label></th>
        <td><input type="text" readonly="readonly" name="to_date" id="to_date" class="text-input datepicker" value="<?php echo format_date(strtotime(date("Y-m-d")));?>" style="width:70%;" /></td>
      </tr>
      <tr>
        <td colspan="1"><input type="hidden" value="0" id="hdncity_id" name="hdncity_id" />
          <input type="hidden" name="currentpage" id="currentpage" value="0" />
          <input class="button" type="submit" value="<?php echo $this->lang->line('generate'); ?>" name="btn_submit" id="btn_submit" onClick="javascript:$('#currentpage').val(0);getreminderlist();"/></td>
          <td colspan="5"><span class="message"><span class="message_text"></span></span></td>
      </tr>
    </table>
  </form>
</div>
<div id="reminderreportlist"></div>
