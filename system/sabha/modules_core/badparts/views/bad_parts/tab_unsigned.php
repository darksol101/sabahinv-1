<?php $this->load->view('dashboard/header', array("header_insert"=>"badparts/bad_parts/script", "title"=>$this->lang->line('unsigned_report'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('unsigned_report')?></h3>
<ul class="content-box-tabs">
	<li><a  href="#tab1" id="badparts"><?php echo $this->lang->line('badparts')?></a></li>
    <li><a href="#tab2"  id="badparts/returnlist"><?php echo $this->lang->line('return_list')?></a></li>
	<li><a class="default-tab" href="#tab3" id="badparts/unsignedlist"><?php echo $this->lang->line('unsigned_report')?></a></li>
	
</ul>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content"></div>
<div id="tab2" class="tab-content"></div>
<div id="tab3" class="tab-content default-tab"><?php echo $this->load->view('badparts/bad_parts/unsignedlist');?></div>

</div>
<?php $this->load->view('dashboard/footer'); ?>

















 