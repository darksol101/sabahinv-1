<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!isset($print_preview)){
$this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); ?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('trial_balance');?></h3>
<ul class="content-box-tabs">
	<li><a class="" href="#tab1" id="account/report/balancesheet"><?php echo $this->lang->line('balance_sheet')?></a></li>
	<li><a class="" href="#tab2" id="account/report/profitandloss"><?php echo $this->lang->line('profit_and_loss_account')?></a></li>
	<li><a class="default-tab" href="#tab4" id="account/report/trialbalance"><?php echo $this->lang->line('trial_balance')?></a></li>
	<li><a class="" href="#tab4" id="account/report/ledgerst"><?php echo $this->lang->line('ledger_statement')?></a></li>
        <li><a href="#tab4" id="account/report/reconciliation/pending"><?php echo $this->lang->line('reconciliation')?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab">
<?php } ?>
<?php $this->load->view('dashboard/system_messages');?>
<?php $this->load->view('account/sub_menu');?>
<?php
	$temp_dr_total = 0;
	$temp_cr_total = 0;

	echo "<table border=0 cellpadding=5 class=\"simple-table trial-balance-table tblgrid\" width=\"100%\">";
	echo "<thead><tr><th>Ledger Account</th><th>O/P Balance</th><th>C/L Balance</th><th>Dr Total</th><th>Cr Total</th></tr></thead>";
	$this->load->model('Ledger_model');
	$all_ledgers = $this->Ledger_model->get_all_ledgers();
	$odd_even = "odd";
	foreach ($all_ledgers as $ledger_id => $ledger_name)
	{
		if ($ledger_id == 0) continue;
		echo "<tr class=\"tr-" . $odd_even . ' '. $odd_even."\">";

		echo "<td>";
		echo  anchor('account/report/ledgerst/' . $ledger_id, $ledger_name, array('title' => $ledger_name . ' Ledger Statement', 'class' => 'anchor-link-a'));
		echo "</td>";

		echo "<td>";
		list ($opbal_amount, $opbal_type) = $this->Ledger_model->get_op_balance($ledger_id);
		echo convert_opening($opbal_amount, $opbal_type);
		echo "</td>";

		echo "<td>";
		$clbal_amount = $this->Ledger_model->get_ledger_balance($ledger_id);
		echo convert_amount_dc($clbal_amount);
		echo "</td>";

		echo "<td>";
		$dr_total = $this->Ledger_model->get_dr_total($ledger_id);
		if ($dr_total)
		{
			echo $dr_total;
			$temp_dr_total = float_ops($temp_dr_total, $dr_total, '+');
		} else {
			echo "0";
		}
		echo "</td>";
		echo "<td>";
		$cr_total = $this->Ledger_model->get_cr_total($ledger_id);
		if ($cr_total)
		{
			echo $cr_total;
			$temp_cr_total = float_ops($temp_cr_total, $cr_total, '+');
		} else {
			echo "0";
		}
		echo "</td>";
		echo "</tr>";
		$odd_even = ($odd_even == "odd") ? "even" : "odd";
	}
	echo "<tr class=\"tr-total\"><td colspan=3>TOTAL ";
	if (float_ops($temp_dr_total, $temp_cr_total, '=='))
		echo "<img src=\"" . asset_url() . "style/img/icons/match.png\">";
	else
		echo "<img src=\"" . asset_url() . "style/img/icons/nomatch.png\">";
	echo "</td><td>Dr " . convert_cur($temp_dr_total) . "</td><td>Cr " . convert_cur($temp_cr_total) . "</td></tr>";
	echo "</table>";
	if(!isset($print_preview)){
?>
</div>
</div>
<?php $this->load->view('dashboard/footer'); 
	}