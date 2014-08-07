<?php $this->load->view('dashboard/header', array("header_insert"=>"sales/sales/script", "title"=>$this->lang->line('sales'))); ?>
<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('sales')?></h3>
  <ul class="content-box-tabs">
   
    <li><a class="default-tab" href="#tab1" id="sales/modsale"><?php echo $this->lang->line('sales')?></a></li>
  </ul>

  <div class="clear"></div>
</div>
<div class="content-box-content">
 
   <div id="tab1" class="tab-content default-tab"><?php $this->load->view('sales/sales/addsales');?></div>
   
</div>
<?php $this->load->view('dashboard/footer'); ?>

