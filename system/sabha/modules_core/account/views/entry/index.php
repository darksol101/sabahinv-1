<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); 
?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php
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
?></h3>
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
<?php $this->load->view('dashboard/system_messages');?>
<?php $this->load->view('account/sub_menu');?>

<table border=0 cellpadding=5 class="simple-table tblgrid" width="100%">
	<thead>
		<tr>
			<th>Date</th>
			<th>No</th>
			<th>Ledger Account</th>
			<th>Type</th>
			<th>DR Amount</th>
			<th>CR Amount</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i=1;
		foreach ($entry_data->result() as $row)
		{
			$trstyle=($i%2==0)?' class="even"':' class="odd"';
			$current_entry_type = entry_type_info($row->entry_type);

			echo "<tr$trstyle>";

			echo "<td>" . date_mysql_to_php_display($row->date) . "</td>";
			echo "<td>" . anchor('account/entry/view/' . $current_entry_type['label'] . "/" . $row->id, full_entry_number($row->entry_type, $row->number), array('title' => 'View ' . $current_entry_type['name'] . ' Entry', 'class' => 'anchor-link-a')) . "</td>";

			echo "<td>";
			echo $this->Tag_model->show_entry_tag($row->tag_id);
			echo $this->Ledger_model->get_entry_name($row->id, $row->entry_type);
			echo "</td>";

			echo "<td>" . $current_entry_type['name'] . "</td>";
			echo "<td>" . $row->dr_total . "</td>";
			echo "<td>" . $row->cr_total . "</td>";

			echo "<td style=\"text-align:center\">" . anchor('account/entry/edit/' . $current_entry_type['label'] . "/" . $row->id , img(array('src' => asset_url() . "style/img/icons/edit.png", 'border' => '0', 'alt' => 'Edit ' . $current_entry_type['name'] . ' Entry')), array('title' => 'Edit ' . $current_entry_type['name'] . ' Entry', 'class' => 'red-link')) . " ";
			echo " &nbsp;" . anchor('account/entry/delete/' . $current_entry_type['label'] . "/" . $row->id , img(array('src' => asset_url() . "style/img/icons/delete_1.png", 'border' => '0', 'alt' => 'Delete ' . $current_entry_type['name'] . ' Entry', 'class' => "confirmClick", 'title' => "Delete entry")), array('title' => 'Delete  ' . $current_entry_type['name'] . ' Entry')) . " ";
			echo " &nbsp;" . anchor_popup('account/entry/printpreview/' . $current_entry_type['label'] . "/" . $row->id , img(array('src' => asset_url() . "style/img/icons/printer_48.png", 'border' => '0', 'alt' => 'Print ' . $current_entry_type['name'] . ' Entry')), array('title' => 'Print ' . $current_entry_type['name']. ' Entry', 'width' => '600', 'height' => '600')) . " ";
			echo " &nbsp;" . anchor_popup('account/entry/email/' . $current_entry_type['label'] . "/" . $row->id , img(array('src' => asset_url() . "style/img/icons/mail.png", 'border' => '0', 'alt' => 'Email ' . $current_entry_type['name'] . ' Entry')), array('title' => 'Email ' . $current_entry_type['name'] . ' Entry', 'width' => '500', 'height' => '300')) . " ";
			echo " &nbsp;" . anchor('account/entry/download/' . $current_entry_type['label'] . "/" . $row->id , img(array('src' => asset_url() . "style/img/icons/download.png", 'border' => '0', 'alt' => 'Download ' . $current_entry_type['name'] . ' Entry', 'title' => "Download entry")), array('title' => 'Download  ' . $current_entry_type['name'] . ' Entry')) . "</td>";

			echo "</tr>";
			$i++;
		}
	?>
	</tbody>
</table>
<div id="pagination-container"><?php echo $this->pagination->create_links(); ?></div>
</div>
</div>
<?php $this->load->view('dashboard/footer');
