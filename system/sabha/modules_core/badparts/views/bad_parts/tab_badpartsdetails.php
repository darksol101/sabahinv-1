<?php $this->load->view('dashboard/header', array("header_insert"=>"bad_parts/script", "title"=>$this->lang->line('bad_parts_details')))?>
<div class="content-box-header">
<h3 style="cursor: s-resize;">Bad Parts details</h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="badparts/defectivestock"><?php echo $this->lang->line('bad_parts_details')?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
	<div id="tab1" class="tab-content default-tab"><?php $this->load->view('bad_parts/badpartsdetails_search');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
