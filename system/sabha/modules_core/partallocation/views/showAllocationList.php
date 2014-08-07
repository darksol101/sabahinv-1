<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<input type="hidden" value="<?php echo $this->uri->segment(5);?>" id="check_url" />

<script>
/*$(document).ready(function() {
						   var check_unsign = $("#unsign_check").val();
						if(check_unsign == ""){
							$('#btn_submit').hide();
							}
						});*/
/*$(document).ready(function(){ alert($('#check_url').val());
						   if($('#check_url').val()==2){
							   $('#btn_signedcheck').hide();
						   }});*/
							   
</script>
<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="1%" /><col width="15%"/><col width="25%" /><col width="15%" /><col width="25%" /><col width="15%"/><col width="5%" />
	<thead>
    <tr><?php $check_unsign = $this->input->post('check_unsign');
	
		if($check_unsign == "") {
?>
    <td colspan="8" style="text-align:right;"><input type="button" class="button"
			value="<?php echo $this->lang->line('print'); ?>"
			name="btn_submit" id="btn_submit"
			title="Print" onclick="generatePrintReport();" /></td><?php }?>
            </tr>
    	<tr>
        	<th><?php echo $this->lang->line('sn');?></th>
             <th style="text-align:center"><?php echo $this->lang->line('service_center');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('engineer_name');?></th>
            <th><?php echo $this->lang->line('part_number');?></th>
            <th><?php echo $this->lang->line('part_desc');?></th>
            <?php if($this->input->post('allco_select')==1){?>
             <th><?php echo $this->lang->line('allocated_date');?> </th><?php } else {?>
             <th><?php echo $this->lang->line('revoked_date');?> </th> <?php } ?>
             <th style="text-align:center"><?php echo $this->lang->line('quantity');?></th>
             <th><?php echo $this->lang->line('signed'); ?></th>
            
        </tr>
    </thead>
    <tbody>
	
<?php $i=1;
	foreach($lists as $list){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";	?>
			<tr<?php echo $trstyle;?>>
            	<td><?php echo $i;?></td>
                 <td style="text-align:center"><?php echo $list->sc_name;?></td>
                <td style="text-align:center"><?php echo $list->engineer_name;?></td>
                 <td><?php echo $list->part_number?></td>
                 <td><?php echo $list->part_desc?></td>
                <td><?php echo $list->created_date?></td>
                 <td style="text-align:center"><?php echo $list->quantity;?></td>
                 <td style="text-align:center"><?php if($list->signed == 0){?><input type="checkbox" class="alloc_list" id="<?php echo $list->parts_allocation_details_id; ?>" value="<?php echo $list->parts_allocation_details_id;?>"/><?php } 
				 
				 else {?><input type="checkbox"  id="<?php echo $list->parts_allocation_details_id; ?>" disabled="disabled" checked="checked"  value="<?php echo $list->parts_allocation_details_id;?>" class="alloc_list_disable" /><?php }?></td>
               	 
            </tr>
	<?php $i++; }?>
    </tbody>
</table>
<table >
<tr>

<td><input type="button" value="Save" class="button" id="btn_signedcheck" onclick="check_checklist()"/></td>

</tr>
</table>