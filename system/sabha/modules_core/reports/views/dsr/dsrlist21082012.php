<style type="text/css">
.tool-icon a{padding:5px;}
</style>
<form>
<table width="55%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="80%" /><col />
    <thead>
    	<tr>
    		<th style="background:none repeat scroll 0 0 #00689C; text-align:left; color:#FFFFFF;"><?php echo sprintf($this->lang->line('daily_service_report'),$reports['service_center_name'],format_date(strtotime(date('Y-m-d'))));?></th>
            <th style="text-align:center">
            	<div class="tool-icon">
            		<a class="btn" onclick="export_exl();" title="Download as excel"><?php echo icon('excel-download','Download as excel','png');?></a><a class="btn" onclick="email_pop();" title="E-mail"><?php echo icon('mail','E-mail','png');?></a>
            </div>
            </th>
        </tr>
    </thead>
    <tbody id="tblgrid">
	<tr class="odd">
		<td><label><?php echo $this->lang->line('total_call_registered');?></label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_call_registered'];?></label></td>
	</tr>
	<tr class="even">
		<td><label><?php echo $this->lang->line('open_calls');?></label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_open_calls'];?></label></td>
	</tr>

	<tr class="odd">
		<td><label><?php echo $this->lang->line('pending_calls');?></label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_pending_calls'];?></label></td>
	</tr>
	<tr class="even">
		<td><label><?php echo $this->lang->line('partpending_calls');?></label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_part_pending'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label><?php echo $this->lang->line('cancelled_calls');?></label></td>
		<td style="text-align:center;"><label><?php echo $reports['total_cancelled'];?></label></td>
	</tr>
    <tr class="even">
    	<td><label><?php echo $this->lang->line('pending_calls_less_2');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_pending_less_than_2'];?></label></td>
    </tr>
    <tr class="odd">
    	<td><label><?php echo $this->lang->line('pending_calls_between_2_and_7');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_pending_between_2and7'];?></label></td>
    </tr>
    <tr class="even">
    	<td><label><?php echo $this->lang->line('pending_calls_between_7_and_15');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_pending_between_7and15'];?></label></td>
    </tr>
    <tr class="odd">
    	<td><label><?php echo $this->lang->line('pending_calls_between_15_and_30');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_pending_between_15and30'];?></label></td>
    </tr>
    <tr class="even">
    	<td><label><?php echo $this->lang->line('pending_calls_greate_30');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_pending_grater_than30'];?></label></td>
    </tr>
    <tr class="odd">
    	<td><label><?php echo $this->lang->line('total_partpending_less_than_2');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_partpending_less_than_2'];?></label></td>
    </tr>
    <tr class="even">
    	<td><label><?php echo $this->lang->line('total_partpending_between_2and7');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_partpending_between_2and7'];?></label></td>
    </tr>
    <tr class="odd">
    	<td><label><?php echo $this->lang->line('total_partpending_between_7and15');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_partpending_between_7and15'];?></label></td>
    </tr>
    <tr class="even">
    	<td><label><?php echo $this->lang->line('total_partpending_between_15and30');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_partpending_between_15and30'];?></label></td>
    </tr>
    <tr class="odd">
    	<td><label><?php echo $this->lang->line('total_partpending_grater_than30');?></label></td>
        <td style="text-align:center"><label><?php echo $reports['total_partpending_grater_than30'];?></label></td>
    </tr>
	<tr class="even">
		<td><label><?php echo $this->lang->line('pending_calls_average_time');?></label></td>		
		<td style="text-align:center;"><label><?php if($reports['average_aging_current']==NULL){ echo "N/A";}  else{ echo $reports['average_aging_current'];}?></label></td>
	</tr>
	<tr class="odd">
		<td><label><?php echo $this->lang->line('partpending_calls_average_time');?></label></td>		
		<td style="text-align:center;"><label><?php if($reports['average_aging_part']==NULL){ echo "N/A";}  else{?><?php echo $reports['average_aging_part'];}?></label></td>
	</tr>
    </tbody>
</table>
<input type="hidden" name="service_center_name" id="service_center_name" value="<?php echo $reports['service_center_name'];?>" />
<input type="hidden" name="report_dt" id="report_dt" value="<?php echo format_date(strtotime(date('Y-m-d')));?>"  />
<input type="hidden" name="total_call_registered" id="total_call_registered" value="<?php echo $reports['total_call_registered'];?>" />
<input type="hidden" name="total_open_calls" id="total_open_calls" value="<?php echo $reports['total_open_calls'];?>" />
<input type="hidden" name="total_pending_calls" id="total_pending_calls" value="<?php echo $reports['total_pending_calls'];?>" />
<input type="hidden" name="total_part_pending" id="total_part_pending" value="<?php echo $reports['total_part_pending'];?>" />
<input type="hidden" name="total_cancelled" id="total_cancelled" value="<?php echo $reports['total_cancelled'];?>" />
<input type="hidden" name="total_pending_less_than_2" id="total_pending_less_than_2" value="<?php echo $reports['total_pending_less_than_2'];?>" />
<input type="hidden" name="total_pending_between_2and7" id="total_pending_between_2and7" value="<?php echo $reports['total_pending_between_2and7'];?>" />
<input type="hidden" name="total_pending_between_7and15" id="total_pending_between_7and15" value="<?php echo $reports['total_pending_between_7and15'];?>" />
<input type="hidden" name="total_pending_between_15and30" id="total_pending_between_15and30" value="<?php echo $reports['total_pending_between_15and30'];?>" />
<input type="hidden" name="total_pending_grater_than30" id="total_pending_grater_than30" value="<?php echo $reports['total_pending_grater_than30'];?>" />

<input type="hidden" name="total_partpending_less_than_2" id="total_partpending_less_than_2" value="<?php echo $reports['total_partpending_less_than_2'];?>" />
<input type="hidden" name="total_partpending_between_2and7" id="total_partpending_between_2and7" value="<?php echo $reports['total_partpending_between_2and7'];?>" />
<input type="hidden" name="total_partpending_between_7and15" id="total_partpending_between_7and15" value="<?php echo $reports['total_partpending_between_7and15'];?>" />
<input type="hidden" name="total_partpending_between_15and30" id="total_partpending_between_15and30" value="<?php echo $reports['total_partpending_between_15and30'];?>" />
<input type="hidden" name="total_partpending_grater_than30" id="total_partpending_grater_than30" value="<?php echo $reports['total_partpending_grater_than30'];?>" />

<input type="hidden" name="average_aging_current" id="average_aging_current" value="<?php echo $reports['average_aging_current'];?>" />
<input type="hidden" name="average_aging_part" id="average_aging_part" value="<?php echo $reports['average_aging_part'];?>" />
</form>