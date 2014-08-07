<?php $this->load->view('dashboard/header', array("header_insert"=>"callreport/script", "title"=>$this->lang->line('call_report'))); ?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('call_report');?></h3>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab"><?php $this->load->view('callreport/tab_callreport');?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
