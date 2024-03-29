<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); 
?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php $this->lang->line('chart_accounts');?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="account"><?php echo $this->lang->line('chart_accounts')?></a></li>
	<li><a class="" href="#tab1" id="account/entry/show/journal"><?php echo $this->lang->line('journal')?></a></li>
	<li><a class="" href="#tab1" id="account/entry/show/receipt"><?php echo $this->lang->line('receipt')?></a></li>
	<li><a class="" href="#tab1" id="account/entry/show/payment"><?php echo $this->lang->line('payment')?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab">
 <?php $this->load->view('dashboard/system_messages');?>
<div style="width:50%">
<?php
	echo form_open('account/ledger/add');

	echo "<p>";
	echo form_label('Ledger name', 'ledger_name');
	echo "<br />";
	echo form_input($ledger_name);
	echo "</p>";

	echo "<p>";
	echo form_label('Parent group', 'ledger_group_id');
	echo "<br />";
	echo form_dropdown('ledger_group_id', $ledger_group_id, $ledger_group_active);
	echo "</p>";

	echo "<p>";
	echo form_label('Opening balance', 'op_balance');
	echo "<br />";
	echo "<span id=\"tooltip-target-1\">";
	echo form_dropdown_dc('op_balance_dc', $op_balance_dc);
	echo " ";
	echo form_input($op_balance);
	echo "</span>";
	echo "<span id=\"tooltip-content-1\">&nbsp;&nbsp;Assets / Expenses => Dr. Balance<br />Liabilities / Incomes => Cr. Balance</span>";
	echo "</p>";

	echo "<p>";
	echo "<span id=\"tooltip-target-2\">";
	echo form_checkbox('ledger_type_cashbank', 1, $ledger_type_cashbank) . " Bank or Cash Account";
	echo "</span>";
	echo "<span id=\"tooltip-content-2\">Select if Ledger account is a Bank account or a Cash account.</span>";
	echo "</p>";

	echo "<p>";
	echo "<span id=\"tooltip-target-3\">";
	echo form_checkbox('reconciliation', 1, $reconciliation) . " Reconciliation";
	echo "</span>";
	echo "<span id=\"tooltip-content-3\">If enabled account can be reconciled from Reports > Reconciliation</span>";
	echo "</p>";

	echo "<p>";
	echo form_submit('submit', 'Create','class="button"');
	echo " ";
	echo anchor('account', 'Back', 'Back to Chart of Accounts');
	echo "</p>";
	
	echo form_close();
	?>
      </div>  
	</div>
</div>
<?php $this->load->view('dashboard/footer');

