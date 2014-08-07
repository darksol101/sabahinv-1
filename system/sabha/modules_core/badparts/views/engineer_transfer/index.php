<?php $this->load->view('dashboard/header', array("header_insert"=>"badparts/engineer_transfer/script", "title"=>$this->lang->line('defect_transfer')))?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('return_part_engineer')?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab2" id="parts/bad_parts/svctransfer"><?php echo $this->lang->line('return_part_engineer')?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
	<div id="tab1" class="tab-content default-tab"><?php $this->load->view('badparts/engineer_transfer/tab_transfer');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
