<style type="text/css">
#fromdate,#todate{ width:50%!important;}
#searchtxt{ width:85%!important}
</style>
<style>
.datecheck{
	border:red
	}
.loading {display:none}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/validationEngine.jquery.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.validationEngine.js" type="text/javascript"></script>
<script>
/*jQuery(document).ready(function(){
								
				$("#search_form").validationEngine('attach', {
	 		 onValidationComplete: function(form, status){ if(status==true){ showhappycalllist();}}  
			 	});
				
		});*/
</script>
<script>
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
</script>

<form onsubmit="return false" id="frmhappycall" name="frmhappycall">
<table width="100%">
	<col width="10%" />
	<col width="20%" />
	<col width="7%" />
	<col width="20%" />
	<col width="5%" />
	<col width="20%" />
	<tr>
		<th><label><?php echo $this->lang->line('serivcecenter');?>: </label></th>
		<td><?php echo $servicecenter_select; ?></td>
		<th><label><?php echo $this->lang->line('brands');?></label></th>
		<td><?php echo $brand_select;?></td>
		<th><label><?php echo $this->lang->line('products');?></label></th>
		<td><span id="product_box"><?php echo $product_select;?></span></td>
		</tr>
	
	<tr>
		<td colspan="1"><input type="hidden" value="0" id="hdncity_id"
			name="hdncity_id" />
             <input type="hidden" name="currentpage"
			id="currentpage" value="0" /> 
            
		
	</tr>
</table>
</form>

<div class="toolbar1">
  <form onsubmit="return false;" id="search_form">
   <table width="100%">
   <col width="10%" />
   <col width="25%" />
   <col width="25%" />
    <col width="30%" />
    <col width="10%"/>
  <col />
   	<tr>
    	<td>Search Caller ID</td>
        <td><input type="text" placeholder="Call ID" name="searchtxt" id="searchtxt" value="" class="text-input" onkeydown="Javascript: if (event.keyCode==13) showhappycalllist();"  /></td>
        <td>From <span style="color:red
        ";>*</span> : <input id="fromdate" readonly="readonly" name="fromdate" class="validate[required] text-input datepicker" type="text" value="<?php echo date('d/m/Y');?>"></td>
        <td> To <span style="color:red
        ";>*</span> : <input id="todate" readonly="readonly" name="todate" value="<?php echo date('d/m/Y');?>" class="validate[required] datepicker text-input" type="text"></td>
        
        <td><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
        
       <td align="right">
       <input class="button" type="submit"
			value="<?php echo $this->lang->line('generate_happycall'); ?>"
			name="btn_submit" id="btn_submit"
			title="Generate HappyCall Report" onclick="showhappycalllist();" /></td>
          </tr>
   </table>
 <span style="margin-left:20px;"></span>  
<input type="hidden" name="currentpage" id="currentpage" value="0" />         
  </form>
</div>

<div id="happycalllist"></div>
<div id="happycalllist_excel" style="visibility:hidden; display:none"></div>

