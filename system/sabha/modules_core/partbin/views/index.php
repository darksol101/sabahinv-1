<?php $this->load->view('dashboard/header', array("header_insert"=>"partbin/script", "title"=>$this->lang->line('bin_management'))); ?>

<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('bin_management')?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab1" id="partbin"><?php echo $this->lang->line('bin_management')?></a></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab">
    <?php $this->load->view('partbin/tab_partbin');?>
  </div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
