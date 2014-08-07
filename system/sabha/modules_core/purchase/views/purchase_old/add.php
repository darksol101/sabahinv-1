<?php $this->load->view('dashboard/header', array("header_insert"=>"purchase/purchase/script", "title"=>$this->lang->line('purchase'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('purchase')?></h3>
<ul class="content-box-tabs">
	<li><a href="#tab1" id="purchase"><?php echo $this->lang->line('purchase_list')?></a></li>
    <li><a class="default-tab" class="" href="#tab2" id="purchase/addrecord"><?php echo $this->lang->line('addpurchase')?></a></li>
</ul>
</div>
<div class="content-box-content">
	<div id="tab1" class="tab-content default-tab"><?php $this->load->view('purchase/purchase/tab_purchase');?></div>
	<div id="tab2" class="tab-content"></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
