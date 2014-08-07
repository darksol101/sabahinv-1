<?php $this->load->view('dashboard/header', array("header_insert"=>"reminders/script", "title"=>$this->lang->line('brands'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('reminders')?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="reminders"><?php echo $this->lang->line('reminders')?></a></li>
</ul>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('tab_remarks');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
