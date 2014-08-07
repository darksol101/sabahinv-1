<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showallocationdetails();
	$("#revokeForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ revokeAssign();}}  
	});
	$('#engineer_select').val(<?php echo $this->uri->segment(3);?>);
	var ew =  '<?php echo $this->uri->segment(4);?>'+':'+'<?php echo $this->uri->segment(5);?>'
	$('#part_select').val(ew);

})
</script>
<form method="post" name="revokeForm" id="revokeForm" onsubmit="return false">
<fieldset>
<legend style="font-size:13px"> Revoke Parts </legend>
<table width="75%" cellpadding="0" cellspacing="0">

	<tr>
		<td style="vertical-align : top"></td>
	<tr>
				<th><label><?php echo $this->lang->line('parts');?>:</label></th>
				<td><?php echo $part_options;?></td>
			
			
				<th><label><?php echo $this->lang->line('engineers');?>:</label></th>
				<td><?php echo $engineerOption;?></td>
                
                
                <th><label><?php echo $this->lang->line('quantity');?> </label></th>
                <td> <input type="text" id="quantity" class="text-input validate[required]" value="" />      </td>
                <td>&nbsp;</td>
                <td><a href="<?php echo base_url();?>/partallocation"><input type="button" class="button" value="<?php echo $this->lang->line('back');?>" /></input></a></td>
			</tr>
	<tr class="buttons">
		<td align="center" colspan="2" style="text-align:center; ">
          
            <input type="submit" 
			value="<?php echo $this->lang->line('revoke'); ?>" name="btn_submit"
			id="btn_submit" class="button" /> 
            
             <input type="button"
			value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel"
			id="btn_cancel" class="button" onclick="cancelform();" /></label>
		<input type="button" value="<?php echo $this->lang->line('close'); ?>"
			name="btn_submit" id="btn_close" class="button"
			onclick="closeform1();" />
            </td>
	</tr>
</table>

<div class="clear">&nbsp;</div>
</fieldset>
</form>
<div>&nbsp;</div>
