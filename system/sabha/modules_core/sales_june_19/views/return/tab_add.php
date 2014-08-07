<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
table td img.ui-datepicker-trigger { padding-left: 5px;}
.editpart{cursor:pointer;}
.deletepart{cursor:pointer;}
.editactive{background: none repeat scroll 0 0 #FFF5E7;}
.ui-autocomplete {max-height: 200px;overflow-y: auto;overflow-x: hidden;padding-right: 20px;}
* html .ui-autocomplete {height: 200px;}
.toolbar1 input[type="text"] {width:93%!important;}
table.toolbar1 td{ padding:0px 2px;!important;}
form table.toolbar1 label{ padding:0px!important ;}
tr.sale-total td{border-top:#999 1px solid;}
input[readonly="readonly"],input[readonly="readonly"]{ background-color:#eee!important;}
input#sales_return_date{ width:80%!important}
select{ width:100%!important}

#spn_ledger,#table-loading{
	position: relative;
}
#spn_ledger .loading, #table-loading .loading{
	position: absolute;
	left: 0px;
	top: 0;
	width: 100%;
	height: 30px;
	margin: 0 auto;
	text-align: center;
}
.bill-parts-total td{ border-top:#999 1px solid; padding:5px 10px!important; font-weight:bold;}
.bill-calc-td td{font-weight:bold;}
input:focus,select:focus,textarea:focus{ background-color:yellow!important; }
.return_qty{ width:20%!important; text-align:center;}
</style>
<script type="text/javascript">
	$(function() {
		$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
		});
		
		var appendTo = $( ".selector" ).autocomplete( "option", "appendTo" );
		$( "#bill_number" ).autocomplete({			 
			//source: "<?php echo site_url();?>sales/salesreturn/getjsonbills",
			source: function( request, response ) {
          	$.getJSON( "<?php echo site_url();?>sales/salesreturn/getjsonbills", {
            	term: extractLast( request.term ),bill_type:$('#bill_type').val()
          		}, response ).done(function(data) {
							if(data.length==0){
								ResetReturnForm();								
							}
						  });
        	},
			minLength: 2,
			delay: 100,
			select: function( event, ui ) {
				if(ui.item.bill_id>0){
					$("#sc_id").val(ui.item.sc_id);
					$("#spn_sc_id").html(ui.item.sc_name);
					$("#bill_id").val(ui.item.bill_id);
					$("#a_bill_type").val(ui.item.bill_type);
					$("#sales_type").val(ui.item.sales_type);					
					$("#a_ledger_id").val(ui.item.ledger_id);
					$("#customer_name").val(ui.item.bill_customer_name);
					$("#customer_address").val(ui.item.bill_customer_address);
					$("#customer_vat").val(0);	
					$("#spn_call_serial_no").html(ui.item.call_serial_no);
					$("#call_serial_no").val(ui.item.call_serial_no);		
					$("#total_price").val(ui.item.total_price);
					$("#spn_total_price").html(ui.item.total_price);
					$("#spn_vat_price").html(ui.item.tax_amount);
					$("#spn_vat_price").html(ui.item.tax_amount);
					$("#spn_discount_type").html(((ui.item.discount_type==1)?'('+ui.item.discount_amount+'%)':''));
					$("#spn_discount_price").html(ui.item.discount_value);
					$("#discount_type").val(ui.item.discount_type);
					$("#discount_amount").val(ui.item.discount_amount);					
					
					$("#spn_total_taxable_price").html(((ui.item.total_price-ui.item.discount_value)/100*100).toFixed(2));
					$("#spn_grand_total").html(ui.item.bill_rounded_grand_total_price);
					getBillPartDetails(ui.item.bill_id);
					$('#part_description').val(ui.item.pdesc);
					$('#part_rate').val(ui.item.price);
				}
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			focus: function(event, ui) {				
			},
			change: function(event){
				
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
	});
	function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
	function ResetReturnForm(){
		$("#sc_id").val(0);
		$("#bill_id").val('');
		$("#a_bill_type").val(0);
		$("#sales_type").val('');
		$("#sales_return_number").val('');
		$("#a_ledger_id").val(0);
		$("#spn_sales_return_number").html('');				
		$("#customer_name").val('');
		$("#customer_address").val('');
		$("#customer_vat").val('');	
		$("#spn_call_serial_no").html('');
		$("#call_serial_no").val('');		
		$("#total_price").val('');
		$("#spn_total_price").html('');
		$('#part_description').val('');
		$('#part_rate').val('');
		$('#rowdata').html('');
	}
	function generateSalesReturn(){
		if(confirm('Are you sure to generate sales return?This process can not be reversed.')){
			document.generateForm.submit();
		}
	}
</script>

<script language="javascript">
$(document).ready(function(){
	$('#bill_number').val('');						   
	$('#bill_number').focus();
	$("#salesreturnForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){  document.salesreturnForm.submit();}}  
	});	
	$('#bill_type').change(function(){
		if($('#bill_id').val()>0){
			$('#bill_number').focus();
			$('#bill_number').val('');							
			ResetReturnForm();
		}
	})
});
</script>

<?php $this->load->view('dashboard/system_messages');?>
<div style="clear:both"></div>
<div id="purchaseContent">
<form action="" method="post" name="salesreturnForm" id="salesreturnForm">
<div style="float:left; width:33%" class="toolbar1">
<table width="100%">
	<col width="35%" /><col />  
    <!-- <tr>
    	<th><label><?php echo $this->lang->line('bill_type'); ?>: </label></th>
        <td><?php echo $bill_type_select;?></td>
    </tr>  -->    
    <tr>
    	<th><label><?php echo $this->lang->line('bill_number'); ?>: </label></th>
        <td>
        <?php if($sales_return_details->sales_return_id>0){?>
        <input  type="hidden" name="bill_number" id="bill_number" class="text-input" value="<?php echo $sales_return_details->bill_number;?>" tabindex="2" />
        <label><?php echo $sales_return_details->bill_number_full;?></label>
        <?php }else{?>
        <input  type="text" name="bill_number" id="bill_number" class="text-input" value="<?php echo $sales_return_details->bill_number;?>" tabindex="2" />
        <?php } ?>
        </td>
    </tr> 
    <?php if($sales_return_details->sales_return_number){?>
	<tr>
    	<th><label><?php echo $this->lang->line('sales_return_number'); ?>: </label></th>
        <td><label><span id="spn_sales_return_number"><?php echo ($sales_return_details->sales_return_number)?$sales_return_details->sc_code.'SR/'.$sales_return_details->sales_return_number:'';?></span></label></td>
    </tr>     
    <?php } ?>
	<tr>
    	<th><label><?php echo $this->lang->line('serial_ime_number'); ?>: </label></th>
        <td><label><span id="spn_call_serial_no"><?php echo $sales_return_details->call_serial_no;?></span></label></td>
    </tr> 
     <tr>
		<th><label><?php echo $this->lang->line('service_center'); ?>: </label></th>
        <td><label><span id="spn_sc_id"><?php echo $sales_return_details->sc_name;?></span></label><input type="hidden" name="sc_id" id="sc_id" value="<?php echo $sales_return_details->sc_id;?>"  /></td>
    </tr>
    <tr>
		<th><label><?php echo $this->lang->line('customer_name'); ?>: </label></th>
         <td><input type="text" name="customer_name" readonly="readonly" id="customer_name" class ='text-input'value="<?php echo $sales_return_details->customer_name;?>"/></td>
    </tr>
     <tr>
		<th><label><?php echo $this->lang->line('customer_address'); ?>: </label></th>
         <td><input type = "text" name="customer_address" readonly = "readonly" id="customer_address" class ='text-input'value="<?php echo $sales_return_details->customer_address;?>"/></td>
    </tr>
     <!-- <tr>
     		<th><label><?php echo $this->lang->line('cust_vat'); ?>: </label></th>
         <td><input type = 'text' name="customer_vat" id="customer_vat" readonly = "readonly" class ='text-input validate[custom[integer],maxSize[10]]' value="<?php echo $sales_return_details->customer_vat;?>" /></td>
         </tr> -->
	<tr>
		<th><label><?php echo $this->lang->line('sales_return_date'); ?>: </label></th>
        <td><input type="text" readonly="readonly" name="sales_return_date" id="sales_return_date" class="text-input datepicker" value="<?php echo format_date(strtotime($sales_return_details->sales_return_date));?>" style="width: 70%" /></td>
    </tr>
    <tr>
		<th><label><?php echo $this->lang->line('sales_return_remarks'); ?>: </label></th>
       <td><textarea name="sales_return_remarks" id="sales_return_remarks" class="" rows="4" cols="10"><?php echo $sales_return_details->sales_return_remarks;?></textarea></td>
    </tr> 
</table>
</div>

<div style="float:right; width:65%">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="1%" />
	<col />
	<col width="20%" />
	<col width="12%" />
	<col width="10%" />
	<col width="20%" />
	<col width="10%" />
    <thead>
        <tr>
            <th><?php echo $this->lang->line('sn');?></th>  
            <th><?php echo $this->lang->line('part_no');?></th>
            <th><?php echo $this->lang->line('part_description');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('stock_type');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('part_quantity');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('part_return_quantity');?></th>
            <th style="text-align : right"><?php echo $this->lang->line('total_price');?> </th>            
        </tr>
    </thead>
    <tbody id="rowdata"> 
    <?php 
	$i=1;
    foreach($sales_parts_details as $details){    	
		$trstyle=$i%2==0?' class="even" ': ' class="odd"';
		?>
		<tr<?php echo $trstyle;?>>
			<td><input type="hidden" id="" name="sales_return_details_id[]" value="<?php echo $details->sales_return_details_id;?>" /><input type="hidden" id="" name="part_id[]" value="<?php echo $details->part_id;?>" /><?php echo $i;?></td>
			<td><input type="hidden" id="" name="pnum[]" value="<?php echo $details->part_number;?>"  /><span class="lbl"><?php echo $details->part_number;?></span></td>
            
            <td style="text-align: left;"><input type="hidden" name="pdesc[]" value="<?php echo $details->part_desc;?>" class="text-input" /><span class="lbl"><?php echo $details->part_desc;?></span></td>
            <td style="text-align: center; "><input type="hidden" name="comp[]" value="<?php echo $details->company_id;?>" class="text-input" /><span class="lbl"><?php echo $details->company_title;?></span></td>
            
			<td style="text-align: center;"><input type="hidden" name="pqty[]" value="<?php echo $details->part_quantity;?>" class="text-input validate[custom[integer]]" /><span class="lbl"><?php echo $details->part_quantity;?></span></td>
			<td style="text-align:center"><input type="text" class="text-input return_qty" name="p_return_pqty[]" value="<?php echo $details->part_return_quantity;?>" /></td>
			<td style="text-align: right;"><input type="hidden" name="prate[]" value="<?php echo $details->sales_return_calc_price;?>" class="text-input" /><span class="lbl"><?php echo sprintf('%.2f',$details->part_return_quantity * $details->sales_return_calc_price);?></span></td>           
        </tr>
        <?php $i++;}?>   
    </tbody>
	<tfoot> 
    <?php
	?>
    	<tr class="bill-parts-total" style="background:#CCC;">
        	<td colspan="6" style="text-align:right!important; font-weight:bold">Sub Total:</td>
        	<td style="text-align:right!important;"><input type="hidden" name="total_price" id="total_price" value="<?php echo $sales_return_details->sales_return_total_price?>" /><span id="spn_total_price"><?php echo $sales_return_details->sales_return_total_price?></span></td>
       	</tr>  
	    <tr class="bill-calc-td"> 	   
            <td colspan="6" style="text-align:right!important"><?php echo $this->lang->line('discount'); ?><span id="spn_discount_type"><?php echo ($sales_return_details->sales_return_discount_type==1)?'('.sprintf('%.2f',$sales_return_details->sales_return_discount_amount).' % )':''?></span>: <input type="hidden" name="discount_type" id="discount_type" value="<?php echo $sales_return_details->sales_return_discount_type;?>" /></td>
            <td style="text-align:right!important"><span id="spn_discount_price"><?php echo $sales_return_details->sales_return_discounted_price;?></span><input type="hidden" name="discount_amount" id="discount_amount" value="<?php echo $sales_return_details->sales_return_discount_amount;?>" /></td>    
    </tr>
    <tr class="bill-calc-td"> 
        <td colspan="6" style="text-align:right!important">Total Price:</td>
        <td style="text-align:right!important"><span id="spn_total_taxable_price"><?php echo sprintf("%.2f",($sales_return_details->sales_return_total_price-$sales_return_details->sales_return_discounted_price));?></span></td>        
    </tr>
   <!--  <tr class="bill-calc-td"> 
        <td colspan="6" style="text-align:right!important">VAT (13%):</td>
        <td style="text-align:right!important"><span id="spn_vat_price"><?php echo $sales_return_details->sales_return_tax_price;?></span></td>        
    </tr> -->

    <tr class="bill-calc-td"> 	   
        <td colspan="6" style="text-align:right!important">Grand Total:</td>
        <td style="text-align:right!important"><span id="spn_grand_total"><?php echo $sales_return_details->sales_return_rounded_grand_total_price;?></span></td>          
    </tr>     
       </tfoot>
</table>
<div id="table-loading"></div>
</div>
<div style="clear:both"></div>
<input type="hidden" name="bill_id" id="bill_id" value="<?php echo $sales_return_details->bill_id;?>" />
<input type="hidden" name="a_bill_type" id="a_bill_type" value="<?php echo $sales_return_details->bill_type;?>" />
<input type="hidden" name="sales_return_number" id="sales_return_number" value="<?php echo $sales_return_details->sales_return_number;?>" />
<input type="hidden" name="call_serial_no" id="call_serial_no" value="<?php echo $sales_return_details->call_serial_no;?>" />

<?php if($sales_return_details->sales_return_status==0){?>
<input type="submit" name="submit" value="<?php echo $this->lang->line('save');?>" class="button" />
<?php }?>
<?php if($sales_return_details->sales_return_id>0 && $sales_return_details->sales_return_status<1){?>
	<input type="button" name="btngenerate" onclick="generateSalesReturn()" value="<?php echo $this->lang->line('generate');?>" class="button" />
<?php } ?>    
<?php if($sales_return_details->sales_return_status==1){?>
	<input type="button" name="btnprint" onclick="printSalesReturn('<?php echo $sales_return_details->sales_return_id;?>')" value="<?php echo $this->lang->line('print');?>" class="button" />
<?php } ?>    
	<input type="button" name="btnprint" onclick="closeform()" value="<?php echo $this->lang->line('close');?>" class="button" />
</form>
<form method="post" action="<?php echo site_url('sales/salesreturn/generate'); ?>" name="generateForm" id="generateForm">
<input type="hidden" name="generate" id="generate" value="1" class="button" />
<input type="hidden" name="g_sales_return_id" id="g_sales_return_id" value="<?php echo $sales_return_details->sales_return_id;?>" />
<input type="hidden" name="service_center_id" id="service_center_id" value="<?php echo $sales_return_details->sc_id;?>" />
<?php if($sales_return_details->sales_return_id>0 && $sales_return_details->sales_return_status<1){
     foreach($sales_parts_details as $details){    ?>
     <input type="hidden" id="" name="part_id[]" value="<?php echo $details->part_id;?>" />
     <input type="hidden" id="" name="sales_return_details_id[]" value="<?php echo $details->sales_return_details_id;?>" />
     <input type="hidden" name="bad_part_number[]" value="<?php echo  $details->part_number;?>" />
     <input type="hidden" name="bad_part_quantity[]" value="<?php echo  $details->part_return_quantity;?>" />
     <input type="hidden" name="company_id[]" value="<?php echo  $details->company_id;?>" />     
	 <?php } } ?>
</form>
</div>