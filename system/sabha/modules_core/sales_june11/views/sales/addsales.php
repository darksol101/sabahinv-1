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
    tr.sale-total td{border-top:#999 1px solid;}
    tfoot tr td input {width: 111px;}
    #part_rate{ min-width: 50px;}
    #discount_type{ width: 124px;}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet"	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.autocomplete.css" />
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.min.js"	type="text/javascript"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.position.min.js"></script>
<script	type="text/javascript"	src="<?php echo base_url(); ?>assets/jquery/jquery.ui.autocomplete.min.js"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js"	type="text/javascript"></script>

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
		
		$( "#part_number" ).autocomplete({			 
		source: function( request, response ) {
          		 if ($("#service_center").val() < 1){
					 	$('#service_center').validationEngine('showPrompt', '* This field is required', 'error', 'topRight', true);
						$("#part_number").val('');
					 }
				else{	 
				$.getJSON( "<?php echo site_url();?>sales/getjsonparts", {
            		term:  request.term ,sc_id:$('#service_center').val()
          		}, response ).done(function(data) {
							
				});}
        	},
			minLength: 2,
			delay: 100,
			select: function( event, ui ) {
				$("#part_number").val(ui.item.label);
				$('#part_description').val(ui.item.pdesc);
				$('#part_rate').val(ui.item.price);
				$("#part_id").val(ui.item.part_id);
                $('#unit').html(ui.item.unit);
			},
			open: function() {
				
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			focus: function(event, ui) {
			
				$("#part_number").val(ui.item.label);
				$('#part_description').val(ui.item.pdesc);
				$('#part_rate').val(ui.item.price);
				$("#part_id").val(ui.item.part_id);
                $('#unit').html(ui.item.unit);
			},
			change: function(event){
		
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
	});
	
</script>

<script language="javascript">
$(document).ready(function(){

    var sc_id = "<?php echo $this->session->userdata('sc_id');?>"

    if(sc_id > 0){
       $("#service_center option").each(function(){
           if(sc_id == $(this).val()){
               $(this).attr('selected','selected');
           }
       });
    }

    $("#part_number").val('');
	$('#part_description').val('');
	$("#cancel_part").hide() ;
	<?php if($salesdetails->call_uid || $salesdetails->warranty_sale == 1){?>
	calculatetotal();
	<?php }?>
	$("#purchaseForm").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){ 		document.purchaseForm.action='<?php echo site_url('sales/sale');?>'; document.purchaseForm.submit();}}  
	});	
<?php if( $salesdetails->sales_status == 1 &&  $salesdetails->grand_total < 5000  ){ ?>
		abb_show();
<?php }else{ ?>  
	
	$("#abb_con").hide();
<?php }?>
checkDiscount();
});

function resetScValue(){
}
</script>


<?php $this->load->view('dashboard/system_messages');?>
<div style="clear:both"></div>
<div id="purchaseContent">
<form action="<?php echo site_url('sales/sale');?>" method="post" name="purchaseForm" id="purchaseForm">
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
         <td><input type="text" name="customer_name" id="customer_name" class ='text-input validate[required]'value = "<?php echo $salesdetails->customer_name;?>"/></td>
    </tr>
     <tr>
		<th><label><?php echo $this->lang->line('customer_address'); ?>: </label></th>
         <td><input type = 'text' name="customer_address" id="customer_address" class ='text-input validate[required]'value = "<?php echo $salesdetails->customer_address;?>"/></td>
    </tr>
     <tr>
		<!-- <th><label><?php echo $this->lang->line('cust_vat'); ?>: </label></th>
             <td><input type = 'text' name="cust_vat" id="cust_vat" class ='text-input validate[custom[integer],maxSize[10],custom[onlyNumberSp]]' value = "<?php echo $salesdetails->cust_vat;?>"/></tsd>
    </tr> -->
  
	<tr>
		<th><label><?php echo $this->lang->line('sales_note'); ?>: </label></th>
       <td><textarea name="sales_remarks" id="sales_remarks" class="" rows="4" cols="10"><?php echo $salesdetails->sales_remarks;?></textarea></td>
    </tr> 
	<tr>
		<th><label><?php echo $this->lang->line('sales_date'); ?>: </label></th>
        <td><input type="text" readonly="readonly" name="sales_date" id="sales_date" class="text-input datepicker" value="<?php echo format_date(strtotime($salesdetails->sales_date));?>" style="width: 70%" /></td>
    </tr>
   <tr id="abb_con"> 
   <td colspan = "2">
   <div id= "warning" >
   <label> Abbreviated Bill Is generated for this sale. Do you Want TI bill <input name="bill_type" type="checkbox" id="bill_type"  <?php if($salesdetails->bill_type==2){?> checked="checked" <?php }?>/></label>
   </div>
    </td>
   </tr>
    <tr>
        <td colspan="2" align ='left' >
       <?php if($salesdetails->sales_status < 2){?><input  type="submit" value="<?php echo $this->lang->line('save'); ?>" name="btn_submit" id="btn_submit" class="button" />
      <?php }?>
        <input  type="button" value="<?php echo $this->lang->line('close'); ?>" name="btn_submit" id="btn_close" class="button" onclick="closeform();" />
  
	<?php if ($bill_details->bill_id > 0 ){?>
        <input  type="button" id="print_bill" name="print_bill" onclick="printbill(<?php echo $id;?>);"  value="Print Bill" class="button"/>
	
		<?php }?>

            </td>
        </tr>
