<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php 

$final_parts = array_merge($order_parts,$requested_parts);

?>
<?php if($call_details->call_id>0 && $call_details->call_status >= 0){?>
<style>
.searchparstbtn, .searchpendingparstbtn{cursor:pointer}
#used_parts_list input[readonly=readonly],#pending_parts_list input[readonly=readonly]{ background:#F7F7F7!important;}
</style>




<?php //if($call_details->call_id>0 && $call_details->call_reason_pending == 'Part Pending' ){ 

?>
<fieldset>
<div style="text-align:left; <?php if($call_details->call_id>0 && $call_details->call_reason_pending == 'Part Pending' ){?>display:block <?php }else{ ?> display:none <?php }?>;" id="part_pending" > 
<legend><?php echo $this->lang->line('parts_pending');?></legend>
		
		<div class="form_row">
			<div id="col">
                <div id="field_pending">
                <input type="button" onclick="add_pending_parts()" value="Add Pending Parts" name="add" class="button" style="float:left;" />
                </div>
	<?php 
		$i=1;
		foreach($final_parts as $parts){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<?php /*?><tr<?php echo $trstyle;?>><?php */?>
		
      
				<label><?php echo $this->lang->line('part_number');?></label>
				<?php if($parts->order_status==0){?>				
                    <input readonly="readonly" type="text" name="order_part_number[]" value="<?php echo $parts->part_number;?>" class="pnum text-input" />
                    
                    <input type="hidden" name="order_part_id[]" value="<?php echo $parts->order_part_id;?>" />
                    
                    <input type="hidden" name="calls_orders_id[]" value="<?php echo $parts->calls_orders_id;?>" />
                    <div id="field_part">       
                            <a class="searchpendingparstbtn"><?php if(empty($parts->part_number)){ echo icon("search","Search","gif","icon");}?></a>
                    </div>
                    
                    <div id="field_description">
                    
                        <?php echo $this->lang->line('description');?>
                        <input readonly="readonly" type="text" name="order_part_description" value="<?php echo $parts->part_desc;?>" class="text-input">
                    
                    </div>
                    
                    <div id="field_quantity">
                        <?php echo $this->lang->line('quantity');?>	
                        <input style="text-align:left" id="reqqty_<?php echo $i;?>" type="text"  readonly="readonly" name="order_part_quantity[]" value="<?php echo $parts->part_quantity;?>" class="text-input">	
                    </div>	
                    
                 
                    
                     <div id="field_orderno">
					 	<?php echo $this->lang->line('order_number');?>
                        <?php echo $parts->order_number;?>
                     </div>
            
            		<div id="field_orderstatus">
						<?php echo $this->mdl_mcb_data->getStatusDetails($parts->order_status,'order_status');?><input type="hidden" name="parts_order_id[]" value="<?php echo $parts->order_id;?>" />
					<?php if ($parts->order_id == 0){?>
                        <a class="btn deletepartpending"><?php echo icon('delete','Delete','png');?></a>
                    <?php }?>
                    </div>
				<?php }else{?>
            
            
                    <div><label><?php echo $parts->part_number;?></label></div>
                    <div><label><?php echo $this->lang->line('description');?></label></div>
                    <div><label><?php echo $parts->part_desc;?></label></div>
                    <div style="text-align:right"><label><?php echo $this->lang->line('quantity');?></label></div>
                    <div style="text-align:left"><label><?php echo $parts->part_quantity;?></label></div>
                    <div><label><?php echo $this->lang->line('order_number');?></label></div>
                      <div><label><?php echo $parts->order_number;?></label></div>
                    <div><label><?php echo $this->mdl_mcb_data->getStatusDetails($parts->order_status,'order_status');?></label></div>
           
			<?php }?>
		
		<?php $i++;}?>
	</div>
    </div>
</fieldset>
<?php // }?>

		<fieldset>
			<legend><?php echo $this->lang->line('used_parts'); ?></legend>
				<div class="form_row">
                    <div class="col">
                        <div style="text-align:right">
                                <?php if ($call_details->call_status != 3) {?><input type="button" onclick="add_used_parts()" value="Add Used Parts" name="add" class="button" /><?php }?>
                        </div>
                    </div>
                </div>
<table width="100%" id="used_parts_list" class="tblgrid">
	<col width="10%" />
    <col width="10%" />
    <col width="2%" />
    <col width="5%" />
    <col width="22%" />
    <col width="10%" />
    <col width="10%" />
    <col width="9%" />
    <col width="10%" />
    
    <tbody><?php
    $i=1;
    foreach($used_parts as $parts){
    $trstyle=$i%2==0?" class='even' ": " class='odd' ";
    	?>
		<tr<?php echo $trstyle;?>>
			<td><label><?php echo $this->lang->line('part_number');?></label></td>
			<?php if($call_details->call_status<3){?>
			<td><input readonly="readonly" type="text" name="part_number[]" value="<?php echo $parts->part_number;?>" class="pnum text-input" />
			<input type="hidden" name="used_parts_id[]" value="<?php echo $parts->parts_used_id;?>" /></td>
			<td><a class="searchparstbtn"><?php if(empty($parts->part_number)){ echo icon("search","Search","gif","icon");}?></a></td>
			<td><?php echo $this->lang->line('description');?></td>
			<td><input readonly="readonly" type="text" name="part_description[]" value="<?php echo $parts->part_desc;?>" class="text-input"></td>
            
			<td style="text-align:right"><?php echo $this->lang->line('company_name');?></td>
            
             <td> <input readonly="readonly" style="text-left" type="text" name="used_company[]" value="<?php echo $parts->company_title;?>" class="text-input" readonly="readonly"> </td>
            
            <td style="text-align:right"><?php echo $this->lang->line('quantity');?></td>
			<td><input style="text-left" type="text" readonly="readonly" name="part_quantity[]" id="usdpqty_<?php echo $i;?>" value="<?php echo $parts->part_quantity;?>" class="text-input validate[custom[onlyNumberSp]]" ></td>
            
             <td><?php if ($call_details->call_status != 3) {?><a class="btn returnparts"><?php echo icon('delete','Delete','png');?></a><?php }?></td>
            
            
            <td><?php /*<a class="btn editparts"><?php echo icon('edit','Edit','png');?></a> */?></td>
            <?php }else{?>
            <td><label><?php echo $parts->part_number;?></label></td>
            <td><label></label></td>
            <td><label><?php echo $this->lang->line('description');?></label></td>
            <td><label><?php echo $parts->part_desc;?></label></td>
            <td style="text-align:right"><label><?php echo $this->lang->line('quantity');?></label></td>
            
            <td style="text-align:left"><label><?php echo $parts->part_quantity;?></label></td>
             <td style="text-align:right"><label><?php echo $this->lang->line('company_name');?></label></td>
             <td style="text-align:left"><label><?php echo $parts->company_title;?></label></td>
            <td><label></label></td>
            <?php }?>
		</tr>
	<?php $i++;}?></tbody>
</table>

</fieldset>
<?php }  ?>



<input type="hidden" name="hdncall_status" id="hdncall_status" value="<?php echo $call_details->call_status;?>" />
<input type="hidden" name="order_id" id="order_id" value="<?php echo $order->order_id;?>" />
