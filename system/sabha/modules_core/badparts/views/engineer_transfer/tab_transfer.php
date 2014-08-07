<?php $this->load->view('dashboard/system_messages');?>
<script language="javascript">
$(document).ready(function(){
	$("#frmreturnpart").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ returnpart();}}  
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
<col width="7%" ><col width="17%"><col width="4%" ><col width="12%"><col width="3%" ><col width="12%"><col width="3%" ><col width="10%"><col width="5%">
    <tbody>
	<tr>
            <td><?php echo $this->lang->line('service_center');?></td>
            <td><?php echo $servicecenter_select;?></td>
            <td><?php echo $this->lang->line('engineer');?></td>
            <td><span id="engineer_box"><?php echo $engineer_select;?></span></td>
            <td><?php echo $this->lang->line('parts');?></td>
            <td><span id="parts_box"><?php echo $badparts_select;?></span></td>
            <td><?php echo $this->lang->line('quantity');?></td>
            <td><input type="text" class="validate[required,custom[integer]] text-input" id="part_quantity" value=""></td>
            <td><input type="submit" name="return" value="Return" class="button" ></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9" style="text-align: left"><span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
        </tr>
    </tfoot>
</table>    
</form>