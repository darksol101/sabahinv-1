<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<form onsubmit="return false">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col width="10%" /><col /><col width="20%"><col width="20%" /><col width="7%" /><col width="7%" />
	<thead>
    	<tr>
        	<th><?php echo $this->lang->line('sn');?></th>
            <th><?php echo $this->lang->line('stock_date');?></th>
            <th><?php echo $this->lang->line('part_number');?></th>
             <th><?php echo "Event";?></th>
            
            <th style="text-align:center"><?php echo $this->lang->line('sc_name');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('stock_in');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('stock_out');?></th>           
        </tr>
    </thead>
    <tbody>
	<?php
	$i=1;
	$total_stock_in=0;
	$total_stock_out=0;
	foreach($stocklist_details as $stockdetail){
		$total_stock_in+=$stockdetail->stock_quantity_in;
		$total_stock_out+=$stockdetail->stock_quantity_out;
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		$stock_url = '';
		if($stockdetail->event=='purchase'){
			
			$detail = $this->mdl_purchase_details->getPurchaseByDetailId($stockdetail->event_id);
			$stock_url=base_url().'purchase/editrecord/'.$detail->purchase_id;
		}
		if ($stockdetail->event=='order'){
			$detail = $this->mdl_order_parts->getOrderByDetailId($stockdetail->event_id);
			$stock_url=base_url().'orders/editorder/'.$detail->order_id;
		}
		else
		{
			$stock_url='';
			}
		?>
		<tr<?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
            <td><?php echo format_date(strtotime($stockdetail->stock_dt));?></td>
			<td>
            <a target="_blank" href="<?php echo $stock_url;?>"><?php echo $stockdetail->part_number;?></a></td>
            
            <td><?php echo ucwords(str_replace('_', ' ',$stockdetail->event));?></a></td>
			<td style="text-align:center"><?php echo $stockdetail->sc_name;?></td>
            <td style="text-align:center"><?php echo $stockdetail->stock_quantity_in;?></td>
            <td style="text-align:center"><?php echo $stockdetail->stock_quantity_out;?></td>
		</tr>
	<?php $i++;}?>
    </tbody>
    <tfoot>
    	<tr>
        	<td colspan="5" align="right" style="text-align:right!important"><strong><?php echo $this->lang->line('total');?></strong></td>
            <td><strong><?php echo $total_stock_in;?></strong></td>
            <td><strong><?php echo $total_stock_out;?></strong></td>
        </tr>
        <tr>
        	<td colspan="6" align="right" style="text-align:right!important"><strong><?php echo $this->lang->line('balance');?></strong></td>
            <td><strong><?php echo ($total_stock_in-$total_stock_out);?></strong></td>
        </tr>
    </tfoot>
</table>
</form>