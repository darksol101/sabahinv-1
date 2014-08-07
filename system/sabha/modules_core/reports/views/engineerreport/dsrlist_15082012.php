<style type="text/css">
.tool-icon a {
	padding: 5px;
}
</style>
<form>
<table width="55%" cellpadding="0" cellspacing="0" class="tbl">
	<col />
	<col />
	<col />
	<col />
	<col />
	<thead>
		<tr class="tblboxhead">
			<th colspan="4"><?php echo sprintf($this->lang->line('daily_service_report'),$reports['service_center_name'],format_date(strtotime(date('Y-m-d'))));?></th>
			<th style="text-align: right;">
			<div class="tool-icon"><a class="btn" onclick="export_exl();"
				title="Download as excel"><?php echo icon('excel-download','Download as excel','png');?></a><a
				class="btn" onclick="email_pop();" title="E-mail"><?php echo icon('mail','E-mail','png');?></a>
			</div>
			</th>
		</tr>
	</thead>
	<tbody id="tblgrid">
		<tr class="odd">
			<td colspan="5" style="padding: 0">&nbsp;
			<div class="tblbox">
			<table class="tbl" cellpadding="0" cellspacing="0" width="100%">
				<tr class="even">
					<td colspan="4"><label><?php echo $this->lang->line('total_call_registered');?></label></td>
					<td style="text-align: right;"><label><?php echo $reports['total_call_registered'];?></label></td>
				</tr>
				<tr class="odd">
					<td colspan="4"><label><?php echo $this->lang->line('open_calls');?></label></td>
					<td style="text-align: right;"><label><?php echo $reports['total_open_calls'];?></label></td>
				</tr>
				<tr class="even">
					<td colspan="4"><label><?php echo $this->lang->line('cancelled_calls');?></label></td>
					<td style="text-align: right;"><label><?php echo $reports['total_cancelled'];?></label></td>
				</tr>
			</table>
			</div>
			</td>
		</tr>
		<tr class="">
			<td colspan="5" style="padding: 0">&nbsp;
			<div class="tblbox">
			<table class="tbl" cellpadding="0" cellspacing="0" width="100%">
				<tr class="even">
					<td colspan="4"><label><strong><?php echo $this->lang->line('pending_calls');?></strong></label></td>
					<td style="text-align: right;"><label><strong><?php echo $reports['total_pending_calls'];?></strong></label></td>
				</tr>
				<tr style="color: #FFF;">
					<th style="text-align: center"><?php echo $this->lang->line('less_than_2');?></th>
					<th style="text-align: center"><?php echo $this->lang->line('between_2and7');?></th>
					<th style="text-align: center"><?php echo $this->lang->line('between_7and15');?></th>
					<th style="text-align: center"><?php echo $this->lang->line('between_15and30');?></th>
					<th style="text-align: center"><?php echo $this->lang->line('greater_than30');?></th>
				</tr>
				<tr class="even">
					<td style="text-align: center"><?php echo $reports['total_pending_less_than_2'];?></td>
					<td style="text-align: center"><?php echo $reports['total_pending_between_2and7'];?></td>
					<td style="text-align: center"><?php echo $reports['total_pending_between_7and15'];?></td>
					<td style="text-align: center"><?php echo $reports['total_pending_between_15and30'];?></td>
					<td style="text-align: center"><?php echo $reports['total_pending_grater_than30'];?></td>
				</tr>
				<tr class="even">
					<td colspan="4"><label><?php echo $this->lang->line('pending_calls_average_time');?></label></td>
					<td style="text-align: center;"><label> <?php if($reports['average_aging_current']==NULL){ echo "N/A";}  else{ echo $reports['average_aging_current'];}?>
					</label></td>
				</tr>
			</table>
			</div>
			</td>
		</tr>
		<tr class="odd">
			<td colspan="5" style="padding: 0">&nbsp;
			<div class="tblbox">
			<table class="tbl" cellpadding="0" cellspacing="0" width="100%">
				<tr class="even">
					<td colspan="4"><label><strong><?php echo $this->lang->line('partpending_calls');?></strong></label></td>
					<td style="text-align: right;"><label><strong><?php echo $reports['total_part_pending'];?></strong></label></td>
				</tr>
				<tr class=""
					style="background: none repeat scroll 0 0 #F2F9FC !important; border-bottom: 1px solid #00689 !important">
					<th style="text-align: center;"><?php echo $this->lang->line('less_than_2');?></th>
					<th style="text-align: center"><?php echo $this->lang->line('between_2and7');?></th>
					<th style="text-align: center"><?php echo $this->lang->line('between_7and15');?></th>
					<th style="text-align: center"><?php echo $this->lang->line('between_15and30');?></th>
					<th style="text-align: center"><?php echo $this->lang->line('greater_than30');?></th>
				</tr>
				<tr class="even">
					<td style="text-align: center"><?php echo $reports['total_partpending_less_than_2'];?></td>
					<td style="text-align: center"><?php echo $reports['total_partpending_between_2and7'];?></td>
					<td style="text-align: center"><?php echo $reports['total_partpending_between_7and15'];?></td>
					<td style="text-align: center"><?php echo $reports['total_partpending_between_15and30'];?></td>
					<td style="text-align: center"><?php echo $reports['total_partpending_grater_than30'];?></td>
				</tr>
				<tr class="even">
					<td colspan="4"><label><?php echo $this->lang->line('partpending_calls_average_time');?></label></td>
					<td style="text-align: center;"><label> <?php if($reports['average_aging_part']==NULL){ echo "N/A";}  else{?>
					<?php echo $reports['average_aging_part'];}?></label></td>
				</tr>
			</table>
			</div>
			</td>
		</tr>
	</tbody>
