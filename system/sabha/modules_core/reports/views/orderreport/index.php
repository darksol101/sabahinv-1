<?php $this->load->view('dashboard/header', array("header_insert"=>"reports/orderreport/script", "title"=>$this->lang->line('order_report'))); ?>
<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('used_call_report')?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab1" id="reports/orderreport"><?php echo $this->lang->line('order_report')?></a></li>
   
  </ul>

  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab"><?php $this->load->view('reports/orderreport/reportsearch');?></div>
   <div id="tab2" class="tab-content"></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>

