<?php $this->load->view('dashboard/header', array("header_insert"=>"reports/engineerreport/script", "title"=>$this->lang->line('claim_detail'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('claim_detail')?> </h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="reports/engineerreport"><?php echo $this->lang->line('claim_report')?></a></li>
</ul>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('reports/engineerreport/details');?></div>

</div>
<?php $this->load->view('dashboard/footer'); ?>
