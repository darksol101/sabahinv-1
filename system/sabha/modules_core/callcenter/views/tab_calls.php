<?php $this->load->view('dashboard/header', array("header_insert"=>"callcenter/script_calls", "title"=>$this->lang->line('calls'))); ?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('calls')?></h3>
  <ul class="content-box-tabs">
  	<li><a class="default-tab" href="#tab1" id="callcenter/calls"><?php echo $this->lang->line('calls')?></a></li>
    <li><a class=""  href="#tab2" id="callcenter/callregistration"><?php echo $this->lang->line('callsetup')?></a></li>
  </ul>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab"><?php $this->load->view('callcenter/calls');?></div>
  <div id="tab2" class="tab-content"></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
