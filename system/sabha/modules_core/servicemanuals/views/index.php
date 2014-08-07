<?php $this->load->view('dashboard/header', array("header_insert"=>"servicemanuals/script", "title"=>$this->lang->line('servicemanuals'))); ?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('service_manuals');?></h3>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content  default-tab"><?php $this->load->view('servicemanuals/tab_servicemanuals');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>