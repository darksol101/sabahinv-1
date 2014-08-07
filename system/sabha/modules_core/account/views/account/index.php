<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); ?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('chart_accounts');?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="account"><?php echo $this->lang->line('chart_accounts')?></a></li>
	<li><a class="" href="#tab1" id="account/entry/show/journal"><?php echo $this->lang->line('journal')?></a></li>
	<li><a class="" href="#tab1" id="account/entry/show/receipt"><?php echo $this->lang->line('receipt')?></a></li>
	<li><a class="" href="#tab1" id="account/entry/show/payment"><?php echo $this->lang->line('payment')?></a></li>
        <li><a class="" href="#tab1" id="account/entry/show/contra"><?php echo $this->lang->line('contra')?></a></li>
        
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab">
<?php $this->load->view('dashboard/system_messages');?>
<?php echo $this->load->view('account/sub_menu');?>
<?php
	$this->load->library('accountlist');
	echo "<table width=\"100%\">";
	echo "<tr valign=\"top\">";
	$asset = new Accountlist();
	echo "<td>";
	$asset->init(0);
	echo "<table border=0 cellpadding=5 class=\"simple-table account-table tblgrid\" width=\"100%\">";
	echo "<thead><tr><th>Account Name</th><th>Type</th><th>O/P Balance</th><th>C/L Balance</th><th></th></tr></thead>";
	$asset->account_st_main(-1);
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
?>	
</div>
</div>
<?php $this->load->view('dashboard/footer');

