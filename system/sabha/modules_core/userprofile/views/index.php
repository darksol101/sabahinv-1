<?php $this->load->view('dashboard/header', array("header_insert"=>"userprofile/script", "title"=>$this->lang->line('changepassword'))); ?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('changepassword');?></h3>
  <ul class="content-box-tabs"></ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content  default-tab"><?php $this->load->view('userprofile/tab_userprofile');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
