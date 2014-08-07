<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); ?>

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
<script type="text/javascript">
$(document).ready(function() {
	/* Show and Hide affects_gross */
	$('.group-parent').change(function() {
		if ($(this).val() == "3" || $(this).val() == "4") {
			$('.affects-gross').show();
		} else {
			$('.affects-gross').hide();
		}
	});
	$('.group-parent').trigger('change');
});
</script>
<div style="width:50%">
<?php
	echo form_open('account/group/add');

	echo "<p>";
	echo form_label('Group name', 'group_name');
	echo "<br />";
	echo form_input($group_name);
	echo "</p>";

	echo "<p>";
	echo form_label('Parent group', 'group_parent');
	echo "<br />";
	echo form_dropdown('group_parent', $group_parent, $group_parent_active, "class = \"group-parent\"");
	echo "</p>";

	echo "<p class=\"affects-gross\">";
	echo "<span id=\"tooltip-target-1\">";
	echo form_checkbox('affects_gross', 1, $affects_gross) . " Affects Gross Profit/Loss Calculations";
	echo "</span>";
	echo "<span id=\"tooltip-content-1\">If selected the Group account will affect Gross Profit and Loss calculations, otherwise it will affect only Net Profit and Loss calculations.</span>";
	echo "</p>";

	echo "<p>";
	echo form_hidden('sc_id', $this->session->userdata('sc_id'));
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