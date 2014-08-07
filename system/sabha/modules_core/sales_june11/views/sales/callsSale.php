<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
table td img.ui-datepicker-trigger { padding-left: 5px;}
.editpart{cursor:pointer;}
.deletepart{cursor:pointer;}
.editactive{background: none repeat scroll 0 0 #FFF5E7;}
.ui-autocomplete {max-height: 200px;overflow-y: auto;overflow-x: hidden;padding-right: 20px;}
* html .ui-autocomplete {height: 200px;}
.toolbar1 input[type="text"] {width:100%!important;}
table.toolbar1 td{ padding:0px 2px;!important;}
form table.toolbar1 label{ padding:0px!important ;}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet"	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js"	type="text/javascript"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js"	type="text/javascript"></script>



<script language="javascript">
$(document).ready(function(){
	$("#part_number").val('');
	$('#part_description').val('');
	$("#cancel_part").hide() ;
	$("#purchaseForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){  document.purchaseForm.submit();}}  
	});	

	calculatetotal();
});

</script>


<?php $this->load->view('dashboard/system_messages');?>
<div style="clear:both"></div>
<div id="purchaseContent">
<form action="" method="post" name="purchaseForm" id="purchaseForm">
<div style="float:left; width:30%">
<table width="100%">
	<col width="35%" /><col />
    
  <?php if ($salesdetails->sales_id!= '') {?>
    <tr>
    	<th><label><?php echo $this->lang->line('sales_number'); ?>: </label></th>
        <td><label><?php echo $salesdetails->sales_number;?></label> </td>
    </tr> 
    <?php  }?>
     <tr>
		<th><label><?php echo $this->lang->line('service_center'); ?>: </label></th>
        <td><?php echo $service_center; ?></td>
    </tr>
    <tr>
		<th><label><?php echo $this->lang->line('customer_name'); ?>: </label></th>
         <td><input type = 'text' name="customer_name" id="customer_name" class ='text-input'value = "<?php echo $salesdetails->customer_name;?>"/></td>
    </tr>
     <tr>
		<th><label><?php echo $this->lang->line('customer_address'); ?>: </label></th>
         <td><input type = 'text' name="customer_address" id="customer_address" class ='text-input'value = "<?php echo $salesdetails->customer_address;?>"/></td>
    </tr>
   
	<tr>
		<th><label><?php echo $this->lang->line('sales_note'); ?>: </label></th>
       <td><textarea name="sales_remarks" id="sales_remarks" class="" rows="4" cols="10"><?php echo $salesdetails->sales_remarks;?></textarea></td>
    </tr> 
	<tr>
		<th><label><?php echo $this->lang->line('sales_date'); ?>: </label></th>
        <td><input type="text" readonly="readonly" name="sales_date" id="sales_date" class="text-input datepicker" value="<?php echo format_date(strtotime(date("Y-m-d")));?>" style="width: 70%" /></td>
    </tr>
 
     <?php if($id){ ?>  
    <tr>
    	<th><label><?php echo $this->lang->line('confirm_delivery'); ?>:</label></th>
        <td><input name="deliver_status" type="checkbox" id="deliver_status"  <?php if($salesdetails->sales_status==2){?> checked="checked" <?php }?>/></td>
    </tr>
      <?php }?>
   
</table>
</div>

