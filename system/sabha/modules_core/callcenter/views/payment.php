
<div class="col-1">
<fieldset>

<?php if($billing_details->service_charge <= 0){
	
if ($product_details->model_charge != ''){ $sc_charge = $product_details->model_charge;} else{$sc_charge= $product_details->service_charge; }
	
} else {$sc_charge = $billing_details->service_charge;}?> 
<legend><?php echo $this->lang->line('payment');?></legend>
	<div class="form_row">
	<div class="col">
		<label>Service Charge </label>
		<input type='text' class='validate[custom[integer]]  text-input' id='service_charge' name= 'service_charge' value='<?php echo  $sc_charge;?>'> 
		<label>Transportation </label>
		<input type='text' class='validate[custom[integer]] text-input' id='transportation_charge' name= 'transportation_charge' value='<?php echo  $billing_details->transportation_charge;?>'>
		<label>Extra Charge </label>
		<input type='text' class='validate[custom[integer]]  text-input' id='extra_charge' name= 'extra_charge' value='<?php echo  $billing_details->extra_charge;?>'> 
		<label>Extra Charge Details </label>
		<input type='text' class='text-input' id='extra_charge_details' name= 'extra_charge_details' value='<?php echo  $billing_details->extra_charge_desc;?>'> 
	
		<input type = 'hidden' id='call_pay_details_id' name='call_pay_details_id' value = '<?php echo $billing_details->call_pay_details_id;?>'/>
	</div>
	</div>
</fieldset>
</div>