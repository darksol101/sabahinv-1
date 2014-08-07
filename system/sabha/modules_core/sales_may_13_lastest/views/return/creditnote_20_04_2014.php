<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<?php
	$str =   date('Y-m-d',strtotime($sales_return_details->sales_return_date));
	$arr = explode("-",$str);
	$this->load->library('nepalicalendar');
	$date = $this->nepalicalendar->eng_to_nep($arr[0],$arr[1],$arr[2]);

	$nep_sales_return_date = sprintf("%02d",$date['date']).'/'.sprintf("%02d",$date['month']).'/'.$date['year'];
$this->load->library('wordconverter');
?>
<style type="text/css">
#facebox .content table td {padding: 0px!important;}
</style>
<div id="jobcard"><iframe id="ifmcontentstoprint"
	style="height: 0px; width: 0px; position: absolute"></iframe>
<div id="cardContent" style="height: 500px; width:900px; overflow: auto; margin: 0;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:'Courier New', Courier, monospace">
  <tr>
    <td colspan="2" style="width:100px;font-size: 12px;">Branch Office:<?php echo $sales_return_details->sc_name;?></td>
    </tr>
  <tr>
    <td style="font-size: 12px;"><?php echo $sales_return_details->sc_address;?></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" style="font-size: 12px;">Tel:<?php echo $sales_return_details->sc_phone1;?></td>
    </tr>
  <tr>
    <td colspan="2"><strong>VAT NO : </strong><strong>3&nbsp;0&nbsp;2&nbsp;7&nbsp;8&nbsp;3&nbsp;8&nbsp;0&nbsp;3</strong></td>
    </tr>
</table>
<div style="height:90px;"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:'Courier New', Courier, monospace">
  <tr>
    <td colspan="3" style="border-top:1px dashed #000;border-bottom:1px dashed #000;">Ref. Bill No.:<?php echo $sales_return_details->bill_number?></td>
    <td colspan="3" style="border-top:1px dashed #000;border-bottom:1px dashed #000; text-align:right">Date:<?php echo $sales_return_details->sales_return_date?> [<?php echo $nep_sales_return_date;?>] </td>
    </tr>
  <tr>
    <td style="width:90px;">Buyer Name</td>
    <td colspan="3">:<?php echo 'SR'.$sales_return_details->bill_number?></td>
    <td>Bill Type</td>
    <td>:<?php echo ($sales_return_details->sales_return_type==1)?'CASH':'CREDIT';?></td>
    </tr>
  <tr>
    <td>Address</td>
    <td colspan="3">:<?php echo $sales_return_details->customer_address?></td>
    <td>Due Date</td>
    <td>:</td>
    </tr>
  <tr style="border-bottom:1px dashed #000;">
    <td>Vat Reg No</td>
    <td colspan="3">:<?php echo $sales_return_details->customer_vat?></td>
    <td>Due Miti</td>
    <td>:</td>
    </tr>
  <tr>
    <td colspan="6" style="text-align:right">PaymentCash/Cheque/Credit/Other</td>
    </tr>
  </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:'Courier New', Courier, monospace">
  <col width="1%" /><col width="65%" /><col width="5%" /><col width="9%" /><col width="10%" /><col width="10%" />
  <tr>
    <td style="border-top:1px dashed #000;border-bottom:1px dashed #000; width:1%">SNO</td>
    <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;width:65%">DESCRIPTION</td>
    <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;text-align:center;width:5%">QUANTITY</td>
    <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;text-align:center;width:9%">UNIT</td>
    <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;text-align:right; width:10%">RATE</td>
    <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;text-align:right; width:10%">AMOUNT</td>
  </tr>
  <?php 
  $total = count($sales_parts_details);
  $i=1;     foreach($sales_parts_details as $details){    	?>
  <tr>
    <td><?php echo $i;?></td>
    <td><?php echo $details->part_number;?>(<?php echo $details->part_desc;?>)</td>
    <td style="text-align:center"><?php echo $details->part_return_quantity;?></td>
    <td style="text-align:center">PCS</td>
    <td style="text-align:right"><?php echo $details->part_rate;?></td>
    <td style="text-align:right"><?php echo sprintf('%.2f',$details->part_return_quantity*$details->part_rate);?></td>
  </tr>
  <?php $i++; }?>
  <?php
  for($k=1;$k<=15-$total;$k++){
	  ?>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <?php } ?>
  <tr>
  	<td colspan="4"></td>
    <td style="border-top:1px dashed #000; text-align:right">Total:</td>
    <td style="border-top:1px dashed #000; text-align:right"><?php echo $sales_return_details->sales_return_total_price;?></td>
  </tr>
  <tr>
    <td colspan="5" style="border-top:1px dashed #000; text-align:right">Discount:</td>
    <td style="border-top:1px dashed #000; text-align:right"><?php echo $sales_return_details->sales_return_discounted_price;?></td>
  </tr>
  <tr>
  	<td colspan="4"></td>
    <td style="border-top:1px dashed #000;text-align:right">Taxable Amount:</td>
    <td style="border-top:1px dashed #000; text-align:right"><?php echo sprintf('%.2f',(($sales_return_details->bill_type == 1)?$sales_return_details->sales_return_total_price:($sales_return_details->sales_return_total_price-$sales_return_details->sales_return_discounted_price)));?></td>
  </tr>
  <tr>
  	<td colspan="4"></td>
    <td style="border-top:1px dashed #000; text-align:right">Vat:</td>
    <td style="border-top:1px dashed #000; text-align:right"><?php echo $sales_return_details->sales_return_tax_price;?></td>
  </tr>
  <tr>
    <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;">In Words:</td>
    <td colspan="3" style="border-top:1px dashed #000;border-bottom:1px dashed #000;"><?php echo $this->wordconverter->convert_number_to_words( (int)$sales_return_details->sales_return_rounded_grand_total_price); ?> &nbsp;Only.</td>
    <td style="border-top:1px dashed #000; text-align:right">Grand Total:</td>
    <td style="border-top:1px dashed #000; text-align:right"><?php echo $sales_return_details->sales_return_rounded_grand_total_price;?></td>
  </tr>
  <tr>
    <td colspan="6" style="font-size: 12px;">Terms &amp; Conditions</td>
    </tr>
  <tr>
    <td colspan="6" style="font-size: 12px;">1. Please Pay Via A/c payee cheques/Drafts only.</td>
    </tr>
  <tr>
    <td colspan="6" style="font-size: 12px;">2. Endoresement of payment should be its reliasation only. </td>
  </tr>
  <tr>
    <td colspan="2" style="height:30px;">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr> 
  <tr>
    <td colspan="2" style="border-top:1px dashed #000; font-size: 12px;">Received By</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2" style="border-top:1px dashed #000; text-align:right;font-size: 12px; ">For SABAH Nepal.</td>
  </tr> 
</table>
</div>
 <table align="right" style="margin-top:11px">
	<tr>
		<td style="text-align: right; font-size: 11px;"><input type="button"
			name="print_card" id="print_card" value="Print" class="button"
			onclick="printcreditnote();" /></td>
		<td style="text-align: right; font-size: 11px;"><input type="button"
			name="cancel_card" id="cancel_card" value="Cancel" class="button"
			onclick="javascript:$(document).trigger('close.facebox');" /></td>
	</tr>
</table>