</table>
</div>

<div style="float:left; width:67%">
<?php if($salesdetails->sales_status<2){
if($salesdetails->warranty_sale == 0){
?>
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
        <td style="display:none;"><label><?php echo $this->lang->line('company');?>:</label></td>
    	<td><label><?php echo $this->lang->line('part_quantity');?>:</label></td>
    	<td><label><?php echo $this->lang->line('part_rate');?> </label></label> </td>
        <td></td>
	</tr>
	<tr>
        <td><input type="hidden" name="hdnsales_details_id" id="hdnsales_details_id" value="0" class="text-input" /><input type="text" onblur ='getPrice();';  name="part_number" id="part_number" value="" class="text-input" /></td>
        
        <td><input type="text" name="part_description" id="part_description" value="" class="text-input" readonly ="readonly"  /></td>
              <td style="display:none;"><?php echo $company_name ;?></td>
        <td><input type="text" style="width:100px !important;" name="part_quantity" id="part_quantity" value="" class="text-input validate[custom[onlyNumberSp]]" /> <i id="unit"></i>
        </td>
   
         <td><input type="text" name="part_rate" id="part_rate" value="" class="text-input "  readonly = 'readonly' /></td>
          
       
        <td><input type="button" id="add_part" name="add_part" onclick="checkpart();" value="Add" class="button" /></td><td><input type="button" id="cancel_part" name="cancel_part"  onclick= "cancelrow() " value="cancel" class="button" />
        </td>
        
	</tr>
    <input type="hidden" name="serial" id="serial" value="" />
    <input type="hidden" name="part_id" id="part_id" value="0" />
</table>
<?php } }?>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="1%" />
	<col />
	<col width="20%" />
	<col width="12%" />
	<col width="10%" />
	<col width="20%" />
		<?php if($salesdetails->sales_status<2){?>
	<col width="10%" />
	<col width="1%" />
		<?php }?>
    <thead>
        <tr>
            <th>Sno</th>  
            <th><?php echo $this->lang->line('part_no');?></th>
            <th><?php echo $this->lang->line('part_description');?></th>
            <th style="text-align:center; display:none;"><?php echo $this->lang->line('company');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('part_quantity');?></th>
            <th style="text-align:right;">Rate</th>
            <th style="text-align:right;">Sales Discount Rate</th>
            <th style="text-align : right"><?php echo $this->lang->line('total_price');?> </th>            
            <?php if($salesdetails->sales_status<2){?>
            <th></th> 
            <th></th>
			<?php }?>            
        </tr>
    </thead>
    <tbody id="rowdata">
    <?php 
	
	  $i=1;
    foreach($sales_parts as $details){
    	
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr<?php echo $trstyle;?>>
			<td class="sn_td"><input type="hidden" name="p_id[]" value="<?php echo $details->part_id;?>" class="text-input" /><input type="hidden" name="maker_id[]" value="<?php echo $details->maker_id;?>" class="text-input" /><span class="spn_counter"><?php echo $i;?></span></td>
			<td><input type="hidden" class="sales_details_id" name="sales_details_id[]" value="<?php echo $details->sales_details_id;?>" /><input type="hidden" id="" name="pnum[]" value="<?php echo $details->part_number;?>"  /><span class="lbl"><?php echo $details->part_number;?></span></td>
            
            <td style="text-align: left;"><input type="hidden" name="pdesc[]" value="<?php echo $details->part_description;?>" class="text-input" /><span class="lbl"><?php echo $details->part_description;?></span></td>
            <td style=" display:none; text-align: center; "><input type="hidden" name="comp[]" value="<?php echo $details->company_title;?>" class="text-input" /><span class="lbl"><?php echo $details->company_title;?></span></td>
            
			<td style="text-align: center;"><input type="hidden" name="pqty[]" value="<?php echo $details->part_quantity;?>" class="text-input" /><span class="lbl"><?php echo $details->part_quantity;?></span></td>

            <td style="text-align: right;"><input type="hidden" name="prate[]" value="<?php echo $details->part_rate;?>" class="text-input" /><span class="lbl"><?php echo $details->part_rate;?></span></td>

            <td style="text-align: right;"><input type="hidden" name="drate[]" value="<?php echo $details->dis_rate;?>" class="text-input" /><span class="lbl"><?php echo $details->dis_rate;?></span></td>

            <td style="text-align: right;"><span class="lbl"><?php echo sprintf('%.2f',$details->part_quantity * $details->dis_rate);?></span><input type="hidden" name = "call_ids[]" value = "<?php echo  $details->call_id; ?>" id = "call_ids"/></td>
            
           
            <?php if($salesdetails->sales_status <2){
            if ($salesdetails->warranty_sale !=1){?>
            <td><a class="editpart"><?php echo icon('edit','edit','png');?></a></td>
            <td><a class="deletepart"><?php echo icon("delete","Delete","png");?></a></td><?php }} 
            else { //echo '<td></td> <td> </td>';
            }?>
           
           
        </tr>
        <?php $i++;}?>
    </tbody>
	<tfoot>

        <tr style="background:#CCC;" class="sale-total"> 	 
            <td colspan="6" style="text-align:right!important">Sub total:</td>
            <td style="text-align:right"><input type = 'text' style="text-align:right" readonly="readonly" name="total_price" id="total_price" class ='text-input'value = "<?php echo $salesdetails->total_price;?>"/></td>
            <?php if($salesdetails->sales_status <2){?><td colspan="3"></td><?php }?>
        </tr>
    <tr > 	   
        <td colspan="6" style="text-align:right!important"><?php echo $this->lang->line('discount_type'); ?>: </td>
        <td style="text-align:right"><?php echo $discount_type;?></td>           
        <?php if($salesdetails->sales_status <2){?><td colspan="2"></td><?php }?>
    </tr>
     <tr > 	   
	   <td colspan="6" style="text-align:right!important"><?php echo $this->lang->line('discount_value'); ?>: </td>
      <td style="text-align:right">
      <input type = 'text' style="text-align:right" onkeyup= 'calculatetotal();' name="discount" id="discount" class ='text-input validate[custom[onlyNumberSp]]'value = "<?php echo $salesdetails->discount_amount;?>"/></td>
           
          <?php if($salesdetails->sales_status <2){?><td colspan="2"></td><?php }?>
    </tr>
     <tr > 
	   <?php if($salesdetails->discount_type == 1)
	  			 { $discount_amount = (($salesdetails->discount_amount / 100)*$salesdetails->total_price) ;
				   $total_price = $salesdetails->total_price - $discount_amount;
	  			 } 
	  		 elseif ($salesdetails->discount_type == 2) 
	  		 	{ 
	  		 		$discount_amount = $salesdetails->discount_amount;
	  		 		$total_price = $salesdetails->total_price - $salesdetails->discount_amount;
	  		 	}
	  		 else{
	  		 	$total_price = $salesdetails->total_price ;  
	  		 	$discount_amount = 0;
	  		 	} ?>
	  
	   <td colspan="6" style="text-align:right!important">Total Price:</td>
      <td style="text-align:right!important"><input type = 'text' readonly="readonly" style="text-align:right" name="discounted_total" id="discounted_total" class ='text-input'value = "<?php echo $total_price; ?>"/></td>
         <?php if($salesdetails->sales_status <2){?><td colspan="2"></td><?php }?>

    </tr>
    <tr> 
	   <td colspan="6" style="text-align:right!important">VAT (13%):</td>
      <td style="text-align:right"><input type = 'text' readonly = "readonly"  onkeyup= 'calculatetotal();' name="tax" id="tax" class ='text-input' style="text-align:right"value = "<?php echo round((($salesdetails->total_price - $discount_amount) * 0.13),2);?>"/></td>
      <?php if($salesdetails->sales_status <2){?><td colspan="2"></td><?php }?>

    </tr>
    <tr> 	   
	   <td colspan="6" style="text-align:right!important">Grand Total:</td>
      <td style="text-align:right"><input type = 'text' style="text-align:right" readonly = "readonly" name="grand_total" id="grand_total" class ='text-input 'value = "<?php echo $salesdetails->grand_total; ?>"/></td>
                  
       <?php if($salesdetails->sales_status < 2){?><td colspan="2"></td><?php }?>
    </tr>

