<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showOrderList();
	})
});
</script>

<table style="width:100%;" class="tblgrid">
    	<col width="1%" />
        <col width="1%" />
        <col width="10%" />
       <col width="15%" />
       <col width="5%" />
        <col width="5%" />
            <thead>
            <tr>
                <th>S.No.</th>
                <th>Order Number</th>
                <th style="text-align:center">Order Date</th>
               <th>Store</th>
               <th> Status </th>
                <th></th>
            </tr>
            </thead>
        <tbody>
        
        <?php
		$i=1;		
		foreach($orders as $order){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
	
            <tr <?php echo $trstyle;?>>
                <td><?php echo $i+$page['start'];?></td>
                <td ><?php echo $order->badparts_order_number;?></td>
                <td style="text-align:center"><?php echo $order->badparts_order_dt;?></td>
                
                 <td><?php echo $order->sc_name?></td>
                 
                <td> <?php echo $this->mdl_mcb_data->getStatusDetails($order->badparts_order_status,'badparts_order_status');?></td>
                <td><a href="<?php echo site_url();?>badparts/badparts_order/addbadpartorder/<?php echo $order->badparts_order_id;?>"><?php echo icon('edit','Edit','png')?></a></td>
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