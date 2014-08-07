<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); ?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script type="text/javascript">
function email_pop(){
	$.facebox(function() { 
	  $.get('<?php echo site_url();?>account/entry/email/<?php echo $current_entry_type['label'];?>/<?php echo $this->uri->segment(5);?>', {unq:ajaxunq()}, 
		function(data) { $.facebox(data) });
    });
}
function loading(selector)
{
	$("#"+selector).append('<span class="loading"><?php echo icon('loading','loading','gif'); ?></span>');
}
function sendemail(){
	loading("loading1");
	var email_to = $("#email_to").val();
	
	var params = 'email_to='+email_to+'&unq='+ajaxunq();
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>account/entry/email/<?php echo $current_entry_type['label'];?>/<?php echo $this->uri->segment(5);?>",
			data	:	params,
			success	:	function (data){
					hideloading(data);
					$(document).trigger('close.facebox');
				}								
		});//end  ajax
}
</script>
<div class="content-box-header">
<h3 style="cursor: s-resize;">
<?php
	if($entry_type_id==4){
		echo $this->lang->line('journal');
	}elseif($entry_type_id==1){
		echo $this->lang->line('receipt');
	}
	elseif($entry_type_id==2){
		echo $this->lang->line('payment');
	}
	elseif($entry_type_id==3){
		echo $this->lang->line('contra');
	}
	else{
		echo $this->lang->line('chart_accounts');
	}
?>
</h3>
<ul class="content-box-tabs">
	<li><a class="" href="#tab1" id="account"><?php echo $this->lang->line('chart_accounts')?></a></li>
	<li><a class="<?php echo ($entry_type_id==4)?'default-tab':'';?>" href="#tab1" id="account/entry/show/journal"><?php echo $this->lang->line('journal');?></a></li>
	<li><a class="<?php echo ($entry_type_id==1)?'default-tab':'';?>" href="#tab1" id="account/entry/show/receipt"><?php echo $this->lang->line('receipt');?></a></li>
	<li><a class="<?php echo ($entry_type_id==2)?'default-tab':'';?>" href="#tab1" id="account/entry/show/payment"><?php echo $this->lang->line('payment');?></a></li>
        <li><a class="<?php echo ($entry_type_id==3)?'default-tab':'';?>" href="#tab1" id="account/entry/show/contra"><?php echo $this->lang->line('contra');?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab">

<p>Entry Number : <span class="bold"><?php echo full_entry_number($entry_type_id, $cur_entry->number); ?></span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Entry Date : <span class="bold"><?php echo date_mysql_to_php_display($cur_entry->date); ?></span>
</p>

<table border=0 cellpadding=5 class="simple-table entry-view-table tblgrid" width="100%">
<thead><tr><th>Type</th><th>Ledger Account</th><th>Dr Amount</th><th>Cr Amount</th></tr></thead>
<?php
$odd_even = "odd";
foreach ($cur_entry_ledgers->result() as $row)
{
	echo "<tr class=\"tr-" . $odd_even . " ".$odd_even."\">";
	echo "<td>" . convert_dc($row->dc) . "</td>";
	echo "<td>" . $this->Ledger_model->get_name($row->ledger_id) . "</td>";
	if ($row->dc == "D")
	{
		echo "<td>Dr " . $row->amount . "</td>";
		echo "<td></td>";
	} else {
		echo "<td></td>";
		echo "<td>Cr " . $row->amount . "</td>";
	}
	echo "</tr>";
	$odd_even = ($odd_even == "odd") ? "even" : "odd";
}
?>
<tr class="entry-total"><td colspan=2><strong>Total</strong></td><td id=dr-total>Dr <?php echo $cur_entry->dr_total; ?></td><td id="cr-total">Cr <?php echo $cur_entry->cr_total; ?></td></tr>
<?php
if ($cur_entry->dr_total != $cur_entry->cr_total)
{
	$difference = $cur_entry->dr_total - $cur_entry->cr_total;
	if ($difference < 0)
		echo "<tr class=\"entry-difference\"><td colspan=2><strong>Difference</strong></td><td id=\"dr-diff\"></td><td id=\"cr-diff\">" . $cur_entry->cr_total . "</td></tr>";
	else
		echo "<tr class=\"entry-difference\"><td colspan=2><strong>Difference</strong></td><td id=\"dr-diff\">" .  $cur_entry->dr_total .  "</td><td id=\"cr-diff\"></td></tr>";
}
?>
</table>
<p>Narration :<br />
<span class="bold"><?php echo $cur_entry->narration; ?></span>
</p>
<p>
Tag : 
<?php
$cur_entry_tag = $this->Tag_model->show_entry_tag($cur_entry->tag_id);
if ($cur_entry_tag == "")
	echo "(None)";
else
	echo $cur_entry_tag;
?>
</p>
<div class="bottom-btn">
<?php 
	echo '<table class="bottom-tool-bar">';
	echo '<tr>';
	echo '<td>';
	echo anchor('account/entry/show/' . $current_entry_type['label'], 'Back', array('title' => 'Back to ' .  $current_entry_type['name'] . ' Entries'));
	echo '</td>';
	//echo " | ";
	echo '<td>';
	echo anchor('account/entry/edit/' .  $current_entry_type['label'] . "/" . $cur_entry->id, icon('edit','Edit','png'), array('title' => 'Edit ' . $current_entry_type['name'] . ' Entry'));
	echo '</td>';
	//echo " | ";
	echo '<td>';
	echo anchor('account/entry/delete/' . $current_entry_type['label'] . "/" . $cur_entry->id, icon('delete_1','Delete','png'), array('class' => "confirmClick", 'title' => "Delete entry", 'title' => 'Delete this ' . $current_entry_type['name'] . ' Entry'));
	echo '</td>';
	//echo " | ";
	echo '<td>';
	echo '<a onclick="printPopUp('."'account/entry/printpreview/".$current_entry_type['label'] . "/" .$cur_entry->id."'".')" class="btn">'.icon('printer_48','Print','png').'</a>';
	//echo anchor_popup('account/entry/printpreview/' .  $current_entry_type['label'] . "/" . $cur_entry->id, 'Print', array('title' => 'Print this ' . $current_entry_type['name'] . ' Entry', 'width' => '600', 'height' => '600'));
	echo '<td>';
	//echo " | ";
	echo '<td>';
	//echo anchor_popup('account/entry/email/' .  $current_entry_type['label'] . "/" . $cur_entry->id, icon('mail','Email','png'), array('title' => 'Email this ' . $current_entry_type['name'] . ' Entry', 'width' => '400', 'height' => '200'));
	echo '<a class="btn" onclick="email_pop()">'.icon('mail','Email','png').'</a>';
	echo '</td>';
	//echo " | ";
	echo '<td>';
	echo anchor('account/entry/download/' .  $current_entry_type['label'] . "/" . $cur_entry->id, icon('download','Download','png'), array('title' => "Download entry", 'title' => 'Download this ' . $current_entry_type['name'] . ' Entry'));
	echo '</td>';
	echo '</tr>';
	echo '</table>';
?>
<span id="loading1"></span>
<span class="message"><span class="message_text"></span></span>
</div>
</div>
</div>
<?php $this->load->view('dashboard/footer');