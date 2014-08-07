<?php $this->load->view('dashboard/header', array("header_insert"=>"vendors/script", "title"=>$this->lang->line('vendors'))); ?>

<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('vendors')?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab1" id="vendors"><?php echo $this->lang->line('vendors')?></a></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab">
    <?php $this->load->view('vendors/tab_vendors');?>
  </div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
