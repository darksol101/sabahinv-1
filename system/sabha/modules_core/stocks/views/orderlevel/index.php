<?php $this->load->view('dashboard/header', array("header_insert"=>"stocks/stocklist/script", "title"=>$this->lang->line('stockslist'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('order_level_stock')?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="stocks/stocklist/orderlevel"><?php echo $this->lang->line('order_level')?></a></li>
</ul>

</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('stocks/stocklist/tab_stocklist');?></div>

</div>
<?php $this->load->view('dashboard/footer'); ?>
