<style type="text/css">
.tool-icon a {
	padding:5px;
}
#main-content .tblbox table.tbl tfoot tr td{ border-top: 1px solid #00689C;}
</style>
<table width="100%" cellpadding="0" cellspacing="0" class="tbl">
    <col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col />
    <thead>
      <tr class="tblboxhead">
        <th colspan="13"><?php echo sprintf($this->lang->line('daily_service_report'),$reports['service_center_name'],format_date(strtotime(date('Y-m-d'))));?></th>
        <th style="text-align:right;" colspan="2"> <div class="tool-icon"> <a class="btn" onclick="export_exl();" title="Download as excel"><?php echo icon('excel-download','Download as excel','png');?></a><a class="btn" onclick="email_pop();" title="E-mail"><?php echo icon('mail','E-mail','png');?></a> </div>
        </th>
      </tr>
    </thead>
</table>
  <div class="tblbox">
  <table width="100%" cellpadding="0" cellspacing="0" class="tbl tblgrid" id="tbldata">
    <col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col />
    <thead>
    	<tr>
        	<th style="text-align:left"><?php echo $this->lang->line('service_center');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('registered_calls');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('closed_calls');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('cancelled_calls');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('total_pending_calls');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('partpending_calls');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('otherpending_calls');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('calls_not_assigned');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('less_than_12hrs');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('between_12and24hrs');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('between_1and2');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('between_2and7');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('between_7and15');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('between_15and30');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('greater_than30');?></th>
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
            foreach($service_centers as $service_center){
				$trstyle=$i%2==0?" class='even' ": " class='odd' ";
				$service_center->total_call_registered = $this->mdl_dailyservicereport->getTotalRegisteredCalls($service_center->value);
				$total_calls_reg+=$service_center->total_call_registered;
				
				//get total part pending calls
				$service_center->total_part_pending = $this->mdl_dailyservicereport->getTotalPartpendingCallsByDate($service_center->value);
				$total_calls_partpending+=$service_center->total_part_pending;
				
				//get total closed calls
				$service_center->total_closed = $this->mdl_dailyservicereport->getTotalClosedCallsByDate($service_center->value);
				$total_calls_closed+=$service_center->total_closed;
				
				//get total cancelled calls
				$service_center->total_cancelled = $this->mdl_dailyservicereport->getTotalCancelledCallsByDate($service_center->value);
				$total_calls_cancelled+=$service_center->total_cancelled;
				
				//get total pending calls
				$service_center->total_pending_calls =  $this->mdl_dailyservicereport->getTotalPendingCallsByDate($service_center->value);
				$total_calls_pending+=$service_center->total_pending_calls;
				
				//get total other pending calls
				$service_center->total_other_pending = $this->mdl_dailyservicereport->getTotalOtherpendingCallsByDate($service_center->value);	
				$total_calls_otherpending+=$service_center->total_other_pending;
				//get total not assigned calls
				$service_center->total_not_assigned = $this->mdl_dailyservicereport->getTotalCallsNotAssignedByDate($service_center->value);
				$total_calls_not_assigned+=$service_center->total_not_assigned;
				
				//get pending calls less than 12 hours
				$service_center->total_pending_calls_less_than12hrs = $this->mdl_dailyservicereport->getTotalPendingCallsLess12Hrs($service_center->value);
				$total_calls_pending_less_than12hrs+=$service_center->total_pending_calls_less_than12hrs;
				
				//get pending calls between 12 and 24 hrs
				$service_center->total_pending_calls_between_12and24hrs = $this->mdl_dailyservicereport->getTotalPendingCallsBetween12and24hrs($service_center->value);
				$total_calls_pending_between_12and24hrs+=$service_center->total_pending_calls_between_12and24hrs;
				
				//get pendign calls between 1 and 2 days
				$service_center->total_pending_calls_between_1and2 = $this->mdl_dailyservicereport->getTotalPendingCallsBetween1and2($service_center->value);
				$total_calls_pending_between_1and2+=$service_center->total_pending_calls_between_1and2;
				//get pending calls between 2 and 7 days
				$service_center->total_pending_between_2and7 = $this->mdl_dailyservicereport->getTotalPendingCallsBetween2and7($service_center->value);
				$total_calls_pending_between_2and7+=$service_center->total_pending_between_2and7;
				
				//get pending calls between 7 and 15 days
				$service_center->total_pending_between_7and15 = $this->mdl_dailyservicereport->getTotalPendingCallsBetween7and15($service_center->value);
				$total_calls_pending_between_7and15+=$service_center->total_pending_between_7and15;
				
				//get pending calls between 15 and 30 days
				$service_center->total_pending_between_15and30 = $this->mdl_dailyservicereport->getTotalPendingCallsBetween15and30($service_center->value);
				$total_calls_pending_between_15and30+=$service_center->total_pending_between_15and30;
				
				//get pending calls greater than 30 days
				$service_center->total_pending_greater_than30 = $this->mdl_dailyservicereport->getTotalPendingCallsGreaterthan30($service_center->value);
				$total_calls_pending_greater_than30+=$service_center->total_pending_greater_than30;
				?>
            <tr<?php echo $trstyle;?>>
            	<td style="text-align:left"><?php echo $service_center->text;?></td>
                <td style="text-align:center"><?php echo $service_center->total_call_registered;?></td>
                <td style="text-align:center"><?php echo $service_center->total_closed;?></td>
                <td style="text-align:center"><?php echo $service_center->total_cancelled;?></td>
                <td style="text-align:center"><?php echo $service_center->total_pending_calls;?></td>
                <td style="text-align:center"><?php echo $service_center->total_part_pending;?></td>
                <td style="text-align:center"><?php echo $service_center->total_other_pending;?></td>
                <td style="text-align:center"><?php echo $service_center->total_not_assigned;?></td>
                <td style="text-align:center"><?php echo $service_center->total_pending_calls_less_than12hrs;?></td>
                <td style="text-align:center"><?php echo $service_center->total_pending_calls_between_12and24hrs;?></td>
                <td style="text-align:center"><?php echo $service_center->total_pending_calls_between_1and2;?></td>
                <td style="text-align:center"><?php echo $service_center->total_pending_between_2and7;?></td>
                <td style="text-align:center"><?php echo $service_center->total_pending_between_7and15;?></td>
                <td style="text-align:center"><?php echo $service_center->total_pending_between_15and30;?></td>
                <td style="text-align:center"><?php echo $service_center->total_pending_greater_than30;?></td>
            </tr>
			<?php $i++; }?>
            <tfoot>
            <tr>
            	<td style="text-align:right"><strong>Total</strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_reg;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_closed;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_cancelled;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_pending;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_partpending;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_otherpending;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_not_assigned;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_pending_less_than12hrs;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_pending_between_12and24hrs;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_pending_between_1and2;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_pending_between_2and7;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_pending_between_7and15;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_pending_between_15and30;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_calls_pending_greater_than30;?></strong></td>
            </tr>
            </tfoot>
    </tbody>
  </table>
  </div>
  <input type="hidden" name="json" id="json" value="" />
  <input type="hidden" name="report_dt" id="report_dt" value="<?php echo format_date(strtotime(date('Y-m-d')));?>" />
  <input type="hidden" name="service_center_name" id="service_center_name" value="<?php echo $reports['service_center_name'];?>" />