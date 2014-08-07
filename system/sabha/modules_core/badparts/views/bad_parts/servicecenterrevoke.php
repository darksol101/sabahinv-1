<?php $this->load->view('dashboard/system_messages');?>
<script language="javascript">
$(document).ready(function(){
	$("#frmreturnpart").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ revokeScPart();}}  
	});
})
</script>
<style type="text/css">
.loading {display:none;}
#engineer_box,#parts_box{position: relative;}
#engineer_box .loading, #parts_box .loading{position: absolute;left: 0px;top: 0;width: 100%;height: 30px;margin: 0 auto;text-align: center;}
</style>
<form id="frmreturnpart" onsubmit="return false">
<table class="" width="100%">
    <col width="7%" >
    <col width="17%">
    <col width="4%" >
    <col width="12%">
    <col width="3%" >
    <col width="12%">
    <col width="4%" >
     <col width="13%">
    <col width="5%">
    <col width="10%">
    <col width="5%">
    <tbody>
	<tr>
            <td><?php echo $this->lang->line('service_center');?></td>
            <td><?php echo $servicecenter_revoke;?></td>
            
            <td><?php echo $this->lang->line('parts');?></td>
            <td><span id="part_box"><?php echo $part_select;?></span></td>
            
            <td><?php echo $this->lang->line('company');?></td>
            <td><span id="company_box"><?php echo $company_select;?></span></td>
            
            <td>Reason</td>
            <td><?php echo $badpart_reasons;?></td>
            
            <td><?php echo $this->lang->line('quantity');?></td>
            <td><input type="text" class="validate[required,custom[integer]] text-input" id="part_quantity" value=""></td>
            
            <td><input type="submit" name="return" value="Return" class="button" ></td>
        </tr>
    </tbody>
    <tfoot>
        <tr> <td>&nbsp; </td></tr>
        <tr> <td>&nbsp; </td></tr>
        <tr> <td>&nbsp; </td></tr>
    </tfoot>
</table>    
</form>