<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php if($call_details->call_id>0){?>
<script type="text/javascript">
$(document).ready(function() {
	getreasonlist('<?php echo $call_details->call_status;?>','<?php echo $call_details->call_reason_pending;?>');
	$('#cstatuslist').change(function() {
		if($('#cstatuslist').val()==1){
			$("#lblreason").html('<?php echo $this->lang->line('call_reason_pending');?>');
		}
		if($('#cstatuslist').val()==3){
			$("#lblreason").html('<?php echo $this->lang->line('call_reason_closure');?>');
				$("#dispatch").show();
		}
		if($('#cstatuslist').val()==4){
			$("#lblreason").html('<?php echo $this->lang->line('call_reason_cancellation');?>');
		}
	});
});
</script>
<tr>
	<td>Call Status:</td>
	<td><?php echo $call_status;?></td>
	<td><span id="lblreason"><?php
	if($call_details->call_status==1){
		echo $this->lang->line('call_reason_pending');
	}elseif($call_details->call_status==2){
		echo $this->lang->line('call_reason_pending');
	}elseif($call_details->call_status==3){
		echo $this->lang->line('call_reason_closure');
	}elseif($call_details->call_status==4){
		echo $this->lang->line('call_reason_cancellation');
	}else{
	}
	?></span></td>
	<td><span id="reasonlist"></span></td>
	<td><?php
	if($call_details->call_status==1){
		echo $this->lang->line('pending_date');
	}elseif($call_details->call_status==3){
		echo $this->lang->line('closure_date');
	}else{
		echo '&nbsp;';
	}
	?></td>
	<td><?php
	if($call_details->call_status==1){
		echo format_date(strtotime($call_details->pending_dt)).' '.date("H:i",strtotime($call_details->pending_tm));
	}elseif($call_details->call_status==3){
		echo format_date(strtotime($call_details->closure_dt)).' '.date("H:i",strtotime($call_details->closure_tm));
	}else{
		echo '&nbsp;';
	}
	?></td>
</tr>
<tr id = 'dispatch' <?php if ($call_details->call_status != 3){echo 'style="display:none;"';}?>>
<td> <label><?php echo $this->lang->line('dispatch'); ?>:</label></td>
<td><input name="dispatch" type="checkbox" id="dispatch"  <?php if($call_details->dispatch==1){?> checked="checked" <?php }?>/></td> 
</tr>
<tr>
 <?php if($call_details->call_status ==3) { ?>
 <td> <?php echo 'Happy Call Verification' ;?> </td>
 <td> <?php {if ($call_details->happy_status == 1){ echo '<b>Verified</b>';}else {echo '<b>Not Verified</b>';}}?> </td>
 <?php }?>
	<td colspan="6" style="text-align:right;"><input type="button"
		onclick="getcallreasonpendinglist('<?php echo $call_details->call_id;?>');"
		value="Pending Reason List" class="basic_button" /><input
		type="button"
		onclick="getWarrantyUpload('<?php echo $call_details->call_id;?>');"
		class="basic_button" value="File Attach" /> <input type="button"
		name="reminders" id="reminders" value="Reminder" class="basic_button"
		onclick="Reminders();" /></td>
</tr>
	<?php }?>