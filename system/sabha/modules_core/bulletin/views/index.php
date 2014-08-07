<?php $this->load->view('dashboard/header', array("header_insert"=>"bulletin/script_bulletin", "title"=>$this->lang->line('bulletin'))); ?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('bulletin');?></h3>
  <ul class="content-box-tabs">
    <li><a class="default-tab"  href="#tab1" id="bulletin"><?php echo $this->lang->line('bulletin');?></a></li>
    </ul>
  <div class="clear"></div>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content  default-tab"><?php $this->load->view('bulletin/tab_bulletin');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
