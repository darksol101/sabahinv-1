<?php $this->load->view('dashboard/header', array("header_insert"=>"badparts/bad_parts/script", "title"=>$this->lang->line('stockslist'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('defective_stocklist')?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="badparts/defectivestock"><?php echo $this->lang->line('defective_stocklist')?></a></li>
</ul>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('badparts/bad_parts/tab_badstocklist');?></div>

</div>
<?php $this->load->view('dashboard/footer'); ?>
