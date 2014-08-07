<?php $this->load->view('dashboard/header', array("header_insert"=>"settings/warranty/script", "title"=>$this->lang->line('user_accounts'))); ?>
<?php echo $this->load->view('tabs');?>
<div class="content-box-content">
  <div id="tab1" class="tab-content"></div>
  <div id="tab1" class="tab-content"></div>
  <div id="tab2" class="tab-content  default-tab"><?php $this->load->view('settings/warranty/tab_warranty');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>