</table>
<input type="hidden" name="service_center_name" id="service_center_name"
	value="<?php echo $reports['service_center_name'];?>" /> <input
	type="hidden" name="report_dt" id="report_dt"
	value="<?php echo format_date(strtotime(date('Y-m-d')));?>" /> <input
	type="hidden" name="total_call_registered" id="total_call_registered"
	value="<?php echo $reports['total_call_registered'];?>" /> <input
	type="hidden" name="total_open_calls" id="total_open_calls"
	value="<?php echo $reports['total_open_calls'];?>" /> <input
	type="hidden" name="total_pending_calls" id="total_pending_calls"
	value="<?php echo $reports['total_pending_calls'];?>" /> <input
	type="hidden" name="total_part_pending" id="total_part_pending"
	value="<?php echo $reports['total_part_pending'];?>" /> <input
	type="hidden" name="total_cancelled" id="total_cancelled"
	value="<?php echo $reports['total_cancelled'];?>" /> <input
	type="hidden" name="total_pending_less_than_2"
	id="total_pending_less_than_2"
	value="<?php echo $reports['total_pending_less_than_2'];?>" /> <input
	type="hidden" name="total_pending_between_2and7"
	id="total_pending_between_2and7"
	value="<?php echo $reports['total_pending_between_2and7'];?>" /> <input
	type="hidden" name="total_pending_between_7and15"
	id="total_pending_between_7and15"
	value="<?php echo $reports['total_pending_between_7and15'];?>" /> <input
	type="hidden" name="total_pending_between_15and30"
	id="total_pending_between_15and30"
	value="<?php echo $reports['total_pending_between_15and30'];?>" /> <input
	type="hidden" name="total_pending_grater_than30"
	id="total_pending_grater_than30"
	value="<?php echo $reports['total_pending_grater_than30'];?>" /> <input
	type="hidden" name="total_partpending_less_than_2"
	id="total_partpending_less_than_2"
	value="<?php echo $reports['total_partpending_less_than_2'];?>" /> <input
	type="hidden" name="total_partpending_between_2and7"
	id="total_partpending_between_2and7"
	value="<?php echo $reports['total_partpending_between_2and7'];?>" /> <input
	type="hidden" name="total_partpending_between_7and15"
	id="total_partpending_between_7and15"
	value="<?php echo $reports['total_partpending_between_7and15'];?>" /> <input
	type="hidden" name="total_partpending_between_15and30"
	id="total_partpending_between_15and30"
	value="<?php echo $reports['total_partpending_between_15and30'];?>" />
<input type="hidden" name="total_partpending_grater_than30"
	id="total_partpending_grater_than30"
	value="<?php echo $reports['total_partpending_grater_than30'];?>" /> <input
	type="hidden" name="average_aging_current" id="average_aging_current"
	value="<?php echo $reports['average_aging_current'];?>" /> <input
	type="hidden" name="average_aging_part" id="average_aging_part"
	value="<?php echo $reports['average_aging_part'];?>" /></form>
