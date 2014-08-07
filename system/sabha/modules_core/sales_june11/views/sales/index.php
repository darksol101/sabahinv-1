<?php $this->load->view('dashboard/header', array("header_insert"=>"sales/sales/script", "title"=>$this->lang->line('sales'))); ?>
<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('sales')?></h3>
  <ul class="content-box-tabs">
    <li><a href="#tab1" id="sales"><?php echo $this->lang->line('saleslist')?></a></li>
    <li><a class="default-tab" href="#tab2" id="sales/sale"><?php echo $this->lang->line('sales')?></a></li>
  </ul>

  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content"></div>
   <div id="tab2" class="tab-content default-tab"><?php $this->load->view('sales/sales/addsales');?></div>
   
</div>
<?php $this->load->view('dashboard/footer'); ?>

