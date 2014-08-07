<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php

if($this->uri->segment(2)){
	$current_tab = $this->uri->segment(2);
}else{
	$current_tab = 'reasons';
}
//$default_faults = ($current_tab=='faults')?'class="default-tab"':'';
$default_reasons = ($current_tab=='reasons')?'class="default-tab"':'';
$default_partpending = ($current_tab=='partpending')?'class="default-tab"':'';
$default_closure = ($current_tab=='closure')?'class="default-tab"':'';
$default_cancellation = ($current_tab=='cancellation')?'class="default-tab"':'';
$default_warranty = ($current_tab=='warranty')?'class="default-tab"':'';
?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line($current_tab);?></h3>
  <ul class="content-box-tabs">
    <!--<li><a <?php echo $default_faults; ?> href="#tab1" id="settings/faults"><?php echo $this->lang->line('faults');?></a></li>-->
    <li><a <?php echo $default_reasons; ?> href="#tab2" id="settings/reasons"><?php echo $this->lang->line('reason_pending');?></a></li>
    <li><a <?php echo $default_partpending; ?> href="#tab3" id="settings/partpending"><?php echo $this->lang->line('partpending');?></a></li>
    <li><a <?php echo $default_closure; ?> href="#tab4" id="settings/closure"><?php echo $this->lang->line('closure');?></a></li>
    <li><a <?php echo $default_cancellation; ?>  href="#tab5" id="settings/cancellation"><?php echo $this->lang->line('cancellation');?></a></li>
    <li><a <?php echo $default_warranty; ?>  href="#tab5" id="settings/warranty"><?php echo $this->lang->line('warranty');?></a></li>
  </ul>
  <div class="clear"></div>
</div>
