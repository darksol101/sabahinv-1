<?php defined('BASEPATH') or die('Direct access script in not allowed');?>
<style type="text/css">
#facebox a.close{visibility: hidden; display:none}
</style>
<script type="text/javascript">
$(document).ready(function(){
});
function CreatePartialDelivery(){
	var op_status = 'Quantity';
	var html = '';
	var trclass = '';
	html+= '<td></td>';
	html+='<td></td>';
	html+='<td></td>';
	
	html+='<td></td>';
	html+='<td style="text-align:right;">'+op_status+'</td>';
	html+='<td style="text-align:left;"><input type="text" name="request_quantity" value="" class="text-input request_quantity" /></td>';
	html='<tr class="'+trclass+'">'+html+'</tr>';
	$("#parts_orders").append(html);
}
</script>
<div style="width:750px;">
<form onsubmit="return false" name="">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col /><col width="5%" /><col width="5%" /><col width="5%" /><col width="15%" /><col width="10%" />
<thead>
	<tr>
    	<th style="text-align:center">S.No.</th>
        <th style="text-align:left">Item Number</th>
        <th style="text-align:center">Required Qty</th>
        <th style="text-align:center">Delivered Qty</th>
        <th style="text-align:center">Dispached Qty</th>
        <th style="text-align:center">Parts Status</th>
        <th></th>
    </tr>
</thead>
<tbody>
<?php
 $total_qty=0;
		$i=0;
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	 $order_status_list=$this->mdl_mcb_data->getStatusOptions('order_status');
	 $requesting_sc_id = $this->input->post('requesting_sc_id');
	 $requested_sc_id = $this->input->post('requested_sc_id');
	 $delivered_checkbox = '';
	 if($requesting_sc_id == $this->session->userdata('sc_id')){
		//unset($order_status_list[1]);
		//unset($order_status_list[3]);
		if($order_parts->order_part_status==0){
			unset($order_status_list[1]);
			unset($order_status_list[2]);
		}
		//if transit
		if($order_parts->order_part_status==1){
			unset($order_status_list[0]);
			//unset($order_status_list[2]);
			unset($order_status_list[3]);
			 //$order_status = $this->mdl_mcb_data->getStatusDetails($order_parts->order_part_status,'order_status');
		}
		if($order_parts->order_part_status==2){
			unset($order_status_list[0]);
			unset($order_status_list[1]);
			unset($order_status_list[3]);
		}
	 }
	 if($requested_sc_id == $this->session->userdata('sc_id')){
		if($order_parts->order_part_status==0){
			unset($order_status_list[2]);
			unset($order_status_list[3]);
		}
		if($order_parts->order_part_status==1){
			unset($order_status_list[2]);
			unset($order_status_list[3]);
		}
		if($order_parts->order_part_status==2){
			unset($order_status_list[0]);
			unset($order_status_list[1]);
			unset($order_status_list[3]);
		}
	 	//unset($order_status_list[2]);
	 }
	
	 	

	// $order_status=$this->mdl_html->genericlist( $order_status_list, 'order_part_status' ,array('class'=>'part_status'),'value','text',$order_parts->order_part_status);
	// if($order_parts->order_part_status==2 || $order_parts->order_part_status==3){
	$order_status = $this->mdl_mcb_data->getStatusDetails($order_parts->order_part_status,'order_status');
	 //}
	?>
	<tr<?php echo $trstyle;?>>
    	<td style="text-align:center"><?php echo ($i+1);?></td>
        <td style="text-align:left"><?php echo $order_parts->part_number;?><input type="hidden" name="p_part_number" value="<?php echo $order_parts->part_number;?>"  /><input type="hidden" name="p_order_part_id" value="<?php echo $order_parts->order_part_id;?>"  /></td>
        <td style="text-align:center"><span id="d_qty"><?php echo $order_parts->part_quantity;?></span></td>
        <td style="text-align:center"></td> 
        <td style="text-align:center"></td> 
        <td style="text-align:center"><?php echo $order_status;?></td>
        <td style="text-align:center"><?php if($this->input->post('requested_sc_id') == $this->session->userdata('sc_id') ){?><input type="button" name="" class="button" onclick="CreatePartialDelivery();" value="Create"  /><?php }?></td>
    </tr>
	</tbody>
</table>
<table width="100%" class="tblgrid" cellpadding="0" cellspacing="0">
<thead>
	<th>S.N.</th>
    <th style="text-align:center">Dispatched Qty</th>
    <th style="text-align:center">Delivered Qty</th>
    <th style="text-align:center">Delivery Status</th>
    <th style="text-align:center">Parts Status</th>
    <th></th>
</thead>
<tbody id="parts_orders">
    <?php
    if(count($order_parts_details)>0){
		$j=1;
		foreach($order_parts_details as $details){
		$order_part_status = $this->mdl_mcb_data->getStatusDetails($details->order_part_status,'order_status');
		$trstyle1=($j%2==0)?" class='even' ": " class='odd' ";
		$total_dispatched_qty = 0;
		?>
	<tr<?php echo $trstyle1;?>>
        <td style="text-align:center"><?php echo $j+1;?><input type="hidden" name="order_part_details_id" class="order_part_details_id" value="<?php echo $details->order_part_details_id;?>"  /></td>
        <td style="text-align:center">
		<?php $total_qty= $total_qty+$details->part_quantity?>
		<?php 
			$total_dispatched_qty+= $total_dispatched_qty;
			echo $details->part_quantity;
		
			
		?>
        <input type="hidden" name="dispatched_qty" class="dispatched_qty" value="<?php echo $details->part_quantity;?>"  />
        </td>
        <td><?php 
		if($details->order_part_status==2){
			echo $details->part_quantity;
		}
		?></td>
        <td style="text-align:center">
        <?php
        if($requesting_sc_id == $this->session->userdata('sc_id') && $details->order_part_status>=1){
			$c_status = ($details->order_part_status>=2)?TRUE:FALSE;
			$delivered_checkbox = form_checkbox(array('name'=>'delivered_checkbox','class'=>'delivered_checkbox'), 'accept', $c_status);
		}
		echo $delivered_checkbox;
		?>
        </td>
        <td style="text-align:center"><?php
		echo $order_part_status;
		?></td> 
         <td style="text-align:center"><input type="button" name="Delete" id="delete-transit" value="Delete"  onclick="deletetransaction();" class="button" /></td> 
    </tr>
	<?php $j++; }}?>
</tbody>
<div>Parts Order Details</div>
</table>
<?php //	echo $total_qty;
// die();?>

<div style="text-align:right; padding-top:5px;">
<input type="button" name="savedetails" id="savedetails" value="Save" onclick="saveOrderPartsDetails();" class="button" /><input type="button" value="Close" class="close_image button" onclick="javascript:$(document).trigger('close.facebox');"/>
</div>
<input type="hidden" name="pp_number" id="pp_number" value="<?php echo $order_parts->part_number;?>" />
<input type="hidden" name="pp_order_part_id" id="pp_order_part_id" value="<?php echo $order_parts->order_part_id;?>" />
</form>

</div>