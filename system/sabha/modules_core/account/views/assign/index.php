<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); ?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('assign');?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="account/assign"><?php echo $this->lang->line('assign')?></a></li>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab">
</div>
</div>
<?php $this->load->view('dashboard/footer');