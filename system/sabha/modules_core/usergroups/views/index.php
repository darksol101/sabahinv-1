<?php $this->load->view('dashboard/header', array("header_insert"=>"usergroups/script", "title"=>$this->lang->line('user_accounts'))); ?>

<div class="content-box-header">
  <h3 style="cursor: s-resize;">User Group Management</h3>
  <ul class="content-box-tabs">
    <li><a href="#tab1" id="users"><?php echo $this->lang->line('users')?></a></li>
    <li><a class="default-tab" href="#tab2" id="usergroups"><?php echo $this->lang->line('usergroups')?></a></li>
   
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content"></div>
  <div id="tab2" class="tab-content default-tab" style="display:none;"><?php $this->load->view('usergroups/tab_usergroups');?></div>
   <div id="tab3" class="tab-content" style="display:none;"></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