<div style="float:left; width:65%">
<?php if($salesdetails->sales_status<2){?>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid toolbar1">
	<col width="20%" />
    <col width="20%" />
    <col width="20%" />
    <col width="5%" />
    <col width="15%" />
    <col width="5%" />
    <col width="1%" />
	<tr>		
		<td valign="top"><label><?php echo $this->lang->line('part_no');?>:</label></td>
        <td><label><?php echo $this->lang->line('part_description');?>:</label></td>
        <td><label><?php echo $this->lang->line('company');?>:</label></td>
    	<td><label><?php echo $this->lang->line('part_quantity');?>:</label></td>
    	<td><label><?php echo $this->lang->line('part_rate');?> </label></label> </td>
        <td></td>
	</tr>
	<tr>
        <td><input type="hidden" name="hdnsales_details_id" id="hdnsales_details_id" value="0" class="text-input" /><input type="text" name="part_number" id="part_number" value="" class="text-input" /></td>
        
        <td><input type="text" name="part_description" id="part_description" value="" class="text-input" readonly ="readonly"  /></td>
              <td><?php echo $company_name ;?></td>
        <td><input type="text" name="part_quantity" id="part_quantity" value="" class="text-input validate[custom[onlyNumberSp]]"  /></td>
   
         <td><input type="text" name="part_rate" id="part_rate" value="" class="text-input "  readonly = 'readonly' /></td>
          
       
        <td><input type="button" id="add_part" name="add_part" onclick="checkpart();" value="Add" class="button" /></td><td><input type="button" id="cancel_part" name="cancel_part"  onclick= "cancelrow() " value="cancel" class="button" />
        </td>
        
	</tr>
    <input type="hidden" name="serial" id="serial" value=""/>
</table>
<?php }?>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="1%" /><col /><col width="20%" /><col width="10%" /><col width="10%" /><col width="20%" /><?php if($salesdetails->sales_status<2){?><col width="5%" /><col width="1%" /><?php }?>
    <tr>
		<th>Sno</th>  
        <th><?php echo $this->lang->line('part_no');?></th>
         <th><?php echo $this->lang->line('part_description');?></th>
          <th><?php echo $this->lang->line('company');?></th>
       <th><?php echo $this->lang->line('part_quantity');?></th>
       <th><?php echo $this->lang->line('total_price');?> </th>
    	<th></th>
        <?php if($salesdetails->sales_status<2){?><th></th> 
        <th></th><?php }?>
          <?php if($salesdetails->sales_status == 2){?><th></th> 
        <?php }?>
        
        
    </tr>
    <tbody id="rowdata">
    <?php 
	
	$i=1;
    foreach($sales_parts as $details){
    	
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr<?php echo $trstyle;?>>
			<td class="sn_td"><?php echo $i;?></td>
			<td><input type="hidden" id="" name="sales_details_id[]" value="<?php echo $details->sales_details_id;?>" /><input type="hidden" id="" name="pnum[]" value="<?php echo $details->part_number;?>" /><span class="lbl"><?php echo $details->part_number;?></span></td>
            
            <td style="text-align: left;"><input type="hidden" name="pdesc[]" value="<?php echo $details->part_description;?>" class="text-input" /><span class="lbl"><?php echo $details->part_description;?></span></td>
            <td style="text-align: center; "><input type="hidden" name="comp[]" value="<?php echo $details->company_title;?>" class="text-input" /><span class="lbl"><?php echo $details->company_title;?></span></td>
            
			<td style="text-align: left;"><input type="hidden" name="pqty[]" value="<?php echo $details->part_quantity;?>" class="text-input" /><span class="lbl"><?php echo $details->part_quantity;?></span></td>
			
			<td style="text-align: left;"><input type="hidden" name="prate[]" value="<?php echo $details->part_rate;?>" class="text-input" /><span class="lbl"><?php echo $details->part_quantity * $details->part_rate;?></span></td>
            
           <td></td>
            <?php if($salesdetails->sales_status <2){?><td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>
            <td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td><?php }?>
           
           
        </tr>
        <?php $i++;}?>
    </tbody>
    <tfoot> 
     <tr> 
	   
	   <td style ="padding-top: 150px">&nbsp;</td>
       <td style ="padding-top: 150px">Total Price:</td>
      <td style ="padding-top: 150px"><input type = 'text' readonly="readonly" name="total_price" id="total_price" class ='text-input'value = "<?php echo $salesdetails->total_price;?>"/></td>
    </tr>
    <tr > 
	   
	   <td  ><label><?php echo $this->lang->line('discount_type'); ?>: </label></td>
       <td ><?php echo $discount_type;?></td>
      <td ><input type = 'text' onkeyup= 'calculatetotal();' name="discount" id="discount" class ='text-input validate[custom[onlyNumberSp]]'value = "<?php echo $salesdetails->discount_amount;?>"/></td>
    </tr>
     <tr> 
	   
	   <td>&nbsp;</td>
       <td >Discounted Price:</td>
      <td><input type = 'text' readonly="readonly" name="discounted_total" id="discounted_total" class ='text-input'value = "<?php if($salesdetails->discount_type == 1){ echo $discount_amount = (($salesdetails->discount_amount / 100)*$salesdetails->total_price) ; } elseif ($salesdetails->discount_type == 2) { $discount_amount =  $salesdetails->discount_amount ;?>Rs. <?php echo $salesdetails->discount_amount; }else{$discount_amount = 0 ;}//echo $salesdetails->discount_amount;?>"/></td>
    </tr>
    <tr> 
	   
	   <td>&nbsp;</td>
       <td>Tax:</td>
      <td><input type = 'text'  onkeyup= 'calculatetotal();' name="tax" id="tax" class ='text-input 'value = "<?php echo $salesdetails->tax;?>"/></td>
    </tr>
    <tr> 
	   
	  <?php echo "<td>&nbsp;</td>"; ?>  
       <td>Grand Total:</td>
      <td><input type = 'text' readonly = "readonly" name="grand_total" id="grand_total" class ='text-input 'value = "<?php echo ($salesdetails->total_price - $discount_amount)+ (( $salesdetails->tax / 100 ) * ($salesdetails->total_price - $discount_amount));?>"/></td>
    </tr>
    
    
        <tr>
       
        <tr>
        <td colspan="8" >
       <?php if($salesdetails->sales_status < 2){?><input style="float:right" type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
      <?php }?>
        <input  style="float:right" type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
  
	<?php if ($bill_details->bill_id > 1 ){?>
        <input style="float:right" type="button" id="print_bill" name="print_bill" onclick="printbill(<?php echo $id;?>);"  value="Print Bill" class="button"/>
	
		<?php }?>
	<?php if($id > 0 && $salesdetails->sales_status == 2  && $bill_details->bill_id > 0 && $bill_details->bill_status == 1 && $this->session->userdata('usergroup_id')==1  ){?>
    		 <input style="float:right" type="button" id="Cancel Bill" name="Cancel Bill" onclick="cancelBill(<?php echo $bill_details->bill_id;?>);"  value="Cancel Bill" class="button"/>	
    <?php }?>
            </td>
        </tr>
    </tfoot>
