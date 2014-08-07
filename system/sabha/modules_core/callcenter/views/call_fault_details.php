<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php if($call_details->call_id>0){?>
<?php
$this->load->model(array('faultsettings/mdl_defect','faultsettings/mdl_symptom','faultsettings/mdl_repair'));
//$fault_details = $this->mdl_repair->getrepairdetails((int)$call_details->repair_id);
?>
<fieldset>
<legend><?php echo $this->lang->line('call_fault_details'); ?></legend>

	<div class="form_row">
		<div class="col">
		<div id="sym">
		<div id="field_symptom">
			<?php echo $this->lang->line('symptom_code');?><br />
			<span id="symptom_select_box"><?php echo $symptom_select?></span>	
		</div>	
		</div>
		</div>
		
		<div class="col">
		<div id="field_defect">	
			<?php echo $this->lang->line('defect_code');?><br />	
			<span id="defect_select_box"><?php echo $defect_select?></span>	
        </div>
		</div>
		<div class="col">
		<div id="field_repair">
			<?php echo $this->lang->line('repair_code');?><br />
			<span id="repair_select_box"><?php echo $repair_select;?></span>
        </div>
		</div>
	</div>
       

<?php
if(count($call_details)>0){?>
<script type="text/javascript">
$("#symptom_code").val('<?php echo $call_details->symptom_id;?>');
getDefectCodeBySymptom('<?php echo $call_details->symptom_id;?>','<?php echo $call_details->defect_id;?>');
getRepairCodeBydefect('<?php echo $call_details->defect_id;?>','<?php echo $call_details->repair_id;?>');
</script>
<?php }?>
	
</fieldset>
<?php }?>