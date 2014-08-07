<?php if( !defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"ledgerassign/script", "title"=>$this->lang->line('access_management'))); ?>
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('ledgerassign');?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="account/ledgerassign"><?php echo $this->lang->line('ledgerassign')?></a></li>
</ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab">
<?php $this->load->view('dashboard/system_messages');?>
<script type="application/javascript">
$(document).ready(function(){
	$("#ledgerassign").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ saveBillInfo();}}  
	});
});	
</script>	
<!--<form name="ledgerassign"  onsubmit="return false" id="ledgerassign">-->
<?php echo form_open('',array('onsubmit'=>'return false','id'=>'ledgerassign','name'=>'ledgerassign'));?>
<table width="100%">
	<col width="9%" /><col /><col width="10%" /><col /><col width="5%" /><col /><col width="10%" /><col />
	<tbody>
        	<tr>
                	<td>Select Ledger</td>
                        <td><?php echo $ledger_select;?></td>
                        <td>Billing Heads</td>
                        <td><?php echo $billing_select;?></td>      
                        <td>Type</td>
                        <td><?php echo $billing_type_select;?></td>          
                	<td>Service Center</td>
                        <td><?php echo $servicecenter_select;?></td>
                        <td colspan="2"><input type="submit" id="save_ledger_assign" name="save" value="Save" class="button" /></td>
                </tr>
        </tbody>
</table>
<input type="hidden" name="ledger_assign_id" id="ledger_assign_id" value="0" /> 
<?php echo form_close();?>
<div class="toolbar1">
<form onsubmit="return false;">
        <table width="100%" class="tblgrid">
        	<col /><col width="60%" />
                <tbody>
                        <tr>
                                <td>
                                  <?php echo $filter_ledger_select;?>      
                                </td>
                                <td><span class="message"><span class="message_text"></span></span></td>
                       </tr>                 
                </tbody>
        </table>
</form>
</div>
<input type="hidden" name="currentpage" id="currentpage" value="0" /> 
<div id="ledgerassignlist"></div>
</div>
</div>
<?php $this->load->view('dashboard/footer');
