<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<style type="text/css">
.datepicker {
	margin: 5px 5px 0 0;
}

#engineer,#cstatuslist,#scenter {
	width: 215px;
}

input[type="checkbox"] {
	vertical-align: middle !important;
}

.chbx {
	line-height: 10px;
}
</style>
<div class="toolbar1" style="width: 98%;">
<span class="message"><span class="message_text"></span></span>
<form onsubmit="return false" id="fname" name="fname" method="post">
<table cellspacing="0" cellpadding="0" width="100%">
	<col width="10%" />
	<col width="40%" />
	<col width="10%" />
	<col width="40%" />
	<tbody style="width: 80%">
		<tr>
			<th><label><?php echo $this->lang->line('call_id');?></label></th>
			<td><input type="text" class="text-input"
				value="<?php echo $this->input->get('cid');?>" name="call_uid"
				id="call_uid"
				onkeydown="Javascript: if (event.keyCode==13) javascript:generateCallReport();" /></td>
			<th><label><?php echo $this->lang->line('name');?></label></th>
			<td><input type="text" class="text-input"
				value="<?php echo $this->input->get('cn');?>" name="cust_name"
				id="cust_name"
				onkeydown="Javascript: if (event.keyCode==13) javascript:generateCallReport();" /></td>
		</tr>
		<tr>
			<th><label><?php echo $this->lang->line('phone');?></label></th>
			<td><input type="text" class="text-input"
				value="<?php echo $this->input->get('ph');?>" name="phone"
				id="phone"
				onkeydown="Javascript: if (event.keyCode==13) javascript:generateCallReport();" /></td>
			<th><label><?php echo $this->lang->line('serialno.');?></label></th>
			<td><input type="text" class="text-input"
				value="<?php echo $this->input->get('sn');?>" name="serial_number"
				id="serial_number"
				onkeydown="Javascript: if (event.keyCode==13) javascript:generateCallReport();" /></td>
		</tr>
		<tr>
			<th><label><?php echo $this->lang->line('date_from');?></label></th>
			<td><input type="text" name="from_date" id="from_date"
				class="datepicker text-input"
				value="<?php echo $this->input->get('from');?>"
				onkeydown="Javascript: if (event.keyCode==13) javascript:generateCallReport();" /></td>
			<th><label><?php echo $this->lang->line('date_to');?></label></th>
			<td><input type="text" name="to_date" id="to_date"
				class="datepicker text-input"
				value="<?php echo $this->input->get('to');?>"
				onkeydown="Javascript: if (event.keyCode==13) javascript:generateCallReport();" /></td>
		</tr>
		<tr>
			<th style="vertical-align: middle;"><label><?php echo $this->lang->line('status');?></label></th>
			<td><?php
			//echo $call_status;
			$status_ids = '0,1,2,3,4,5';
			$arr_status = explode(",",$status_ids);
			$cs = false;
			$cstatuslist=$this->mdl_mcb_data->getStatusOptions('callstatus');
			foreach($cstatuslist as $chk){
				$cs = (in_array($chk->value,$arr_status))?TRUE:FALSE;
				echo form_checkbox('cstatuslist[]', $chk->value, $cs,'class="status"').'<span class="chbx">'.$chk->text.'&nbsp;&nbsp;</span>';
			}
			?></td>
			<th><label><?php echo $this->lang->line('engineer');?></label></th>
			<td><?php echo $engineer_select;?></td>
		</tr>
		<tr>
			<th></th>
			<td></td>
			<td><label><?php echo $this->lang->line('service_center');?></label></td>
			<td><?php echo $scenters;?></td>
		</tr>
	</tbody>
	<tr>
		<th><label><?php echo $this->lang->line('brands');?></label></th>
		<td><?php echo $brand_select;?></td>
		<th><label><?php echo $this->lang->line('products');?></label></th>
		<td><span id="product_box"><?php echo $product_select;?></span></td>
	</tr>
	<tfoot style="width: 100%">
		<tr>
			<td style="text-align: right !important;" colspan="4"><input
				type="button" name="search"
				onclick="javascript:generateCallReport();$('#currentpage').val(0);"
				value="Generate" class="button" /></td>
		</tr>
	</tfoot>
</table>
<input type="hidden" name="currentpage" id="currentpage" value="0" /></form>
</div>
<span class="loading" style="display: none"><?php echo icon("loading","Loading","gif","icon");?></span>
<div id="call_list"></div>
