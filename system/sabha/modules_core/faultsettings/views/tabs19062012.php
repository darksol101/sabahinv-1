<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php

if($this->uri->segment(2)){
	$current_tab = $this->uri->segment(2);
}else{
	$current_tab = 'symptom';
}
$default_defect = ($current_tab=='defect')?'class="default-tab"':'';
$default_symptom = ($current_tab=='symptom')?'class="default-tab"':'';
$default_repair = ($current_tab=='repair')?'class="default-tab"':'';
?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line($current_tab);?></h3>
  <ul class="content-box-tabs">
     <li><a <?php echo $default_symptom; ?>  href="#tab1" id="faultsettings/symptom"><?php echo $this->lang->line('symptom_code');?></a></li>
    <li><a <?php echo $default_defect; ?>  href="#tab2" id="faultsettings/defect"><?php echo $this->lang->line('defect_code');?></a></li>
    <li><a <?php echo $default_repair; ?>  href="#tab3" id="faultsettings/repair"><?php echo $this->lang->line('repair_code');?></a></li>
  </ul>
  <div class="clear"></div>
</div>
