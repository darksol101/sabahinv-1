
<?php $this->load->view('dashboard/header', array("header_insert"=>"access/script", "title"=>$this->lang->line('access_management'))); ?>

<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php $this->lang->line('access_management');?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="access"><?php echo $this->lang->line('users')?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('tab_access');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
