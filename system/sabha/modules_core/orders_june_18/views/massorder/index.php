<?php $this->load->view('dashboard/header', array("header_insert"=>"orders/massorder/script", "title"=>$this->lang->line('mass_order'))); ?>
<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('mass_order')?></h3>
  <ul class="content-box-tabs">
   <li><a  href="#tab1" id="orders"><?php echo $this->lang->line('order_list')?></a></li>
    <li><a href="#tab2" id="orders/addorder"><?php echo $this->lang->line('PO')?></a></li>
    <li><a class="default-tab" href="#tab3" id="orders/callorder"><?php echo $this->lang->line('RO')?></a></li>
    
  </ul>

  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content"></div>
   <div id="tab2" class="tab-content"></div>
     <div id="tab3" class="tab-content default-tab"><?php $this->load->view('orders/massorder/tab_callorders');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>

