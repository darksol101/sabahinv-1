<?php $this->load->view('dashboard/header', array("header_insert"=>"salesmaker/script", "title"=>$this->lang->line('salesmaker'))); ?>

<div class="content-box-header">
  <h3><?php echo $this->lang->line('salesmaker')?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab"href="#tab1" id="salesmaker1"><?php echo $this->lang->line('salesmaker')?></a></li>

  </ul>

  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab"><?php $this->load->view('salesmaker/tab_salesmaker');?></div>

   
</div>
<?php $this->load->view('dashboard/footer'); ?>

