<?php $this->load->view('dashboard/header', array("header_insert"=>"settings/fault_script", "title"=>$this->lang->line('user_accounts'))); ?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('faults');?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab"  href="#tab1" id="settings/faults"><?php echo $this->lang->line('faults');?></a></li>
    <li><a href="#tab2" id="settings/reasons"><?php echo $this->lang->line('reason_pending');?></a></li>
    <li><a href="#tab3" id="settings/partpending"><?php echo $this->lang->line('partpending');?></a></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab"><?php $this->load->view('settings/tab_faults');?></div>
  <div id="tab2" class="tab-content"></div>
  <div id="tab3" class="tab-content"></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>