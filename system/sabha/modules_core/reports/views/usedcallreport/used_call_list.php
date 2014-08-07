<style type="text/css">
#fromdate,#todate{ width:50%!important;}
#searchtxt{ width:85%!important}
</style>
<style>
.datecheck{
	border:red
	}
.loading {display:none}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/validationEngine.jquery.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.validationEngine.js" type="text/javascript"></script>

<script>
	$(function() {
		$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>',
			
		});
	});
</script>
<div class="toolbar1">
<form onsubmit="return false" id="frmusedcall" name="frmusedcall">
<table width="100%">
	<col width="12%" />
	<col width="22%" />
	<col width="4%" />
	<col width="22%" />
	<col width="22%" />
	<col width="22%" />
    <col width="5%" />
    <col width="5%" />
    
	<tr>
		<th><label><?php echo $this->lang->line('serivcecenter');?>: </label></th>
		<td><?php echo $servicecenters_search; ?></td>
		<th><label><?php echo $this->lang->line('engineer');?></label></th>
		<td><?php echo $engineerOption;?></td>
        <td>From <span style="color:red
        ";>*</span> : <input id="fromdate" readonly="readonly" name="fromdate" class="validate[required] text-input datepicker" type="text" value="<?php echo date('d/m/Y');?>"></td>
        <td> To <span style="color:red
        ";>*</span> : <input id="todate" readonly="readonly" name="todate" value="<?php echo date('d/m/Y');?>" class="validate[required] datepicker text-input" type="text"></td>
        <td><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
        <td align="right">
       <input class="button" type="submit"
			value="<?php echo $this->lang->line('generate_usedcallreport'); ?>"
			name="btn_submit" id="btn_submit"
			title="Generate Used Call Report" onclick="showusedcalllist();" /></td>
		</tr>
	
	
</table>
</form>
</div>


<div id="usedcalllist"></div>


