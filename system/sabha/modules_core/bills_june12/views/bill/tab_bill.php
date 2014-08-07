<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script type="text/javascript">
function closeform()
{
	window.location='<?php echo base_url();?>bills/index/?tp='+<?php echo $bill->bill_type;?>;
}
</script>
<style type = text/css>
tr.sale-total td{border-top:#999 1px solid;}
</style>
<div style="float:left;width:30%" class="toolbar1">
    <form onsubmit="return false">
    <table width="100%" class="">
    <col width="30%" /><col  />
    <tr>
    	<th><label><?php echo $this->lang->line('bill_number'); ?>: </label></th>
        <td><label><?php echo $bill->sc_code.($bill->bill_type==1?'SI':'TI').'/'.$bill->bill_number;?></label> </td>
    </tr>
    <tr>
		<th><label><?php echo $this->lang->line('service_center'); ?>: </label></th>
        <td><label><?php echo $bill->sc_name; ?></label></td>
    </tr>
    <tr>
   	<th><label><?php echo $this->lang->line('bill_type'); ?>:</label></th>
        <td><label><?php echo $this->mdl_mcb_data->getStatusDetails($bill->bill_type,'bill_type');?></label></td>
   </tr>
     <tr>
		<th><label><?php echo $this->lang->line('customer_name'); ?>: </label></th>
         <td><label><?php echo $bill->bill_customer_name;?></label></td>
    </tr>
     <tr>
		<th><label><?php echo $this->lang->line('customer_address'); ?>: </label></th>
         <td><label><?php echo $bill->bill_customer_address;?></label></td>
    </tr>
     <!-- <tr>
          <th><label><?php echo $this->lang->line('cust_vat'); ?>: </label></th>
          <td><label><?php echo $bill->bill_customer_vat;?></label></td>
          </tr> --> 
	<tr>
		<th><label><?php echo $this->lang->line('bill_date'); ?>: </label></th>
        <td><label><?php echo format_date(strtotime($bill->bill_sale_date));?></label></td>
    </tr>
    </table>
    <input type ="hidden" name="bill_id" id="bill_id" value="<?php echo $bill->bill_id ; ?>" />
    </form>
</div>
<div style="float:right;width:65%">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="1%" />
	<col width="20%"/>
	<col width="20%" />
	<col width="20%" />
  <col width="10%" />
	<col width="10%" />
    <thead>
        <tr>
            <th>Sno</th>  
            <th><?php echo $this->lang->line('part_no');?></th>
            <th style="text-align:right"><?php echo $this->lang->line('part_rate');?></th>
            <th><?php echo $this->lang->line('part_description');?></th>
            <th style="text-align:center"><?php echo $this->lang->line('part_quantity');?></th>
            <th style="text-align : right"><?php echo $this->lang->line('total_price');?> </th>            
        </tr>
    </thead>
    <tbody id="rowdata">
    <?php 
	
	$i=1;
	foreach($bill_part_details as $details){
    	
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
		<tr<?php echo $trstyle;?>>
			<td class="sn_td"><?php echo $i;?></td>
			<td><span class="lbl"><?php echo $details->part_number;?></span></td>            
			<td style="text-align:right"><?php echo $details->part_rate;?></td>            
            <td style="text-align: left;"><span class="lbl"><?php echo $details->part_desc;?></span></td>
			<td style="text-align: center;"><span class="lbl"><?php echo $details->part_quantity;?></span></td>			
			<td style="text-align: right;"><span class="lbl"><?php echo sprintf('%.2f',$details->part_quantity*$details->part_rate);?></span></td>        
        </tr>
        <?php $i++;}?>
    </tbody>
	<tfoot>
        <tr style="background:#CCC;" class="sale-total"> 	   
            <td colspan="5" style="text-align:right!important"><strong><?php echo $this->lang->line('sub_total');?>:</strong></td>
            <td style="text-align:right!important"><?php echo $bill->total_price;?></td>
        </tr>
    <tr > 	   
        <td colspan="5" style="text-align:right!important"><strong><?php echo $this->lang->line('discount').(($bill->discount_type==1)?'( '.$bill->discount_amount.'% )':'');?>:</strong> </td>
        <td style="text-align:right!important"><?php echo $bill->discount_value;?></td>           
    </tr>
     <?php if($bill->bill_type==2){ ?>
     <tr > 
	  
	   <td colspan="5" style="text-align:right!important"><strong><?php echo $this->lang->line('total_price');?>:</strong></td>
      <td style="text-align:right!important"><?php echo sprintf('%.2f',($bill->total_price-$bill->discount_value)); ?></td>

    </tr>
   
    <!-- <tr> 
         <td colspan="5" style="text-align:right!important"><strong><?php echo $this->lang->line('vat');?> (13%):</strong></td>
      <td style="text-align:right!important"><?php echo $bill->tax_amount;?></td>
    </tr> -->
    <?php } ?>
    <tr > 	   
	   <td colspan="5" style="text-align:right!important"><strong><?php echo $this->lang->line('grand_total');?>:</strong></td>
      <td style="text-align:right!important"><?php echo $bill->bill_rounded_grand_total_price; ?></td>
    </tr>
       </tfoot>
</table>
</div>
<div style="clear:both"></div>
<input  type="button" id="close_btn" name="close_btn" onclick="closeform()"  value="Close" class="button"/>
<?php if ($bill->bill_id > 0 ){?>
        <input  type="button" id="print_bill" name="print_bill" onclick="printbill(<?php echo $bill->bill_id;?>);"  value="Print Bill" class="button"/>	
		<?php }?>