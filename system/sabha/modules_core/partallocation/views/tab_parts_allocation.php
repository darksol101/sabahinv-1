

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<input type="hidden" id="searchthis" value="<?php echo $this->uri->segment('5');?>" />
<input type="hidden" id="part_num" value="<?php echo $this->uri->segment('6');?>" />
<input type="hidden" id="get_date" value="<?php echo $this->uri->segment('7');?>" />
<input type="hidden" id="unsign_check" value="<?php echo $this->uri->segment('8');?>" />
<input type="hidden" id="status_check" value="<?php echo $this->uri->segment('9');?>" />

<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">



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
							   
			$("#allocation_frm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){  document.allocation_frm.submit();}}  
	});
							  
	if ($('#searchthis').val()==1 || $('#searchthis').val()==2){
		var datematch=$('#get_date').val();
		var b=datematch.substring(0,10);
		var x=b.split("-");
		var date1 =x[2]+"/"+x[1]+"/"+x[0];
		
		if($('#searchthis').val()==2){ $('#searchdate').val(date1);}
		showAllocationList();
		}
		
	
	$('.loading').hide();
	
	});
</script>

<div class="toolbar1">
<form action="" method="post" id="allocation_frm" name="allocation_frm">
	<table width="100%">
	<col width="25%" /><col width="25%" /><col width="15%" /><col width="30%" /><col width="10%" />
<tr>
   	<td><?php echo $servicecenters_search ;?></td>  
  	<td> <span id="egr"> <?php echo $engineerOption;?> </span></td>	
    <td><?php echo $status_select;?></td>
  	<td>Date <span style="color:red
        ";>*</span> : <input id="searchdate" name="searchdate" class="text-input datepicker" type="text" readonly="readonly" value="<?php echo date('d/m/Y');?>"> </td>
	<td> <span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    <td>    
        <img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="showAllocationList();" /> 
    </td>
    
</tr>
</table>
</form>
</div>
<div id="allocationlist" style ="width: 100%; margin-top: 15px"></div>
    
