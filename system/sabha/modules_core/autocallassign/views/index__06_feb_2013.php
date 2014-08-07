<?php $this->load->view('dashboard/header', array("header_insert"=>"autocallassign/script", "title"=>$this->lang->line('auto_assignment'))); ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.ui.accordion.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/jquery.ui.accordion.min.js" type="text/javascript"></script>
<script>
$(function() {
		$( "#accordion" ).accordion({
		autoHeight: false,
		collapsible: true							
		});
	});
</script>
<div class="content-box-header">
  <h3 id="moduletitle" style="cursor: s-resize;"><?php echo $this->lang->line('auto_assignment')?></h3>
  <ul class="content-box-tabs">
    <li><a href="#tab1" id="servicecenters">Store</a></li>
    <li><a class="default-tab" href="#tab2" id="autocallassign">Auto Call Assignment</a></li>
    <li><a href="#tab3" id="cities">City</a></li>
  </ul>
</div>
<div class="content-box-content">
    <div id="tab1" class="tab-content"></div>
    <div id="tab2" class="tab-content default-tab"><?php $this->load->view('autocallassign/tab_autocallassign');?></div>
    <div id="tab3" class="tab-content"></div>
</div>
<div style="clear:both"></div>
<?php $this->load->view('dashboard/footer'); ?>
