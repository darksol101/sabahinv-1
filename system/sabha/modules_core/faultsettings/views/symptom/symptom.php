<?php $this->load->view('dashboard/header', array("header_insert"=>"faultsettings/symptom/script", "title"=>$this->lang->line('user_accounts'))); ?>
<?php echo $this->load->view('tabs');?>
<div class="content-box-content">
  <div id="tab7" class="tab-content default-tab"><?php $this->load->view('faultsettings/symptom/tab_symptom');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>