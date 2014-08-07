<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php if($call_details->call_id>0 && $call_details->call_status >= 0){?>
<style>
.searchparstbtn, .searchpendingparstbtn{cursor:pointer}
#used_parts_list input[readonly=readonly],#pending_parts_list input[readonly=readonly]{ background:#F7F7F7!important;}
</style>
<fieldset><legend><?php echo $this->lang->line('defected_parts'); ?></legend>
<div  style="text-align:right">
	<input type="button" onclick="add_defected_parts()" value="Add Defected Parts" name="add" class="button" />
</div>
<table width="100%" id="defected_parts_list" class="tblgrid">
	<col width="10%" />
    <col width="10%" />
    <col width="2%" />
    <col width="5%" />
    <col width="10%" />
    <col width="4%" />
    <col width="10%" />
    <col width="10%" />
    <col width="7%" />
    <col width="10%" />
    <col width="7%" />
    <tbody><?php
    $i=1;
    foreach($defected_parts as $parts){
    $trstyle=$i%2==0?" class='even' ": " class='odd' ";
    	?>
		<tr<?php echo $trstyle;?>>
			<td><label><?php echo $this->lang->line('part_number');?></label></td>
			<?php if($call_details->call_status<3){?>
			<td><input readonly="readonly" type="text" name="defected_part_number[]" value="<?php echo $parts->part_number;?>" class="pnum text-input" />
            <input type="hidden" name="defected_parts_id[]" value="<?php echo $parts->part_defect_id;?>" />
			</td>
			<td></td>
			<td><?php echo $this->lang->line('description');?></td>
			<td><input readonly="readonly" type="text" name="defected_part_description[]" value="<?php echo $parts->part_desc;?>" class="text-input"></td>
            
            <td style="text-align:right"><?php echo $this->lang->line('quantity');?></td>
			<td><input style="text-left" type="text" name="defected_part_quantity[]" value="<?php echo $parts->part_quantity;?>" class="text-input" readonly="readonly"></td>
            
            
            <td style="text-align:right"><?php echo $this->lang->line('company_name');?></td>
            
            <td> <input readonly="readonly" style="text-left" type="text" name="defected_company[]" value="<?php echo $parts->company_title;?>" class="text-input" readonly="readonly"> </td>
            
             <td style="text-align:right"><?php echo $this->lang->line('serialno.');?></td>
            
            <td> <input readonly="readonly" style="text-left" type="text" name="serial[]" value="<?php echo $parts->part_serial_no;?>" class="text-input" readonly="readonly"> </td>
            
            
			
           
            
			
			<?php }else{?>
            <td><label><?php echo $parts->part_number;?></label></td>
            <td><label></label></td>
            <td><label><?php echo $this->lang->line('description');?></label></td>
            <td><label><?php echo $parts->part_desc;?></label></td>
            <td style="text-align:right"><label><?php echo $this->lang->line('quantity');?></label></td>
             <td style="text-align:right"><label><?php echo $this->lang->line('company_name');?></label></td>
            <td style="text-align:left"><label><?php echo $parts->part_quantity;?></label></td>
             <td style="text-align:left"><label><?php echo $parts->company_title;?></label></td>
              <td style="text-align:right"><?php echo $this->lang->line('serialno.');?></td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td><label></label></td>
           
            <?php }?>
		</tr>
	<?php $i++;}?></tbody>
</table>
</fieldset>

<?php }?>
<input type="hidden" name="hdncall_status" id="hdncall_status" value="<?php echo $call_details->call_status;?>" />
<input type="hidden" name="order_id" id="order_id" value="<?php echo $order->order_id;?>" />
    