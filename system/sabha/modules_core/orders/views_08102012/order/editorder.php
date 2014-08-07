<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<style type="text/css">
table.tblgrid tbody tr td {   padding: 6px 10px!important;}
</style>
<div style="float:left; width:30%">
<form action="" method="post" name="orderForm" id="orderForm">
	<table width="100%" cellpadding="0" cellspacing="0" class="">
        <col width="40%" /><col />
        <tr>
            <th><label> <?php echo $this->lang->line('order_id')?></label></th>
            <td><label><?php echo $result->order_id;?> </label></td>
        </tr>
        <tr>
            <th><label><?php echo $this->lang->line('order_number')?> </label></th>
              <td><label><?php echo $result->order_number;?> </label></td>
        </tr>
        <tr>
        <th><label> <?php echo $this->lang->line('call_id')?></label></th>
            <td><label><?php echo $result->call_id;?> </label></td>
        </tr>
        <tr>
            <th><label> <?php echo $this->lang->line('order_date')?></label></th>
            <td><label><?php echo format_date(strtotime($result->order_dt));?> </label></td>
        </tr>
        <tr>
        <th><label> <?php echo $this->lang->line('status')?></label></th>
        <td><label><?php if(($result->order_status)==1){?>
       		<input type="checkbox" value="2" name="status" class="status"/> 
       <?php } else { 
	   			echo $this->mdl_mcb_data->getStatusDetails($result->order_status,'order_status');}
	   
	   ?></label>
         </td>
        </tr>
       
        <tr>
            <td colspan="2"><input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" /> <input type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel"	id="btn_cancel" class="button" onclick="cancelProductform();" />
		<input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
           
            </td>
        </tr>
    </table>
    <input type="hidden" name="order_id" id="order_id" value="<?php echo $result->order_id;?>" />
</form>
</div>
<div style="float:left; width:60%">

 	<table width="100%" cellpadding="0" cellspacing="0"	class="tblgrid">
	<col width="5%" /><col width="40%" /><col width="30%" />
	<thead>
		<tr>
			<th><?php echo $this->lang->line('sn');?></th>
			<th><?php echo $this->lang->line('part_number');?></th>
			<th style="text-align:center"><?php echo $this->lang->line('quantity');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach($orderparts as $orderpart){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
			<td><?php echo $orderpart->part_number ;?></td>
			<td style="text-align:center"><?php echo $orderpart->part_quantity ;?></td>
		</tr>
		<?php
		$i++;
	} ;
	?>
	</tbody>
</table>

</div>
<div style="clear:both"></div>