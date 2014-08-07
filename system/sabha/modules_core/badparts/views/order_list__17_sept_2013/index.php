<?php $this->load->view('dashboard/header', array("header_insert"=>"badparts/order_list/script", "title"=>$this->lang->line('badparts_order'))); ?>
<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('badparts_order_list')?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab1" id="badparts/badparts_order"><?php echo $this->lang->line('badparts_order_list')?></a></li>
    <li><a href="#tab2" id="badparts/badparts_order/addbadpartorder"><?php echo $this->lang->line('badparts_order')?></a></li>
  </ul>

  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab"><?php $this->load->view('badparts/order_list/tab_orders');?></div>
   <div id="tab2" class="tab-content"></div>

</div>
<?php $this->load->view('dashboard/footer'); ?>

