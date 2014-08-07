<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type = text/css>
.editlist{cursor:pointer;}
#fromdate,#todate{ width:50%!important;}
#searchtxt{ width:70%!important}
.toolbar1 table td img.ui-datepicker-trigger{ margin-left:7px!important;}
.toolbar1 table td{ padding: 0px 0px!important;}
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
<?php $this->load->view('dashboard/system_messages');?>
<div class="toolbar1">
<form onsubmit="return false">
<table style="width:100%" class="toolbox" cellpadding="0"
	cellspacing="0">
	<col width="20%" />
	<col width="15%" />
	<col width="15%" />
	<col width="15%" />
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
	<col width="1%" /><col />
    <tr>
		<td><?php echo $this->lang->line('search');?><input type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) {$('#currentpage').val(0);showPurchaseList();}" /></td>
        <td><?php echo $this->lang->line('from');?>
        <input readonly="readonly" type="text" name="fromdate" id="fromdate" class="text-input datepicker" value=""/></td>
        <td><?php echo $this->lang->line('to');?>
        <input type="text" name="todate" id="todate" class="text-input datepicker" value=""/></td>
        <td><?php echo $servicecenters;?> </td>
        <!-- <td><?php echo $bill_type;?> </td> -->
        <td><?php echo $status_select;?></td>
		<td><img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showBills();" /></td>
		<td><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span><span class="message"><span class="message_text"></span></span></td>
	</tr>
</table>
</form>
</div>
<div id="billslist" style="display:none"></div>
<input type="hidden" name="currentpage" id="currentpage" value="0" />
