<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showBills();
	})
});
</script>
<table style="width: 100%" class="tblgrid" cellpadding="0" cellspacing="0">
	<col width="1%" />
	<col width="15%"/>
	<col width="15%"/>
	<col width="15%"/>
	<!-- <col width="10%"/> -->
	<col width="15%" />
	<col width="20%" />
	
	<thead>
		<tr>
			<th style="text-align: center;"><label><?php echo $this->lang->line('s.no.');?></label></th>
			<th style="text-align: left;"><label><?php echo $this->lang->line('sales_date');?></label></th>
			<th style="text-align: left;"><label><?php echo $this->lang->line('bill_number');?></label></th>
			<th style="text-align: left;"><label><?php echo $this->lang->line('customer_name');?></label></th>
		<!-- 	<th style="text-align: center;"><label><?php //echo $this->lang->line('cust_vat');?></label></th> -->
			<th style="text-align: right;"><label><?php echo $this->lang->line('grand_total');?></label></th>
			<th style="text-align: left"><label><?php echo $this->lang->line('sc_name');?></label></th>
            <th style="text-align: center;"><label><?php echo $this->lang->line('status');?></label></th>
			<th></th>           
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($bills as $bill){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td align="center"><?php echo $i+$page['start'];?></td>
			<td style="text-align: left;"><?php echo format_date(strtotime($bill->bill_sale_date));?></td>
			<td style="text-align: left;"><a href="<?php echo site_url();?>bills/view/<?php echo $bill->bill_id;?>"><?php echo $bill->sc_code.($bill->bill_type==1?'SI':'TI').'/'.$bill->bill_number;?></a></td>
			<td style="text-align: left;"><?php echo $bill->bill_customer_name;?></td>
			<!-- <td style="text-align: center;"><?php //echo $bill->bill_customer_vat;?></td> -->
			<td style="text-align: right;"><?php echo $bill->bill_rounded_grand_total_price;?></td>
            <td style="text-align: left;"><?php echo $bill->sc_name;?></td>
			<td style="text-align: center;">
			<?php
            echo $this->mdl_mcb_data->getStatusDetails($bill->bill_status,'bill_status');
			?></td>			
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
