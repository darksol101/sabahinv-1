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
		}
		if($('#cstatuslist').val()==4){
			$("#lblreason").html('<?php echo $this->lang->line('call_reason_cancellation');?>');
		}
	});
});
</script>
<div class="form_row">
		<div id="callstat">
			<div class="col">
			<div id="stat">
				<label>Call Status:</label>
				<?php echo $call_status;?><br />
				</div>
				</div>
				
					<div class="col">
					<div id="margin">
					<div id="statuss">
					<span id="lblreason"><?php
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
					?></span><br /> <br />
						<span id="reasonlist"></span>
			</div>
	
			<?php
			if($call_details->call_status==1){
				echo $this->lang->line('pending_date');
			}elseif($call_details->call_status==3){
				echo $this->lang->line('closure_date');
			}else{
				echo '&nbsp;';
			}
			?>
				</div>
	</div>
	</div>
	</div>
	<?php
	if($call_details->call_status==1){
		echo format_date(strtotime($call_details->pending_dt)).' '.date("H:i",strtotime($call_details->pending_tm));
	}elseif($call_details->call_status==3){
		echo format_date(strtotime($call_details->closure_dt)).' '.date("H:i",strtotime($call_details->closure_tm));
	}else{
		echo '&nbsp;';
	}
	?>

<div  id = 'dispatch'  <?php if ($call_details->call_status != 3){echo 'style="display:none;"';}?> >
<?php if($call_details->dispatch==1){ ?> <label style="text-align:center; font-weight:bold; font-size:13px;"> Set Dispatched </label><?php } else{?>
<input type="button" id='btn_dispatch' value='Dispatch' onclick="dispatchset('<?php echo $call_details->call_id;?>');"/> <?php }?> 
 </div>


 <?php if($call_details->call_status ==3) { ?>
<?php echo 'Happy Call Verification' ;?> 
  <?php {if ($call_details->happy_status == 1){ echo '<b>Verified</b>';}else {echo '<b>Not Verified</b>';}}?> 
 <?php }?>
	 <div class="form_row">
		<div id="all">
			<div class="col">
			<div id="list">
			<input type="button"
			onclick="getcallreasonpendinglist('<?php echo $call_details->call_id;?>');" value="Pending Reason List" class="bbasic_button" />
			</div>
			</div>
			<div class="col">
			<div id="file">
			<input type="button"
				onclick="getWarrantyUpload('<?php echo $call_details->call_id;?>');" class="bbasic_button" value="File Attach" /> 
			<div class="col">
				<div id="rem">
				<input type="button"
				name="reminders" id="reminders" value="Reminder" class="bbasic_button"
				onclick="Reminders();" />
			</div>
			</div>
			</div>
		</div>
		</div>
	</div>
	
	<?php }?>