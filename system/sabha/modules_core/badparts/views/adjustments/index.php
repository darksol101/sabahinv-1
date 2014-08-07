<?php $this->load->view('dashboard/header', array("header_insert"=>"badparts/adjustments/script", "title"=>$this->lang->line('adjustment')))?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('adjustment')?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="badparts/adjustment/finaladjustment"><?php echo $this->lang->line('adjustment');?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
	<div id="tab1" class="tab-content default-tab"><?php $this->load->view('badparts/adjustments/tab_adjustment');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
