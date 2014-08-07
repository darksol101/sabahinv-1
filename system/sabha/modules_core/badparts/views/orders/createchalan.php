<?php $this->load->model(array('badparts/mdl_badparts_order_parts_details'));  ?>
<script>
	$(function() {
		$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
		});
	});
	

function validateForm()
{
var a=document.forms["transitform"]["courior_number"].value;
var b=document.forms["transitform"]["vehicle_number"].value;
var c=document.forms["transitform"]["box_number"].value;
var d=document.forms["transitform"]["transit_number"].value;
var e=document.forms["transitform"]["couriordate"].value;

if (a==null || a=="" || b==null || b=="" || c==null || c=="" || d==null || d=="" || e==null || e=="")
  {
  alert("All Fields Are required.");
  return false;
  }
  else {confirmtransit();
  }
}
</script>


<div style="width:800px;">
<form onsubmit="return false" id="transitform" name="transitform" >
<table style="width:100%;">
<tr> 
<td> <label> Courior Number </label></td>
<td>  <input type="input" id="courior_number" name="courior_number" class="text-input" value="" /> <span style="color:red;">*</span>  </td>

</tr>
<tr> 
<td> <label> Number of Boxes </label></td>
<td>  <input type="input" id="box_number"  class="text-input" name="box_number" value=""/> <span style="color:red;">*</span>   </td>
</tr>
<tr> 
<td> <label> Vehicle Number </label></td>
<td>  <input type="input" id="vehicle_number" name="vehicle_number" value="" class="text-input" />  <span style="color:red;">*</span>  </td>
</tr>
<tr> 
<td> <label> Transit Number </label></td>
<td>  <input type="input" id="transit_number" name="transit_number" value="" class="text-input" />  <span style="color:red;">*</span>  </td>
</tr>
<tr> 
<td> <label> Courior Date </label></td>
<td>   <input id="couriordate" name="couriordate" readonly="readonly" value=""  class="datepicker text-input" type="text" style="width:80%;">  <span style="color:red;">*</span>  </td>
</tr>
<tr>
<td colspan="2" style="text-align:right"><input type="submit" value="Save Transit Detail" onclick="validateForm();"  class="button"/></td>
</tr>
</table>

 <input type="hidden" id="transit_detail_id" value=""/>

</form>




<?php 
//echo '<pre>';
//print_r($order_parts);
//die();
?>


<form onsubmit="return false" name="">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid" style="margin-top:10px">
<col width="1%" /><col width="15%"  /><col width="30%" /><col width="5%" /><col width="5%" /><col width="10%" /><col width="16%" />
<thead>
	<tr>
    	<th style="text-align:center">S.N.</th>
        <th style="text-align:left">Item Number</th>
        <th style="text-align:left">Item description</th>
        <th style="text-align:center">Total Quantity</th>
        <th style="text-align:center">Delivered Qty</th>
        <th style="text-align:center">Dispatched Qty</th>
        <th style="text-align:center"></th>
        <th></th>
    </tr>
</thead>
<tbody>
<?php $i=0;
 foreach ($order_parts as $orderpart){
$trstyle=$i%2==0?" class='even' ": " class='odd' ";
$i = $i+1;
	//	$order_parts_details = $this->mdl_order_parts_details->getPartOderDetailsByOrderParts($orderpart->order_part_id);
		$dispatched_quantity = $this->mdl_badparts_order_parts_details->getDispatchedQuantity($orderpart->badparts_order_part_id);
		$delivered_quantity = $this->mdl_badparts_order_parts_details->getDeliveredQuantity($orderpart->badparts_order_part_id);
		//$deliveredDifferance = $this->mdl_order_parts_details->deliveredDifferance($orderpart->order_part_id);
		//print_r($dispatched_quantity);
?> 
<tr>
<td> <?php echo $i;?></td>
<td  style="text-align:left"><input type="hidden" id="badparts_order_part_number" value="<?php echo $orderpart->badparts_order_part_id;?>" /><?php echo $orderpart->part_number;?></td>
<td ><input type="hidden" name="hdn_part_number" value="<?php echo $orderpart->part_number;?>"/><?php echo $orderpart->part_desc;?> </td>

<td  style="text-align:center"><input type="hidden" name="remaining_quantity" value="<?php  echo ($orderpart->part_quantity - ($dispatched_quantity->dispatched_quantity)) ?>" /><span><?php echo $orderpart->part_quantity;?></span></td>

<td  style="text-align:center"> <?php  echo $delivered_quantity->delivered_quantity;?></td>

<td style="text-align:center"><?php echo $dispatched_quantity->dispatched_quantity; ?> </td>

<td style="text-align:center"><?php //if ($sc_id == $this->session->userdata('sc_id') || $this->session->userdata('usergroup_id') ==1){?><input type="text" class="text-input" value="" id="sentquantity"/> <?php // }?></td>
<td style="text-align:center"><?php if ($this->session->userdata('sc_id') == $from_sc_id) {?><a title="Dispatch" class="btn confirm_part">Dispatch</a><?php }?></td>
</tr>
<?php  }?>
</tbody>
</table>
<input type="hidden" id="hdn_from_sc_id" value="<?php echo $from_sc_id;?>" />
<input type="hidden" id="hdn_to_sc_id" value="<?php echo $to_sc_id?>"/>
<input type="hidden" id="hdn_badparts_order_id" value=" <?php echo $badparts_order_id;?>" />
</form>
</div>
