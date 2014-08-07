<?php $this->load->view('dashboard/header', array("header_insert"=>"cities/script", "title"=>$this->lang->line('city'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('cities');?></h3>
<ul class="content-box-tabs">
	<li><a href="#tab1" id="servicecenters"><?php echo $this->lang->line('serivcecenter');?></a></li>
	<li><a class="default-tab" href="#tab2" id="cities"><?php echo $this->lang->line('city');?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content"></div>
<div id="tab2" class="tab-content  default-tab"><?php $this->load->view('cities/tab_cities');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
