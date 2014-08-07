<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showPopOrderList();
	})
});
</script>

	
    
    
    <form>
<table style="width:100%;" class="tblgrid">
    	<col width="1%" />
        <col />
        <col width="20%" />
        <col width="20%" />
        <col width="15%" />
        <col width="10%" />
        <col width="5%" />
        <col width="5%" />
            <thead>
            <tr>
                <th>S.No.</th>
                <th>Call Id</th
                ><th style="text-align:center">Item Number</th>
                <th style="text-align:center">Quantity</th>
                <th>Store</th>
                <th>Assigned Er.</th>
                <th> </th>
                <th></th>
            </tr>
            </thead>
        <tbody>
        
        <?php
		$i=1;
		$attr_calls = 'class="call_order"';
		$selected = false;
		foreach($callorders as $order){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
	
            <tr <?php echo $trstyle;?>>
                <td><?php echo $i;?></td>
                <td ><?php echo $order->call_uid;?></td>
                <td style="text-align:center"><?php echo $order->part_number;?></td>
                <td style="text-align:center"><?php echo $order->part_quantity;?></td>
                 <td><?php echo $order->sc_name?></td>
                  <td><?php echo $order->engineer_name;?></td>
                <td> <?php echo form_checkbox('call_'.$order->calls_orders_id,$order->calls_orders_id,$selected,$attr_calls.' id="'.$order->calls_orders_id.'"');?></td>
                <td></td>
            </tr>
        <?php $i++; } ?>
        </tbody>
        <tfoot>
		<tr> 
			<td colspan="6">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
    </table>
    </form>