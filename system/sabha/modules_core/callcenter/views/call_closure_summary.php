<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php if($call_details->call_id>0){?>
<?php
$timeOptions =array();
for($i=1;$i<=24;$i++){
	$timeOptions[$i]['value'] = $i;
	$timeOptions[$i]['text'] = $i;
}
$hr_list = $this->mdl_html->genericlist($timeOptions,'call_visit_hr',array('style'=>'width:50px;'),'value','text','');
$call_details->call_visit_dt = ($call_details->call_visit_dt=='0000-00-00')?'':$call_details->call_visit_dt;
$call_details->call_delivery_dt = ($call_details->call_delivery_dt=='0000-00-00')?'':$call_details->call_delivery_dt;
?>

<fieldset>
<legend><?php echo $this->lang->line('call_closure_summary'); ?></legend>
	
	<div class="form_row">
		<div class="col">
			<div id="closure">
				<?php echo $this->lang->line('call_engineer_remark');?><br />
				<textarea id="call_engineer_remark" name="call_engineer_remark"><?php echo $call_details->call_engineer_remark;?></textarea>
			</div>
		</div>
		
		<div class="col">
			<div id="details">
				<?php echo $this->lang->line('call_detail_wrk_done');?><br />
				<textarea id="call_detail_wrk_done" name="call_detail_wrk_done"><?php echo $call_details->call_detail_wrk_done;?></textarea>
			</div>
		</div>
		</div><br />
		<div class="form_row">
		<div class="col">
			<div id="delivery">
				<?php echo $this->lang->line('call_delivery_dt');?><br />
				<input type="text" name="call_delivery_dt" id="call_delivery_dt" value="<?php echo format_date(strtotime($call_details->call_delivery_dt));?>" class="text-input datepicker<?php if($call_details->call_status==3){ echo ' validate[required]';}?>" readonly="readonly" /><br />
			</div>
		</div>
		</div><br />
		<div class="form_row">
		<div class="col colc">
			<div id="status">
			
				<?php 	$this->load->view('call_status');?>
			</div>
	 </div>
	 </div>

</fieldset>




<?php }?>