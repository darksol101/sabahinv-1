<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet"	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js"	type="text/javascript"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js"	type="text/javascript"></script>
<script>
	$(document).ready(function(){
	showSalesMakerList();
	$("#salesmakerForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){
	  		saveSalesMaker();
	  	}
	  }  
		});
	});
</script>

<style type="text/css">
table td img.ui-datepicker-trigger { padding-left: 5px;}
.editpart{cursor:pointer;}
.deletepart{cursor:pointer;}
.editactive{background: none repeat scroll 0 0 #FFF5E7;}
.ui-autocomplete {max-height: 200px;overflow-y: auto;overflow-x: hidden;padding-right: 20px;}
*html .ui-autocomplete {height: 200px;}
#deduction_value{width: 45px;}
table.toolbar1 td{ padding:0px 2px;!important;}
form table.toolbar1 label{ padding:0px!important ;}
</style>

<script>
 $(function() {
	$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd'
		});
	$("#btn_cancel" ).click(function() {
  		$("#salesmakerForm tbody tr input").empty();
  		$("#salesmaker_action").val(1);
	});


	$("#deduction_value").change(function () {
		var dis_type = $("#deductiontype").val();
		if(dis_type == 1){
			if($(this).val() > 99){
				alert('Discount 100% and is invalid');
				$(this).val('10');
			}

			if($(this).val() > 80){
				if(!confirm("The discount % large. Are you sure?")){
					$(this).val('10');
				}
			}

			
		}
	});

	
});


/*function getAssignOptions(val) {
		var options='';
		if(val==1){

			options='<?php echo $brand_select;?>';

		}
		else if(val==2){
			options='<?php echo $cat_select;?>';
		}
		
		else if(val==3){
			options='<?php echo $model_select;?>';
		}

		else if(val==4){
			options='<?php echo $model_select;?>';
		}

		else if(val==5){
			options='<?php echo $part_select;?>';
		}else{
			options='';
		}	

		
		$("#assign_to").html(options);
			
	}*/
</script>

<style>
	#assign_to select {
		display: block;
		width: 181px;
		height: 85px;
		max-height: auto;
		
	}

</style>
<form method="post" name="salesmakerForm" id="salesmakerForm" action="salesmaker/save_salesmaker">
	<input type="hidden" name="salesmaker_action" id="salesmaker_action" value="1"/>
	<input type="hidden" name="sale_id" id="sale_id" value/>

	<table width="100%">
	<col width="20%"/><col width="20%"/><col width="20%"/>

		<tr>
			<th>
				<label><?php echo $this->lang->line('salesmaker_name'); ?>: </label>
			</th>
			<td>
				<input type="text" name="sale_name" id="sale_name" value="" class="validate[required] text-input" />
			</td>
		
		</tr>

		<!-- 	<tr>
			<th>
			<label><?php echo $this->lang->line('assign');?></label>
			</th>
			<td>
				<?php echo $sale_maker_assign;?>
			</td>
		<td id="assign_to">
		
		</td>	
		
				
		
		
				</tr> -->

		<tr>
			<th><?php echo $this->lang->line('salestatus');?></th>
			<td><?php echo $salestatus; ?></td>
		
			<th><?php echo $this->lang->line('deductiontype');?></th>
			<td><?php echo $deductiontype; ?>
				<input type="text" name="deduction_value" id="deduction_value" value="" class="validate[required,custom[integer]] text-input">
			</td>
		</tr>

		<tr>
			<th><?php echo $this->lang->line('issue_date');?></th>

				<td><input name="issue_date" id="issue_date" type="text" class="text-input datepicker" value="<?php echo date('Y-m-d');?>"></td>
			<th><?php echo $this->lang->line('expire_date');?></th>
				<td><input name="expire_date" id="expire_date" type="text" class="validate[required] text-input datepicker" value=""></td>

		</tr>	


		<tr>
			<td>&nbsp;</td>
			
			<td align="right" colspan="3">
				<input type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" /> 
			<input type="reset" value="<?php echo $this->lang->line('cancel'); ?>" name="btn_cancel" id="btn_cancel" class="button"/> 
			
		</tr>
		
	</table>
	<input type="hidden" name="currentpage" id="currentpage" value="0" />
	

</form>

