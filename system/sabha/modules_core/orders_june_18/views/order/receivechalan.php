<div style="width:800px;">
<style> .text-input {font-size:11px;background:#fff;border:1px solid #ddd;color:#333;width:50%;padding:2px 6px; border-radius:4px;}
</style>
<?php 
$order_parts = $this->mdl_order_parts->ordersparts($transit_details->order_id);
?>
<table style="width:100%;">
<tr> 
<td> <label> Chalan Number </label></td>
<td>  <input type="input" id="courior_number" readonly="readonly" name="courior_number" class="text-input" value="<?php echo $transit_details->chalan_number;?>" /></td>

</tr>
<tr> 
<td> <label> Courior Number </label></td>
<td>  <input type="input" id="courior_number" readonly="readonly" name="courior_number" class="text-input" value="<?php echo $transit_details->courior_number;?>" />  </td>

</tr>
<tr> 
<td> <label> Number of Boxes </label></td>
<td>  <input type="input" id="box_number"  class="text-input" readonly="readonly" name="box_number" value="<?php echo $transit_details->box_number;?>"/>  </td>
</tr>
<tr> 
<td> <label> Vehicle Number </label></td>
<td>  <input type="input" id="vehicle_number" name="vehicle_number" readonly="readonly" value="<?php echo $transit_details->vehicle_number;?>" class="text-input" />  </td>
</tr>
<tr> 
<td> <label> Transit Number </label></td>
<td>  <input type="input" id="transit_number" name="transit_number" readonly="readonly" value="<?php echo $transit_details->transit_number;?>" class="text-input" />  </td>
</tr>
<tr> 
<td> <label> Courior Date </label></td>
<td>   <input id="couriordate" name="couriordate"  readonly="readonly" class=" text-input" type="text"  value="<?php echo $transit_details->courior_date;?>">  </td>
</tr>
<tr>
</tr>
</table>
<form onsubmit="return false" name="">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid" style="margin-top:10px">
<col width="1%" />
<col width="10%" />
<col width="5%" />
<col width="50%" />
<col width="5%" />
<col width="10%" />
<col width="5%" />
<col width="5%" />
<col width="5%" />
<col width="10%" />
<thead>
	<tr>
    	<th style="text-align:center">S.No.</th>
        <th style="text-align:left">Item Number</th>
        <th style="text-align:left">&nbsp;</th>
        <th style="text-align:left" >Company</th>
        <th style="text-align:center">Required Qty</th>
        <th style="text-align:center">Delivered Qty</th>
        <th style="text-align:center">Total Dispatched Qty</th>
        <th style="text-align:center">Dispatched Qty</th>
        <th style="text-align:center">Differance Qty</th>
        <th style="text-align:center">&nbsp;</th>
        <th></th>
    </tr>
</thead>
<tbody>
<?php $i=0;

 foreach ($order_parts as $orderpart){
$trstyle=$i%2==0?" class='even' ": " class='odd' ";
$i = $i+1;
	$order_parts_details = $this->mdl_order_parts_details->getPartOderDetailsByOrderParts($orderpart->order_part_id);
	$dispatched_quantity = $this->mdl_order_parts_details->getDispatchedQuantity($orderpart->order_part_id);
	$delivered_quantity = $this->mdl_order_parts_details->getDeliveredQuantity($orderpart->order_part_id);
	$chalan_dispatched_quantity = $this->mdl_order_parts_details->getDispatchedPartByChalan($orderpart->order_part_id,$transit_details->transit_detail_id);
	$chalan_received_quantity = $this->mdl_order_parts_details->getReceivedQuantityByChalan($orderpart->order_part_id,$transit_details->transit_detail_id);
	$order_part_detail_id = $this->mdl_order_parts_details->orderPartDetailId($orderpart->order_part_id,$transit_details->transit_detail_id);
	$chalandifferance = $this->mdl_order_parts_details->getDifferance($orderpart->order_part_id,$transit_details->transit_detail_id);
	//echo '<pre>'; print_r($chalandifferance);
	//print_r($chalan_received_quantity);
?> 
<tr>
<td> <?php echo $i;?></td>
<td  style="text-align:center"><input type="hidden" id="order_part_number" value="<?php echo $orderpart->order_part_id;?>" /><?php echo $orderpart->part_number;?></td>
<td ><input type="hidden" name="hdn_part_number" value="<?php echo $orderpart->part_number;?>"/><?php // echo $orderpart->part_description;?> </td>
<td ><input type="hidden" name="hdn_company_id" value="<?php echo $orderpart->company_id;?>"/><?php echo $orderpart->company_title;?> </td>
<td  style="text-align:center"><input type="hidden" name="remaining_quantity" value="<?php echo ($orderpart->part_quantity - ($delivered_quantity->delivered_quantity + $dispatched_quantity->dispatched_quantity )) ?>" /><?php echo $orderpart->part_quantity;?> </td>

<td  style="text-align:center"> <?php echo $delivered_quantity->delivered_quantity;?></td>
<td style="text-align:center"> <input type="hidden" value="<?php echo $chalan_dispatched_quantity->part_quantity-$chalandifferance->differance; ?>"/><?php echo $dispatched_quantity->dispatched_quantity; ?> </td>

<td style="text-align:center"><input type="hidden" value="<?php echo $order_part_detail_id->order_part_details_id;?>"/><?php echo $chalan_dispatched_quantity->part_quantity; ?> </td>

<td style="text-align:center"><?php echo $chalan_dispatched_quantity->part_quantity - $chalan_received_quantity->part_quantity.'('.$chalan_received_quantity->part_quantity.')'; ?> </td>

<td style="text-align:center"><?php if ( $chalan_dispatched_quantity->part_quantity - $chalan_received_quantity->part_quantity > 0){?><input type="text" class="validate[custom[integer]] text-input" value="<?php  echo $chalan_dispatched_quantity->part_quantity - $chalan_received_quantity->part_quantity; ?>" id="sentquantity" style="width:40px"/> <?php  }?></td>
<td style="text-align:center"><?php if ( $chalan_dispatched_quantity->part_quantity - $chalan_received_quantity->part_quantity > 0){?><a title="Confirm" class="btn receive_partial_part">Receive</a><?php  }?></td>
</tr>
<?php  }?>
</tbody>
</table>
<input type="hidden" id="hdn_requested_sc_id" value="<?php echo $transit_details->requested_sc_id;?>" />
<input type="hidden" id="hdn_requesting_sc_id" value="<?php echo $transit_details->requesting_sc_id?>"/>
<input type="hidden" id="hdn_order_id" value=" <?php echo $transit_details->order_id;?>" />
</form>
</div>

<table align="right" style="margin-top:11px">
	<tr>
		<td style="text-align: right; font-size: 11px;"><input type="button"
			 value="Print Chalan" class="button"
			onclick="partialordercard(<?php echo $transit_details->transit_detail_id?> ,<?php echo $transit_details->order_id?>);" /></td>
		<td style="text-align: right; font-size: 11px;"><input type="button"
			name="cancel_card" id="cancel_card" value="Cancel" class="button"
			onclick="javascript:$(document).trigger('close.facebox');" /></td>
	</tr>
</table>
