<form>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="70%" /><col />
    <thead>
    	<tr>
    		<th style="background:none repeat scroll 0 0 #00689C; text-align:left; color:#FFFFFF; padding: 8px 10px;" colspan="2"><?php echo sprintf($this->lang->line('closed_call_report'),$service_center_name,$report_from_date,$report_to_date);?></th>
        </tr>
    </thead>
	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;">Number of Closed Calls</label></td>
		<td style="text-align:center;"><label style="color: #555555;"><?php echo $total_closed_calls;?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;">Average Closing Time</label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $average_closing;?></label></td>
	</tr>

	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;">Closed calls < 2 days</label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $closed_calls;?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;">Closed Calls between 2 and 7 days</label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $closed_calls_between1;?></label></td>
	</tr>
	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;">Closed Calls between 7 and 15 days</label></td>
		<td style="text-align:center;padding: 2px 10px"><label style="color: #555555;"><?php echo $closed_calls_between2;?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;">Closed Calls > 15 days</label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $closed_calls_greater;?></label></td>
	</tr>
</table>
</form>