<?php if($id > 0 && $bill_details->bill_id ==''){?>
<script>
jQuery.fn.ForceNumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 46 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105)
                );
        });
    });
};

$(document).ready(function () {
  $("#received").ForceNumericOnly();
  $("#received").blur(function (e) {
    var rec = $(this).val();
   
    var grand_total = $("#grand_total").val();
    var returns = (rec - grand_total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    $("#returns").val(returns);
  });
});

    </script>


    <tr>
      <td colspan="5"></td>
      <td style="background:#D6D6D6;text-align:right!important">Received:</td>
      <td style="background:#D6D6D6;"><input type="text" class ='text-input validate[maxSize[10]]' value="" style="text-align:right!important" id="received"></td>
      <td style="background:#D6D6D6;" colspan="3"></td>
    </tr>
    <tr>
      <td colspan="5"></td>
      <td style="background:#D6D6D6;text-align:right!important">Return:</td>
      <td style="background:#D6D6D6;"><input type="text" readonly="readonly" class ='text-input' id="returns" value="0.00" style="text-align:right!important"></td>
      <td style="background:#D6D6D6;" colspan="3"></td>
    </tr>
  <?php }?>

  </tfoot>
</table>
</div>
<div style="clear:both"></div>

<input type="hidden" name="sales_id" id="sales_id" value="<?php echo $id;?>" /> 
<input type = "hidden" name =  "sc_id" id = "sc_id" value = "<?php echo $salesdetails->sc_id;  ?>" />
<input type = "hidden" name =  "bill_number" id = "bill_number" value = "<?php echo $bill_details->bill_id ; ?>" />
<input type = "hidden" name =  "call_id" id = "call_id" value = "<?php echo $salesdetails->call_id ;  ?>" />
<input type="hidden" name="call_serial_no" id="call_serial_no" value="<?php echo $salesdetails->call_serial_no;?>" />
<input type="hidden" name="call_uid" id="call_uid" value="<?php echo $salesdetails->call_uid;?>" />
<input type="hidden" name="model_number" id="model_number" value="<?php echo $salesdetails->model_number;?>" /> 
<input type="hidden" name="warranty_sale" id="warranty_sale" value="<?php echo $salesdetails->warranty_sale;?>" /> 

</form>

<?php if ($id > 0 && $bill_details->bill_id =='') {?>
<form action="<?php echo site_url('account/entry/create_bill');?>" method="post" name="generateBill" id="generateBill">
			
			<input type="hidden" name="sales_id" value="<?php echo $id;?>" />
			<input type="hidden" name="reverse_entry" value="0" />
			<input style="float:right;margin-right:150px;" type="button" onclick="GenerateBill()" id="bill_sales_id" name="bill_sales_id" value="Generate Bill" class="button" />
</form>
<?php } ?>
</div>