</table>
</div>
<div style="clear:both"></div>

<input type="hidden" name="sales_id" id="sales_id" value="<?php echo $id;?>" /> 
<input type = "hidden" name =  "sc_id" id = "sc_id" value = "<?php echo $salesdetails->sc_id;  ?>" />
<input type = "hidden" name =  "bill_number" id = "bill_number" value = "<?php echo $bill_details->bill_id ; ?>" />


<div class="clear">&nbsp;</div>
</form>




</form>
<form action="<?php echo site_url('account/entry/print_bill');?>" method="post" name="generateBill" id="generateBill">

<?php if($id > 0 && $salesdetails->sales_status == 2  && $bill_details->bill_id > 0 && $bill_details->bill_status == 1 && $this->session->userdata('usergroup_id')==1  ){?>
			<?php 
			$this->load->model(array('account/mdl_ledgerassign'));
			$ledger_1 = $this->mdl_ledgerassign->getBillingHeads($this->session->userdata('sc_id'),'LDGR1');
			$LDGR1_ledger_id = $ledger_1->ledger_id;
			$LDGR1_ledger_assign_type = $ledger_1->ledger_assign_type;
			
			$ledger_2 = $this->mdl_ledgerassign->getBillingHeads($this->session->userdata('sc_id'),'LDGR2');
			$LDGR2_ledger_id = $ledger_2->ledger_id;
			$LDGR2_ledger_assign_type = $ledger_2->ledger_assign_type;
			
			$ledger_3 = $this->mdl_ledgerassign->getBillingHeads($this->session->userdata('sc_id'),'LDGR3');
			$LDGR3_ledger_id = $ledger_3->ledger_id;
			$LDGR3_ledger_assign_type = $ledger_3->ledger_assign_type;
			
			?>
			
			<input type="hidden" name="ledger_id[0]" value="<?php echo $LDGR2_ledger_id ;?>"/>
			<input type="hidden" name="ledger_id[1]" value="<?php echo $LDGR1_ledger_id;?>"/>
			<input type="hidden" name="cash_ledger_id" value="<?php echo $LDGR3_ledger_id;?>"/>
			<input type="hidden" name="ledger_dc[0]" value="<?php echo $LDGR1_ledger_assign_type;?>"/>
			<input type="hidden" name="ledger_dc[1]" value="<?php echo $LDGR2_ledger_assign_type;?>"/>
			<input type="hidden" name="dr_amount[0]" value="<?php echo ($salesdetails->total_price - $discount_amount)+ (( $salesdetails->tax / 100 ) * ($salesdetails->total_price - $discount_amount));?>"/>
			<input type="hidden" name="cr_amount[1]" value="<?php echo ($salesdetails->total_price - $discount_amount)+ (( $salesdetails->tax / 100 ) * ($salesdetails->total_price - $discount_amount));?>"/>
			<input type="hidden" name="entry_number" value="" />
			<input type="hidden" name="reverse_entry" value="1" />			
			<input type="hidden" name="entry_date" value="<?php echo date('d/m/Y');?>" />
			<input type="hidden" name="sales_id" value="<?php echo $id;?>" />
			<input style="float:right" type="submit" id="bill_sales_id" name="bill_sales_id" value="Cancel Bill" class="button" />
			
	<?php } ?>


