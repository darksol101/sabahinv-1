<?php $this->load->view('dashboard/header', array("header_insert"=>"engineers/script", "title"=>$this->lang->line('enginners'))); ?>

<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('enginners');?></h3>
  <ul class="content-box-tabs">
    <li><a href="#tab1" id="users"><?php echo $this->lang->line('users')?></a></li>
    <li><a href="#tab2" id="usergroups"><?php echo $this->lang->line('usergroups')?></a></li>
    <li><a href="#tab3" id="engineers" class="default-tab"><?php echo $this->lang->line('engineers')?></a></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content" style="display:none;"></div>
  <div id="tab2" class="tab-content" style="display:none;"></div>
  <div id="tab3" class="tab-content default-tab"><?php $this->load->view('engineers/tab_engineers');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
