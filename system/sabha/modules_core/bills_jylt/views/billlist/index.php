<?php $this->load->view('dashboard/header', array("header_insert"=>"bills/billlist/script", "title"=>$this->lang->line('bills'))); ?>
<div class="content-box-header">
<h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('bills')?></h3>
<ul class="content-box-tabs">
	
    <li><a class="default-tab" href="#tab1" id="bills"><?php echo $this->lang->line('bills_list')?></a></li>
	
</ul>
</div>
<div class="content-box-content">

<div id="tab1" class="tab-content default-tab"><?php $this->load->view('bills/billlist/tab_bills');?></div>



</div>
<?php $this->load->view('dashboard/footer'); ?>
