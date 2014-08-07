<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"sales/return/script", "title"=>$this->lang->line('sales'))); ?>
<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('sales_return')?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab1" id="sales/salesreturn"><?php echo $this->lang->line('sales_return_list')?></a></li>
    <li><a href="#tab2" id="sales/salesreturn/add"><?php echo $this->lang->line('sales_return')?></a></li>
  </ul>

  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content"></div>
   <div id="tab2" class="tab-content default-tab"><?php $this->load->view('sales/return/tab_return');?></div>
   
</div>
<?php $this->load->view('dashboard/footer'); ?>