</form>


<form action="<?php echo site_url('account/entry/print_bill');?>" method="post" name="generateBill" id="generateBill">

<?php if ($id > 0 && $salesdetails->sales_status == 2 && $bill_details->bill_id =='') {?>
			<?php 
			$this->load->model(array('account/mdl_ledgerassign'));
			$ledger_1 = $this->mdl_ledgerassign->getBillingHeads($this->session->userdata('sc_id'),'LDGR1');
			$LDGR1_ledger_id = $ledger_1->ledger_id;
			$LDGR1_ledger_assign_type = $ledger_1->ledger_assign_type;
			
			$ledger_2 = $this->mdl_ledgerassign->getBillingHeads($this->session->userdata('sc_id'),'LDGR2');
			$LDGR2_ledger_id = $ledger_2->ledger_id;
			$LDGR2_ledger_assign_type = $ledger_2->ledger_assign_type;
			
			$ledger_3 = $this->mdl_ledgerassign->getBillingHeads($this->session->userdata('sc_id'),'LDGR3');
			$LDGR3_ledger_id = $ledger_3->ledger_id;
			$LDGR3_ledger_assign_type = $ledger_3->ledger_assign_type;
			
			?>
			
			<input type="hidden" name="ledger_id[0]" value="<?php echo $LDGR1_ledger_id;?>"/>
			<input type="hidden" name="ledger_id[1]" value="<?php echo $LDGR2_ledger_id;?>"/>
			<input type="hidden" name="cash_ledger_id" value="<?php echo $LDGR3_ledger_id;?>"/>
			<input type="hidden" name="ledger_dc[0]" value="<?php echo $LDGR1_ledger_assign_type;?>"/>
			<input type="hidden" name="ledger_dc[1]" value="<?php echo $LDGR2_ledger_assign_type;?>"/>
			<input type="hidden" name="dr_amount[0]" value="<?php echo ($salesdetails->total_price - $discount_amount)+ (( $salesdetails->tax / 100 ) * ($salesdetails->total_price - $discount_amount));?>"/>
			<input type="hidden" name="cr_amount[1]" value="<?php echo ($salesdetails->total_price - $discount_amount)+ (( $salesdetails->tax / 100 ) * ($salesdetails->total_price - $discount_amount));?>"/>
			<input type="hidden" name="entry_number" value="" />			
			<input type="hidden" name="entry_date" value="<?php echo date('d/m/Y');?>" />
			<input type="hidden" name="sales_id" value="<?php echo $id;?>" />
			<input type="hidden" name="reverse_entry" value="0" />
			<input style="float:right" type="submit" id="bill_sales_id" name="bill_sales_id" value="Generate Bill" class="button" />
			
	<?php } ?>


</form>
<?php //onKeyup="isInteger(this.value)" ?>
</div>