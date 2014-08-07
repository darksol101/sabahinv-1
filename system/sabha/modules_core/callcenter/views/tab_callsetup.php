<?php $this->load->view('dashboard/header', array("header_insert"=>"callcenter/script", "title"=>$this->lang->line('callsetup'))); ?>
<?php $this->load->view('products/script');?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('callsetup')?></h3>
  <ul class="content-box-tabs">
  	<li><a class="" href="#tab1" id="callcenter/calls"><?php echo $this->lang->line('calls')?></a></li>
    <li><a class="default-tab"  href="#tab2" id="callcenter/callregistration"><?php echo $this->lang->line('callsetup')?></a></li>
  </ul>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content"></div>
  <div id="tab2" class="tab-contentdefault-tab">
  	<?php $this->load->view('callregistration');?>
  </div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
