<?php $this->load->view('dashboard/header', array("header_insert"=>"badparts/transfer/script", "title"=>$this->lang->line('defect_transfer')))?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('defect_transfer')?></h3>
<ul class="content-box-tabs">
	<li><a class="" href="#tab1" id="badparts/transfer/servicecenter"><?php echo $this->lang->line('defective_transfer')?></a></li>
	<li><a class="default-tab" href="#tab2" id="badparts/transfer"><?php echo $this->lang->line('defect_list')?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
	<div id="tab1" class="tab-content"></div>
	<div id="tab2" class="tab-content default-tab"><?php $this->load->view('badparts/transfer/tab_edit');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
