<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script type="text/javascript">
$(document).ready(function(){
	showPartList();
	$("#partForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){savePart();}}  
	});
});

$(function() {
    $('#part_number').keyup(function() {
		this.value = this.value.replace(' ','');

        this.value = this.value.toLocaleUpperCase();
    });

    /*$('#part_customer_price').keyup(function () {
    	$(this).val(parseFloat($(this).val()).toFixed(2));
    });*/

});




function appendInPart () {
	/*var part_number=$("#part_init_no").val();
	
	if(part_number){
		var part_number=part_number.split('-')[0];
	var size = $("#part_size").val();
	var color = $("#part_color").val();

	if(size){
		part_number+="-"+size;
	}
	if(color){
		part_number+="-"+color;
	}

	part_number=part_number.toUpperCase();
	$("#part_number").val(part_number);
	$("#new_num").html(part_number);
	}
	else{
		$("#part_number").val(' ');

	}
*/
	

}


</script>

<form method="post" name="partForm" id="partForm" onsubmit="return false">
<table width="100%">
<col width="10%" /><col width="20%" /><col width="10%" /><col width="20%" /><col width="10%" /><col width="20%" />

	<tr>
	
		<th><label><?php echo $this->lang->line('partnumber'); ?>: </label></th>
		<td>
			<input type="text" value="" id="part_number" name="part_number" class="text-input">
		</td>
		<th style="display:none">
			<label><?php echo $this->lang->line('landing_price');?></label>
		</th>
        <td style="display:none">
        	<input type="text" name="part_landing_price" id="part_landing_price" value="" class="text-input" />
        </td>
        <th><label>Unit Type</label></th>
        <td>
        	<?php echo $part_units;?>
        </td>
        <th><label for="order_level">Min Order Level:</label></th>
        <td><input type="text" class="text-input validate[required]" name="order_level" id="order_level" value></td>
	</tr>

	<tr>
		<th rowspan="2"><label><?php echo $this->lang->line('description'); ?>:</label></th>
		<td rowspan="2"><textarea style="max-width:125px;min-width:125px;" name="description" id="description" class="validate[required]" rows="4" cols="10"></textarea></td>
		<th style="display:none"><label><?php echo $this->lang->line('service_center_price');?></label></th>
    	<td style="display:none"><input type="text" name="part_sc_price" id="part_sc_price" value="" class="text-input" /></td>
	</tr>

	<tr>
		<th><label><?php echo $this->lang->line('customer_price');?></label></th>
    	<td><input type="text" name="part_customer_price" id="part_customer_price" value="" class="validate[required,custom[number]] text-input" /></td>
    	
    	<th class="label_show" style="">

            <label for="order_level_max">Max Order Level:</label>
        </th>
        
        <td>
            
            <input type="text" class="text-input validate[required,custom[number]]" name="order_level_max" id="order_level_max" value>
        </td>

	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td align="right" colspan="3"><input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" /> 
			<input type="button" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel"	id="btn_cancel" class="button" onclick="cancelform();" /> 
			<input type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" /></td>
	</tr>
</table>
<input type="hidden" name="currentpage" id="currentpage" value="0" /> <input
	type="hidden" name="hdnid" id="hdnid" value="0" />
<div class="clear">&nbsp;</div>
</form>
<form onsubmit="return false" name="fname" id="fname" method="post">
</form>