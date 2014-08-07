<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){
		$("#allocaltion_frm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){  unsignedlist();}}  
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
	$('.loading').hide();});
</script>

<div class="toolbar1">
<form id="allocaltion_frm" name="fname">
	<table width="50%">
	<col width="30%" /><col width="30%" /><col width="1%" /><col width="5%" />
<tr>
   	<td><?php echo $servicecenters_search ;?> 
  	<td><?php echo $engineerOption;?></span></td>
   	<td> <span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    <td>    
        <img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="unsignedlist();" /> 
    </td>
   </tr>
    
</table>
</form>
</div>
<div id="unsignedlist" style ="width: 100%; margin-top: 15px"></div>
    
