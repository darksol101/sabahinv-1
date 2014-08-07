<?php $this->load->view('dashboard/header', array("header_insert"=>"company/script", "title"=>$this->lang->line('Company'))); ?>

<div class="content-box-header">
  <h3 style="cursor: s-resize;"><?php echo $this->lang->line('company')?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab" href="#tab1" id="company"><?php echo $this->lang->line('company')?></a></li>
  </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab">
    <?php $this->load->view('company/tab_company');?>
  </div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
