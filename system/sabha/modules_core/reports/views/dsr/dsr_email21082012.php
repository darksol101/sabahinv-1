<style type="text/css">
.tblgrid td{padding: 6px 10px;}
.tblgrid td.even{background: none repeat scroll 0 0 #F2F9FC;}
.tblgrid td.odd{}
.tblgrid td label{color: #555555}
</style>
<form>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="70%" /><col />
    <thead>
    	<tr>
    		<th style="background:none repeat scroll 0 0 #00689C; text-align:left; color:#FFFFFF; padding: 8px 10px;" colspan="2"><?php echo sprintf($this->lang->line('daily_service_report'),$service_center_name,$report_dt);?></th>
        </tr>
    </thead>
	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('total_call_registered');?></label></td>
		<td style="text-align:center;"><label style="color: #555555;"><?php echo $total_call_registered;?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('open_calls');?></label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $total_open_calls;?></label></td>
	</tr>

	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('pending_calls');?></label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $total_pending_calls;?></label></td>
	</tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('partpending_calls');?></label></td>
		<td style="text-align:center;padding: 6px 10px"><label style="color: #555555;"><?php echo $total_part_pending;?></label></td>
	</tr>
	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('cancelled_calls');?></label></td>
		<td style="text-align:center;padding: 2px 10px"><label style="color: #555555;"><?php echo $total_cancelled;?></label></td>
	</tr>
    <tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('pending_calls_less_2');?></label></td>
        <td style="text-align:center;padding: 2px 10px"><label style="color: #555555;"><?php echo $total_pending_less_than_2;?></label></td>
    </tr>
    <tr class="odd" >
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('pending_calls_between_2_and_7');?></label></td>
        <td style="text-align:center"><label><?php echo $total_pending_between_2and7;?></label></td>
    </tr>
    <tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('pending_calls_between_7_and_15');?></label></td>
        <td style="text-align:center;padding: 2px 10px"><label style="color: #555555;"><?php echo $total_pending_between_7and15;?></label></td>
    </tr>
    <tr class="odd">
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('pending_calls_between_15_and_30');?></label></td>
        <td style="text-align:center;padding: 2px 10px"><label style="color: #555555;"><?php echo $total_pending_between_15and30;?></label></td>
    </tr>
    <tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
    	<td style="padding: 6px 10px"><label style="color:#555555;"><?php echo $this->lang->line('pending_calls_greate_30');?></label></td>
        <td style="text-align:center;padding: 2px 10px"><label style="color: #555555;"><?php echo $total_pending_grater_than30;?></label></td>
    </tr>
    <tr class="odd">
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('total_partpending_less_than_2');?></label></td>
        <td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php echo $total_partpending_less_than_2;?></label></td>
    </tr>
    <tr class="even"  style="background: none repeat scroll 0 0 #F2F9FC;">
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('total_partpending_between_2and7');?></label></td>
        <td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php echo $total_partpending_between_2and7;?></label></td>
    </tr>
    <tr class="odd">
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('total_partpending_between_7and15');?></label></td>
        <td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php echo $total_partpending_between_7and15;?></label></td>
    </tr>
    <tr class="even"  style="background: none repeat scroll 0 0 #F2F9FC;">
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('total_partpending_between_15and30');?></label></td>
        <td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php echo $total_partpending_between_15and30;?></label></td>
    </tr>
    <tr class="odd">
    	<td style="padding: 6px 10px"><label style="color: #555555;"><?php echo $this->lang->line('total_partpending_grater_than30');?></label></td>
        <td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php echo $total_partpending_grater_than30;?></label></td>
    </tr>
	<tr class="even" style="background: none repeat scroll 0 0 #F2F9FC;">
		<td style="padding: 6px 10px"><label style="color: #555555;">Average aging time for current pending calls</label></td>		
		<td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php if($average_aging_current==NULL){ echo "N/A";}  else{ echo $average_aging_current;}?></label></td>
	</tr>
	<tr class="odd">
		<td style="padding: 6px 10px"><label style="color: #555555;">Average aging time for part pending calls</label></td>		
		<td style="text-align:center; padding: 6px 10px"><label style="color: #555555;"><?php if($average_aging_part==NULL){ echo "N/A";}  else{?><?php echo $average_aging_part;}?></label></td>
	</tr>
</table>
</form>