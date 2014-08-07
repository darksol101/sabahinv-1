<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showOrderList();
	})
});
</script>
<table style="width:100%;" class="tblgrid" cellpadding="0" cellspacing="0">
    	<col width="1%" />
        <col />
        <col width="20%" />
        <col width="20%" />
        <col width="10%" />
        <col width="5%" />
            <thead>
            <tr>
                <th>S.No.</th>
                <th>Order Number</th>
                <th style="text-align:center"><?php echo $this->lang->line('order_dt');?></th>
                <th>Call ID</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
        <tbody>
        
        <?php
		$i=1;		
		foreach($orders as $order){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		$color = ($order->order_status==2)?'#A9A9A9;':'#1A9F04';
		?>
	
            <tr <?php echo $trstyle;?>>
                <td><?php echo $i+$page['start'];?></td>
                <td><?php echo $order->order_number;?></td>
                <td style="text-align:center"><?php echo format_date(strtotime($order->order_dt));?></td>
                <td><a target="_blank" title="Click to go to call" href="<?php echo site_url();?>callcenter/callregistration/<?php echo $order->call_id;?>"><?php echo $order->call_uid;?></a></td>
                <td><span style="color:<?php echo $color;?>;"><?php echo $this->mdl_mcb_data->getStatusDetails($order->order_status,'order_status');?></span></td>
                <td><a title="Click to edit" href="<?php echo site_url();?>orders/editorder/<?php echo $order->order_id;?>"><?php echo icon('edit','Edit','png')?></a></td>
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