<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
if($this->uri->segment(3)){
	$current_tab = $this->uri->segment(3);
}else{
	$current_tab = 'callcenter';
}
$lang = 'performance_report_'.$current_tab;
$default_callcenter = ($current_tab=='callcenter')?'class="default-tab"':'';
$default_engineers = ($current_tab=='engineers')?'class="default-tab"':'';
?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line($lang);?></h3>
  <ul class="content-box-tabs">
  	<li><a <?php echo $default_callcenter; ?> href="#tab1" id="reports/performance/callcenter"><?php echo $this->lang->line('callcenter')?></a></li>
    <li><a <?php echo $default_engineers; ?> href="#tab2" id="reports/performance/engineers"><?php echo $this->lang->line('engineers')?></a></li>
   </ul>
  <div class="clear"></div>
</div>