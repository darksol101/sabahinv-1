<?php $this->load->view('dashboard/system_messages');?>
<style type="text/css">
img.searchbtn {
    cursor: pointer;
    margin-bottom: -2px;
}
.loading{ display:none;}
input#from_date,input#to_date{ width:100px;}
table td img.ui-datepicker-trigger {padding-left: 5px!important;margin-top: -4px;vertical-align: middle;}
table td img.ui-datepicker-trigger{ }
#main-content .adjustment table td, #main-content .adjustment table th,#main-content .toolbar1 table td, #main-content .toolbar1 table th { padding: 0!important;}
</style>
<script language="javascript">
$(document).ready(function(){
	$("#frmadjustment").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ saveadjustment();}}  
	});
})
</script>
<div class="adjustment">
<form onsubmit="return false;" id="frmadjustment">
<table width="100%">
<col width="8%" /><col width="25%" /><col width="7%" /><col width="20%" /><col width="7%" /><col width="7%" /><col width="5%" /><col />
    <tr>
        <td>Store</td>
        <td><?php echo $servicecenter_select;?></td>
        <td>Item Number</td>
        <td><?php echo $part_select;?></td>
        <td>Part Quantity</td>
        <td><input type="text" style="width:50px;" name="part_quantity" id="part_quantity" value="" class="validate[required,custom[integer]] text-input" /></td>
        <td><input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" /></td>
        <td><span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    </tr>
</table>
</form>
</div>
<div class="toolbar1">
    <form onsubmit="return false">
    <table width="100%">
        <col width="8%" /><col width="18%" /><col width="5%" /><col width="15%" /><col width="2%" /><col width="15%" /><col />
        <tbody>
            <tr>
                <td>Store</td>
                <td><?php echo $servicecenter_select_search;?></td>
                <td>From</td>
                <td><input type="text" name="from_date" id="from_date" class="text-input datepicker" /></td>
                <td>To</td>
                <td><input type="text" name="to_date" id="to_date" class="text-input datepicker" /></td>
                <td><img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="getadjustments();" /> </td>
            </tr>
        </tbody>
        
    </table>
    </form>
</div>
<div id="adjustmentslist"></div>