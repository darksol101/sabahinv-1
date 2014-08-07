<?php 
switch (trim($ajaxaction)){
	case 'getreportslist':
		displayReportsList($reports);
		break;
}
			
//for reports

function displayreportsList($reports){
?>
<form onsubmit="return false">
<table width="55%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="70%" /><col width="10%" />
    <thead>
    	<tr>
    		<th>&nbsp;</th><th>&nbsp;</th>
        </tr>
    </thead>
	<tr class="odd">
		<td><label>Total Call Registered</label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_call_registered'];?></label></td>
	</tr>
	<tr class="even">
		<td><label>Number of Open Calls</label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_open_calls'];?></label></td>
	</tr>

	<tr class="odd">
		<td><label>Number of Pending Calls</label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_pending_calls'];?></label></td>
	</tr>
	<tr class="even">
		<td><label>Number of Part Pending Calls</label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_part_pending'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>Number of Cancelled Calls</label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_cancelled'];?></label></td>
	</tr>
	<tr class="even">
		<td><label>Number of Closed Calls</label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_closed_calls'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>Average aging time for current pending calls</label></td>		
		<td style="text-align:center;"><label><?php if($reports['average_aging_current']==NULL){ echo "N/A";}  else{ echo $reports['average_aging_current'];}?></label></td>
	</tr>
	<tr class="even">
		<td><label>Average aging time for part pending calls</label></td>		
		<td style="text-align:center;"><label><?php if($reports['average_aging_part']==NULL){ echo "N/A";}  else{?><?php echo $reports['average_aging_part'];}?></label></td>
	</tr>
	<tr class="odd">
		<td><label>Average Closing Time</label></td>
		<td style="text-align:center;"><label><?php if($reports['average_closing']==NULL){ echo "N/A";}  else{ echo $reports['average_closing'];}?></label></td>
	</tr>
	<tr class="even">
		<td><label>Long Closure Calls</label></td>
		<td style="text-align:center;"><label><?php if($reports['long_closure']==NULL){ echo "N/A"; } else{ echo $reports['long_closure'];}?></label></td>
	</tr>
	<tr class="odd">
		<td><label>Closed calls < 12 hours</label></td>
		<td style="text-align:center;"><label><?php echo $reports['closed_calls'];?></label></td>
	</tr>
	<tr class="even">
		<td><label>Closed Calls < 12 and >24 hours</label></td>
		<td style="text-align:center;"><label><?php echo $reports['closed_calls_between'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>Closed Calls > 24 hours</label></td>
		<td style="text-align:center;"><label><?php echo $reports['closed_calls_greater'];?></label></td>
	</tr>
</table>
</form>
<?php }?>


