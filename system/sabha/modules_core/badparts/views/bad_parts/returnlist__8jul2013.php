<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<input type="hidden" id="date_get" value="<?php echo $this->uri->segment('5');?>" />
<input type="hidden" id="check_url_print" value="<?php echo $this->uri->segment('6');?>" />
<script type="text/javascript">

$(document).ready(function(){
						  
		$("#return_list").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){  getretrunlist();}}  
	});
});

	$(function() {
		$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>',
			
		});
	});
	$(document).ready(function(){
	 if($('#check_url_print').val() == 1) { 	getretrunlist(); }	
	$('.loading').hide();});
</script>

<div class="toolbar1">
<form id="return_list" name="fname">
	<table width="100%">
	<col width="19%" /><col width="19%" /><col width="26%" /><col width="26%" /><col width="1%" /><col width="5%" />
<tr>
   	<td><?php echo $servicecenters_search ;?> 
  	<td><?php echo $engineerOption;?></td>
    <td>From <span style="color:red
        ";>*</span> : <input id="fromdate" readonly="readonly" name="fromdate" class="validate[required] text-input datepicker" type="text" value="<?php echo date('d/m/Y');?>"></td>
        <td> To <span style="color:red
        ";>*</span> : <input id="todate" readonly="readonly" name="todate" value="<?php echo date('d/m/Y');?>" class="validate[required] datepicker text-input" type="text"></td>
   	<td> <span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    <td>    
        <img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="getretrunlist();" /> 
    </td>
   </tr>
    
</table>
</form>
</div>
<div id="showreturnlist" style ="width: 100%; margin-top: 15px"></div>
    
