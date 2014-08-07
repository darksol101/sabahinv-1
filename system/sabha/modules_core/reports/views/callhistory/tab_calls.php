<?php $this->load->view('dashboard/header', array("header_insert"=>"reports/callhistory/script_calls", "title"=>$this->lang->line('calls'))); ?>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('calls')?></h3>
  <ul class="content-box-tabs">
  	<li><a class="default-tab" href="#tab1" id="callhistory/calls"><?php echo $this->lang->line('calls')?></a></li>
     </ul>
</div>
<div class="content-box-content">
  <div id="tab1" class="tab-content default-tab"><?php $this->load->view('reports/callhistory/calls');?></div>
  </div>
<?php $this->load->view('dashboard/footer'); ?>
