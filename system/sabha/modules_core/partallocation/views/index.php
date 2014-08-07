<?php $this->load->view('dashboard/header', array("header_insert"=>"partallocation/script", "title"=>$this->lang->line('eng_allocation_good_parts'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('eng_allocation_good_parts')?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="partallocation"><?php echo $this->lang->line('allocate')?></a></li>
    <li><a href="#tab2" id="partallocation/allocationreport"><?php echo $this->lang->line('allocation_report')?></a></li>
    <li><a href="#tab3" id="partallocation/unsignedlist"><?php echo $this->lang->line('unsigned')?></a></li>
    
</ul>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('partallocation/tab_parts');?></div>
<div id="tab2" class="tab-content"></div>
<div id="tab3" class="tab-content"></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>

















 