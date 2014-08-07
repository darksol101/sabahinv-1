<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php if($call_details->call_id>0){?>
<?php
$this->load->model(array('faultsettings/mdl_defect','faultsettings/mdl_symptom','faultsettings/mdl_repair'));
//$fault_details = $this->mdl_repair->getrepairdetails((int)$call_details->repair_id);
?>
<fieldset>
<legend><?php echo $this->lang->line('call_fault_details'); ?></legend>
<table width="100%">
    <tr>
    	<td><?php echo $this->lang->line('symptom_code');?></td>
        <td><span id="symptom_select_box"><?php echo $symptom_select?></span></td>
        <td><?php echo $this->lang->line('defect_code');?></td>
        <td><span id="defect_select_box"><?php echo $defect_select?></span></td>
        <td><?php echo $this->lang->line('repair_code');?></td>
        <td><span id="repair_select_box"><?php echo $repair_select;?></span></td>
        <td></td>
        
    </tr>
</table>
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