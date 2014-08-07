<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div style="text-align:left; <?php if($call_details->call_id>0 && $call_details->call_reason_pending == 'PO Approval Pending' ){?>display:block <?php }else{ ?> display:none <?php }?>;" id="PO_pending" >
<fieldset>
<legend><?php echo $this->lang->line('po_request_parts');?></legend>

<input type="button" onclick="add_po_request()" value="Add PO Request Parts" name="add" class="button" style="float:right;" />
	
	<table cellpadding="0" cellspacing="0" width="100%" class="tblgrid" id="po_request_list">
    
		<col width="15%" /><col width="10%" /><col width="5%" /><col width="10%" /><col width="15%" /><col width="2%" /><col width="4%" /><col width="10%" /><col width="5%" /><col width="8%" /><col width="8%" /><col width="5%" /><col width="5%" />
		<tbody>
		<?php 
		$i=1;
		foreach($po_request_parts as $parts){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr<?php echo $trstyle;?>>
      
			<td><label><?php echo $this->lang->line('part_number');?></label></td>
			<?php if($parts->approved==0){?>
            <td>
            <input readonly="readonly" type="text" name="requestpo_part[]" value="<?php echo $parts->part_number;?>" class="pnum text-input" />
            </td>
            
			<td><a class="searchpendingparstbtn"><?php if(empty($parts->part_number)){ echo icon("search","Search","gif","icon");}?></a></td>
			
            <td><?php echo $this->lang->line('description');?></td>
			<td><input readonly="readonly" type="text" name="requestpo_part_desc[]" value="<?php echo $parts->part_desc;?>" class="text-input"></td>
			
            <td style="text-align:right"><?php echo $this->lang->line('quantity');?></td>
			<td><input style="text-align:left" id="reqqty_<?php echo $i;?>" type="text"  readonly="readonly" name="requestpo_part_quantity[]" value="<?php echo $parts->part_quantity;?>" class="text-input"></td>
            <td><input type='hidden' name='requestpo_id[]' value="<?php echo $parts->requestpo_id?>" ></td>
            
            
            <td><?php echo $this->mdl_mcb_data->getStatusDetails($parts->approved,'porequest_status');?></td>
           
            <?php if ($parts->approved == 0){?>
            <td><a class="btn deleteporequest"><?php echo icon('delete','Delete','png');?></a></td>
              <td><a class="btn approveporequest"><?php echo icon('check','Check','png');?></a></td>
            <?php }?>
			<?php }else{?>
            
            
            <td><label><?php echo $parts->part_number;?></label></td>
            <td></td>
            <td><label><?php echo $this->lang->line('description');?></label></td>
			<td><label><?php echo $parts->part_desc;?></label></td>
            <td style="text-align:right"><label><?php echo $this->lang->line('quantity');?></label></td>
			<td style="text-align:left"><label><?php echo $parts->part_quantity;?></label></td>
			<td style="text-align:left"><input type="hidden" name='requestpo_id[]' value="<?php echo $parts->requestpo_id?>" > </td>
            <td><label><?php echo $this->mdl_mcb_data->getStatusDetails($parts->approved,'porequest_status');?></label></td>
            <td></td>
			<?php }?>
		</tr>
		<?php $i++;}?>
		</tbody>
	</table>

</fieldset>
</div>