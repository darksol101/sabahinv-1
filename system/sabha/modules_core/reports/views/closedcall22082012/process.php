<?php 
switch (trim($ajaxaction)){
	case 'getclosedcallreportslist':
		displayclosedcallreportsList($closedcallreports);
		break;
}
			
//for closedcallreports

function displayclosedcallreportsList($closedcallreports){
?>
<form onsubmit="return false">
<table width="55%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="70%" /><col width="10%" />
    <thead>
    	<tr>
    		<th colspan="2"><?php echo sprintf($this->lang->line('closed_call_report'),$service_center_name,$report_dt);?></th>
        </tr>
    </thead>
	<tr class="even">
		<td><label>Number of Closed Calls</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['total_closed_calls'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>Average Closing Time</label></td>
		<td style="text-align:center;"><label><?php if($closedcallreports['average_closing']==NULL){ echo "N/A";}  else{ echo $closedcallreports['average_closing'];}?></label></td>
	</tr>
	<tr class="even">
		<td><label>Closed calls < 2 days</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['closed_calls'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>Closed Calls between 2 and 7 days</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['closed_calls_between1'];?></label></td>
	</tr>

	<tr class="even">
		<td><label>Closed Calls between 7 and 15 days</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['closed_calls_between2'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>Closed Calls > 15 days</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['closed_calls_greater'];?></label></td>
	</tr>
</table>
<input type="hidden" name="total_closed_calls" id="total_closed_calls" value="<?php echo $closedcallreports['total_closed_calls'];?>" />
<input type="hidden" name="average_closing" id="average_closing" value="<?php echo $closedcallreports['average_closing'];?>" />
<input type="hidden" name="closed_calls" id="closed_calls" value="<?php echo $closedcallreports['closed_calls'];?>" />
<input type="hidden" name="closed_calls_between1" id="closed_calls_between1" value="<?php echo $closedcallreports['closed_calls_between1'];?>" />
<input type="hidden" name="closed_calls_between2" id="closed_calls_between2" value="<?php echo $closedcallreports['closed_calls_between2'];?>" />
<input type="hidden" name="closed_calls_greater" id="closed_calls_greater" value="<?php echo $closedcallreports['closed_calls_greater'];?>" />
</form>
<?php }?>


