<?php $this->load->view('dashboard/header', array("header_insert"=>"reports/script", "title"=>$this->lang->line('reports'))); ?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('reports');?></h3>
  <ul class="content-box-tabs">
    </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content"></div>
  <div id="tab2" class="tab-content  default-tab"><?php $this->load->view('reports/tab_reports');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
