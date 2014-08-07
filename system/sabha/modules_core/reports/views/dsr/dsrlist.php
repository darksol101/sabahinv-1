<style type="text/css">
.tool-icon a {
	padding: 5px;
}

#main-content .tblbox table.tbl tfoot tr td {
	border-top: 1px solid #00689C;
}
</style>

<table width="100%" cellpadding="0" cellspacing="0" class="tbl">
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<thead>
		<tr class="tblboxhead">
			<th colspan="13"><?php echo sprintf($this->lang->line('daily_service_report'),'',format_date(strtotime(date('Y-m-d'))));?></th>
			<th style="text-align: right;" colspan="2">
			<div class="tool-icon"><a class="btn" onclick="export_exl();"
				title="Download as excel"><?php echo icon('excel-download','Download as excel','png');?></a><a
				class="btn" onclick="email_pop();" title="E-mail"><?php echo icon('mail','E-mail','png');?></a>
			</div>
			</th>
		</tr>
	</thead>
</table>
<div class="tblbox">
<table width="100%" cellpadding="0" cellspacing="0" class="tbl tblgrid"
	id="tbldata">
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<col />
	<thead>
		<tr>
			<th style="text-align: left"><?php echo $this->lang->line('service_center');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('registered_calls');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('closed_calls');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('cancelled_calls');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('total_pending_calls');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('partpending_calls');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('otherpending_calls');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('calls_not_assigned');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('less_than_12hrs');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('between_12and24hrs');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('between_1and2');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('between_2and7');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('between_7and15');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('between_15and30');?></th>
			<th style="text-align: center"><?php echo $this->lang->line('greater_than30');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	$total_calls_reg = 0;
	$total_calls_closed = 0;
	$total_calls_cancelled = 0;
	$total_calls_pending = 0;
	$total_calls_partpending = 0;
	$total_calls_otherpending = 0;
	$total_calls_not_assigned = 0;
	$total_calls_pending_less_than12hrs = 0;
	$total_calls_pending_between_12and24hrs = 0;
	$total_calls_pending_between_1and2 = 0;
	$total_calls_pending_between_2and7 = 0;
	$total_calls_pending_between_7and15 = 0;
	$total_calls_pending_between_15and30 = 0;
	$total_calls_pending_greater_than30 = 0;
	foreach($reports as $report){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		$total_calls_reg = $total_calls_reg + $report->OPENCALLS;
		$total_calls_closed = $total_calls_closed + $report->CLOSEDCALLS;
		$total_calls_cancelled = $total_calls_cancelled + $report->CANCELLEDCALLS;
		$total_calls_pending =$total_calls_pending + $report->TOTALPENDINGCALLS;
		$total_calls_partpending =$total_calls_partpending+$report->PARTPENDINGCALLS;
		$total_calls_otherpending=$total_calls_otherpending+$report->OTHERPENDINGCALLS;
		$total_calls_not_assigned = $total_calls_not_assigned+$report->CALLSNOTASSIGNED;
		$total_calls_pending_less_than12hrs = $total_calls_pending_less_than12hrs+$report->LESSTHAN12HRS;
		$total_calls_pending_between_12and24hrs= $total_calls_pending_between_12and24hrs+$report->C12TO24HRS;
		$total_calls_pending_between_1and2 = $total_calls_pending_between_1and2+$report->C1TO2DAYS;
		$total_calls_pending_between_2and7 = $total_calls_pending_between_2and7+$report->C2TO7DAYS;
		$total_calls_pending_between_7and15 = $total_calls_pending_between_7and15+$report->C7TO15DAYS;
		$total_calls_pending_between_15and30 =$total_calls_pending_between_15and30+$report->C15TO30DAYS;
		$total_calls_pending_greater_than30 = $total_calls_pending_greater_than30+$report->GREATERTHAN30DAYS;
		?>
		<tr <?php echo $trstyle;?>>
			<td style="text-align: left"><?php echo $report->sc_name;?></td>
			<td style="text-align: center"><?php echo $report->OPENCALLS;?></td>
			<td style="text-align: center"><?php echo $report->CLOSEDCALLS;?></td>
			<td style="text-align: center"><?php echo $report->CANCELLEDCALLS;?></td>
			<td style="text-align: center"><?php echo $report->TOTALPENDINGCALLS;?></td>
			<td style="text-align: center"><?php echo $report->PARTPENDINGCALLS;?></td>
			<td style="text-align: center"><?php echo $report->OTHERPENDINGCALLS;?></td>
			<td style="text-align: center"><?php echo $report->CALLSNOTASSIGNED;?></td>
			<td style="text-align: center"><?php echo $report->LESSTHAN12HRS;?></td>
			<td style="text-align: center"><?php echo $report->C12TO24HRS;?></td>
			<td style="text-align: center"><?php echo $report->C1TO2DAYS;?></td>
			<td style="text-align: center"><?php echo $report->C2TO7DAYS;?></td>
			<td style="text-align: center"><?php echo $report->C7TO15DAYS;?></td>
			<td style="text-align: center"><?php echo $report->C15TO30DAYS;?></td>
			<td style="text-align: center"><?php echo $report->GREATERTHAN30DAYS;?></td>
		</tr>
		<?php $i++; }?>
	</tbody>
	<tfoot>
		<tr>
			<td style="text-align: right"><strong>Total</strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_reg;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_closed;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_cancelled;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_pending;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_partpending;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_otherpending;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_not_assigned;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_pending_less_than12hrs;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_pending_between_12and24hrs;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_pending_between_1and2;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_pending_between_2and7;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_pending_between_7and15;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_pending_between_15and30;?></strong></td>
			<td style="text-align: center"><strong><?php echo $total_calls_pending_greater_than30;?></strong></td>
		</tr>
	</tfoot>
</table>
</div>
<input
	type="hidden" name="json" id="json" value="" />
<input
	type="hidden" name="report_dt" id="report_dt"
	value="<?php echo format_date(strtotime(date('Y-m-d')));?>" />
<input
	type="hidden" name="service_center_name" id="service_center_name"
	value="<?php // echo $reports['service_center_name'];?>" />
