<style type="text/css">
.tool-icon a{padding:5px;}
</style>
<form onsubmit="return false">
<table width="55%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="70%" /><col width="10%" />
    <thead>
    	<tr>
    		<th colspan="1"><?php echo sprintf($this->lang->line('closed_call_report'),$closedcallreports['service_center_name'],$closedcallreports['report_from_date'],$closedcallreports['report_to_date']);?></th>
            <th>
            	<!--<div class="tool-icon">
            		<a class="btn" onclick="export_exl();" title="Download as excel"><?php echo icon('excel-download','Download as excel','png');?></a><a class="btn" onclick="email_pop();" title="E-mail"><?php echo icon('mail','E-mail','png');?></a>
            </div>-->
            </th>
        </tr>
    </thead>
    <tbody id="tblgrid">
	<tr class="even">
		<td><label>Number of Closed Calls</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['total_closed_calls'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>TAT</label></td>
		<td style="text-align:center;"><label><?php if($closedcallreports['average_closing']==NULL){ echo "N/A";}  else{ echo $closedcallreports['average_closing'];}?></label></td>
	</tr>
	<tr class="even">
		<td><label>TAT < 2 days</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['closed_calls'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>TAT between 2 and 7 days</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['closed_calls_between1'];?></label></td>
	</tr>

	<tr class="even">
		<td><label>TAT between 7 and 15 days</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['closed_calls_between2'];?></label></td>
	</tr>
	<tr class="odd">
		<td><label>TAT > 15 days</label></td>
		<td style="text-align:center;"><label><?php echo $closedcallreports['closed_calls_greater'];?></label></td>
	</tr>
    </tbody>
</table>
<input type="hidden" name="service_center_name" id="service_center_name" value="<?php echo $closedcallreports['service_center_name'];?>" />
<input type="hidden" name="total_closed_calls" id="total_closed_calls" value="<?php echo $closedcallreports['total_closed_calls'];?>" />
<input type="hidden" name="average_closing" id="average_closing" value="<?php echo $closedcallreports['average_closing'];?>" />
<input type="hidden" name="closed_calls" id="closed_calls" value="<?php echo $closedcallreports['closed_calls'];?>" />
<input type="hidden" name="closed_calls_between1" id="closed_calls_between1" value="<?php echo $closedcallreports['closed_calls_between1'];?>" />
<input type="hidden" name="closed_calls_between2" id="closed_calls_between2" value="<?php echo $closedcallreports['closed_calls_between2'];?>" />
<input type="hidden" name="closed_calls_greater" id="closed_calls_greater" value="<?php echo $closedcallreports['closed_calls_greater'];?>" />
</form>