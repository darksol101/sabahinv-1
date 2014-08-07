<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>'Bills')); ?>
<div class="content-box-header">
<h3 style="cursor: s-resize;">Bills</h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="account">Bills</a></li>
	
        
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab">
<?php $this->load->view('dashboard/system_messages');?>
<strong>Module under Construction.</strong>
</div>
</div>
<?php $this->load->view('dashboard/footer');

