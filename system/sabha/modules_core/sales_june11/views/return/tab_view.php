<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
.bill-parts-total td{ border-top:#999 1px solid; padding:5px 10px!important; font-weight:bold;}
.bill-calc-td td{font-weight:bold;}
</style>
<?php $this->load->view('dashboard/system_messages');?>
<div style="clear:both"></div>
<div id="purchaseContent">
<form action="" method="post" name="salesreturnForm" id="salesreturnForm">
<div style="float:left; width:33%" class="toolbar1">
<table width="100%">
	<col width="35%" /><col />  
    <tr>
    	<th><label><?php echo $this->lang->line('bill_type'); ?>: </label></th>
        <td><label><?php echo $this->mdl_mcb_data->getStatusDetails($sales_return_details->bill_type, 'bill_type');?></label></td>
    </tr>     
    <tr>
    	<th><label><?php echo $this->lang->line('bill_number'); ?>: </label></th>
        <td><label><?php echo $sales_return_details->bill_number;?></label></td>
    </tr> 
	<tr>
    	<th><label><?php echo $this->lang->line('sales_return_number'); ?>: </label></th>
        <td><label><?php echo ($sales_return_details->sales_return_number)?$sales_return_details->sc_code.'SR/'.$sales_return_details->sales_return_number:'';?></label></td>
    </tr>     
	<tr>
    	<th><label><?php echo $this->lang->line('serial_ime_number'); ?>: </label></th>
        <td><label><?php echo $sales_return_details->call_serial_no;?></label></td>
    </tr> 
     <tr>
		<th><label><?php echo $this->lang->line('service_center'); ?>: </label></th>
        <td><label><?php echo $sales_return_details->sc_name; ?></label></td>
    </tr>
    <tr>
		<th><label><?php echo $this->lang->line('customer_name'); ?>: </label></th>
         <td><label><?php echo $sales_return_details->customer_name;?></label></td>
    </tr>
     <tr>
		<th><label><?php echo $this->lang->line('customer_address'); ?>: </label></th>
         <td><label><?php echo $sales_return_details->customer_address;?></label></td>
    </tr>
 
     <!-- <tr>
             <th><label><?php echo $this->lang->line('cust_vat'); ?>: </label></th>
         <td><label><?php echo $sales_return_details->customer_vat;?></label></td>
         </tr> -->
	<tr>
		<th><label><?php echo $this->lang->line('sales_return_date'); ?>: </label></th>
        <td><label><?php echo format_date(strtotime($sales_return_details->sales_return_date));?></label></td>
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
			<td><?php echo $i;?></td>
			<td><label><?php echo $details->part_number;?></label></td>
            
            <td style="text-align: left;"><label><?php echo $details->part_desc;?></label></td>
            <td style="text-align: center; "><label><?php echo $details->company_title;?></label></td>
            
			<td style="text-align: center;"><label><?php echo $details->part_quantity;?></label></td>
			<td style="text-align:center"><label><?php echo $details->part_return_quantity;?></label></td>
			<td style="text-align: right;"><label><?php echo sprintf('%.2f',$details->part_return_quantity * $details->sales_return_calc_price);?></label></td>           
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
    <tr class="bill-calc-td"> 
        <td colspan="6" style="text-align:right!important">VAT (13%):</td>
        <td style="text-align:right!important"><span id="spn_vat_price"><?php echo $sales_return_details->sales_return_tax_price;?></span></td>        
    </tr>
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
<?php if($sales_return_details->sales_return_status==1){?>
	<input type="button" name="btnprint" onclick="printSalesReturn('<?php echo $sales_return_details->sales_return_id;?>')" value="<?php echo $this->lang->line('print');?>" class="button" />
<?php } ?>    
	<input type="button" name="btnprint" onclick="closeform()" value="<?php echo $this->lang->line('close');?>" class="button" />
</form>
</div>