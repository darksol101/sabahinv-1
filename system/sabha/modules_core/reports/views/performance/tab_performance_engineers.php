<?php $this->load->view('dashboard/header', array("header_insert"=>"reports/performance/script", "title"=>$this->lang->line('performance_report_engineer'))); ?>
<?php $this->load->view('dashboard/jquery_date_picker'); ?>
<?php $this->load->view('reports/performance/tabs');?>
<div class="content-box-content">
  <div class="tab-content  default-tab">
	<?php $this->load->view('reports/performance/form1');?>
	<div id="performancereportslist"></div>
    </div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
