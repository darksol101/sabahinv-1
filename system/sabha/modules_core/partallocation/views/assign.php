<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	allocationlist();
	$("#assignForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ saveAssign();}}  
	});
})
</script>




<form method="post" name="assignForm" id="assignForm" onsubmit="return false">
<table width="75%" cellpadding="0" cellspacing="0">

 <tr>
  
<th> <?php echo $this->lang->line('service_center')?></th>
 <td><?php echo $servicecenters;?></td>
 
 </tr>
	<tr>
		<td style="vertical-align: top">
	<tr>
				<th><label><?php echo $this->lang->line('parts');?>:</label></th>
				<td><?php echo $part_options;?></td>
			<td><img
					 title="Search Parts" class="btn"
					onclick="getPartSearch();"
					src="<?php echo base_url();?>assets/style/img/icons/search.gif"
					 /></td>
			
				<th><label><?php echo $this->lang->line('engineers');?>:</label></th>
				<td><?php echo $engineerOption;?></td>
                
                
                <th><label><?php echo $this->lang->line('quantity');?> </label></th>
                <td> <input type="text" id="quantity" class="text-input validate[required,custom[onlyNumberSp]]" value=""  />      </td>
			</tr>
	<tr class="buttons">
		<td align="center" colspan="3" style="text-align:center; ">
           <input type="submit"
			value="<?php echo $this->lang->line('assign'); ?>" name="btn_submit"
			id="btn_submit" class="button"/>
           <input type="button"
			value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel"
			id="btn_cancel" class="button" onclick="cancelform();" /></label>
		<input type="button" value="<?php echo $this->lang->line('close'); ?>"
			name="btn_submit" id="btn_close" class="button"
			onclick="closeform();" />
            </td>
	</tr>
</table>

<div class="clear">&nbsp;</div>
</form>
