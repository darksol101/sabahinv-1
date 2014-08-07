<form>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="70%" /><col />
    <thead>
    	<tr>
    		<th style="background:none repeat scroll 0 0 #00689C; text-align:left; color:#FFFFFF; padding: 8px 10px;" colspan="2"><?php echo sprintf($this->lang->line('daily_service_report'),$service_center_name,$report_dt);?></th>
        </tr>
    </thead>
	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;">Total Call Registered</label></td>
		<td style="text-align:center;"><label style="color: #555555;"><?php echo $total_call_registered;?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;">Number of Open Calls (As of today)</label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $total_open_calls;?></label></td>
	</tr>

	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;">Number of Pending Calls (As of today)</label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $total_pending_calls;?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;">Number of Part Pending Calls (As of today)</label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $total_part_pending;?></label></td>
	</tr>
	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;">Number of Cancelled Calls</label></td>
		<td style="text-align:center;padding: 2px 10px"><label style="color: #555555;"><?php echo $total_cancelled;?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;">Number of Closed Calls</label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $total_closed_calls;?></label></td>
	</tr>
	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;">Average aging time for current pending calls</label></td>		
		<td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php if($average_aging_current==NULL){ echo "N/A";}  else{ echo $average_aging_current;}?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;">Average aging time for part pending calls</label></td>		
		<td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php if($average_aging_part==NULL){ echo "N/A";}  else{?><?php echo $average_aging_part;}?></label></td>
	</tr>
</table>
</form>