<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showPurchaseList();
	})
});
</script>
<table style="width: 100%" class="tblgrid" cellpadding="0" cellspacing="0">
	<col width="1%" /><col /><col width="10%" /><col width="20%" /><col width="20%"><col width="10%" /><col width="5%" /><col width="5%" />
	<thead>
		<tr>
			<th style="text-align: center;"><label><?php echo $this->lang->line('s_no');?></label></th>
			<th style="text-align: left;"><label><?php echo $this->lang->line('purchase_number');?></label></th>
			<th style="text-align: center;"><label><?php echo $this->lang->line('purchase_date');?></label></th>
			<th style="text-align: center;"><label><?php echo $this->lang->line('vendor_name');?></label></th>
			<th style="text-align: center"><label><?php echo $this->lang->line('sc_name');?></label></th>
            <th style="text-align: center;"><label><?php echo $this->lang->line('deliver_status');?></label></th>
			<th></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($purchases as $purchase){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td align="center"><?php echo $i+$page['start'];?></td>
			<td style="text-align:left;"><a><?php echo $purchase->purchase_number;?></a></td>
			<td style="text-align: center;"><?php echo format_date(strtotime($purchase->purchase_date));?></td>
            <td style="text-align: center;"><?php echo $purchase->vendor_name;?></td>
            <td style="text-align: center;"><?php echo $purchase->sc_name;?></td>
			<td style="text-align: center;">
			<?php
            echo $this->mdl_mcb_data->getStatusDetails($purchase->purchase_status,'purchase_status');
			?></td>
			
			<td><a class="editlist" href="<?php echo base_url();?>purchase/editrecord/<?php echo $purchase->purchase_id?>"><?php echo icon('edit','edit','png');?></a></td>
            <td><?php if($purchase->purchase_status<1){?><a class="btn"	onclick="deletepurchase('<?php echo $purchase->purchase_id;?>')"><?php echo icon("delete","Delete","png");?></a><?php }else{?> <?php echo icon("delete_disable","Delete","png");?> <?php }?></td>
		</tr>
		<?php
		$i++;
	}
	?>
	</tbody>
    <tfoot>
		<tr>
			<td colspan="6">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>
