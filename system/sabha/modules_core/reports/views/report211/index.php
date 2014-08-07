<?php $this->load->view('dashboard/header', array("header_insert"=>"reports/report211/script", "title"=>$this->lang->line('211_report'))); ?>

<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('211_report')?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab1" id="reports/report211"><?php echo $this->lang->line('211_report')?></a></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab"> <?php  $this->load->view('reports/report211/tab_report211');?> </div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
