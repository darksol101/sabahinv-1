<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php
$Cust_vat =0;
?>
<?php $this->load->library('wordconverter');?>
<style type="text/css">
.tg{width: 21cm;}
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-s6z2{text-align:center}
.tg th,.tg td{height: 10px !important;font-family:courier new; font-size: 13px;border: 1px solid !important;}

#facebox .content table th {
background: none repeat scroll 0 0 #FFF;
color: #000;
border: 1px solid #000;
font-size: 11px;
font-weight: 700;
padding: 8px 10px;
}
.tg tfoot tr td{text-align: left !important;}
.sabah_headings{ text-align: center;}
.sabah_headings h1,.sabah_headings p{padding: 0px;}
</style>
<div class="sabah_headings">
  <p><img  style="margin-left: 84px;" src="<?php echo site_url('assets/style/images/cglogo2.png');?>"></p>
  <h4>SAARC Business Association of Home Based Workers</h4>
  <p><?php echo $bill_details->sc_name; ?></p>  
  <p><?php echo $bill_details->sc_address; ?></p>
  <p>Telephone: <?php echo $bill_details->sc_phone1; ?></p>
  <p>Email:info@sabahnp.org / www.sabahnp.org</p><b style="float:right;margin-top: -15px;">Invoice Number: <?php echo $bill_details->bill_number; ?></b>
  <h5>INVOICE</h5>
 <p style="float: left;">
   <table width="100%" border="0">
      <tr>
        <th style="border:0 none;">PAN NO.</td>
        <td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">3</td>
        <td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">0</td>
        <td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">3</td>
        <td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">4</td>
        <td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">1</td>
        <td style="font-family:courier new;border:1px solid #000;padding-left:3px;padding-right:3px;">4</td>
        <td style="font-family:courier new;border:1px solid #000;padding-left:3px;padding-right:3px;">4</td>
        <td style="font-family:courier new;border:1px solid #000;padding-left:3px;padding-right:3px;">4</td>
        <td style="font-family:courier new;border:1px solid #000;padding-left:3px;padding-right:3px;">9</td>
        <td style="width:51%">&nbsp;</td>
        <td style="float:right; text-align:right;">Date: <?php echo $bill_details->bill_sale_date; ?> </td>
      </tr>
      <tr>
        <td colspan="12"><p>Customer's Name: <?php echo $bill_details->bill_customer_name; ?></p></td>
      </tr>
      <tr>
        <td colspan="12"><p>Address: <?php echo $bill_details->bill_customer_address; ?></p></td>
      </tr>
    </table>
</p>

</div>


<table class="tg" border="1" width="100%">
<thead>
    <col width="5%" />
    <col width="30%" />
    <col width="8%" />
    <col width="12%" />
    <col width="12%" />
    <col width="5%" />
  <tr>
    <th class="tg-031e" rowspan="2">Sn.</th>
    <th class="tg-031e" rowspan="2">Description</th>
    <th class="tg-031e" rowspan="2">Qty.</th>
    <th class="tg-031e" rowspan="2">Rate/Unit</th>
    <th class="tg-031e" colspan="2">Amount</th>
  </tr>
  <tr>
    <td class="tg-031e">Rs.</td>
    <td class="tg-031e">Ps.</td>
  </tr>
</thead>
  <tbody>
  <?php
  $i=1; 
  foreach ($bill_part_details as $bill_part):?>

    <tr>
    <td class="tg-031e"><?php echo $i++; ?></td>
    <td class="tg-031e"><?php echo $bill_part->part_number;?>
    <?php $maker_detail = Modules::run('sales/sale/getMakerDetail', $sales_id, $bill_part->part_id); ?>
    <?php if($maker_detail){
          echo "(".ucwords($maker_detail->sale_name)." ";
                  if($maker_detail->sale_deduction_type == 1){
                      echo $maker_detail->sale_deduction_value."%)";
                  }else if ($maker_detail->sale_deduction_type==2) {
                      echo sprintf('%.2f',($maker_detail->sale_deduction_value)).")";
                  }


      }?>
    </td>

    <td class="tg-031e"><?php echo $bill_part->part_quantity; ?></td>
    <td class="tg-031e"><?php echo number_format($bill_part->part_rate,2); ?></td>
    <td class="tg-031e"><?php echo number_format(($bill_part->part_quantity*$bill_part->part_rate),2); ?></td>
    <td class="tg-031e"></td>
  </tr>
  <?php endforeach; ?>

  <tr>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
  </tr>
   <tr>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
    <td class="tg-031e">&nbsp;</td>
  </tr>

  </tbody>

  <tfoot>
  <tr>
    <td class="tg-031e"  colspan="2" rowspan="3"  style="font-family:courier new;font-size: 13px; text-align: left !important;"><label>In Words: <?php echo ucwords($this->wordconverter->convert_number_to_words($bill_details->bill_rounded_grand_total_price)); ?> &nbsp;Only.</label></td> 
    <td class="tg-031e" style="font-family:courier new;font-size: 13px; text-align: right !important;" colspan="2">Total</td>
    <td class="tg-031e"><?php echo number_format($bill_details->total_price,2);?></td>
    <td class="tg-s6z2"></td>
  </tr>
  <tr>
   
    <td class="tg-031e" colspan="2" style="font-family:courier new;font-size: 13px; text-align: right !important;">
    <?php echo (($bill_details->discount_type == 1)?'('.$bill_details->discount_amount. '%)':''); ?>Discount</td>
    <td class="tg-031e"><?php echo number_format($bill_details->discount_value,2); ?></td>
    <td class="tg-031e"></td>
  </tr>
  <tr>
    <td class="tg-031e" colspan="2" style="font-family:courier new;font-size: 13px; text-align: right !important;">Grand Total</td>
    <td class="tg-031e"><?php echo number_format($bill_details->bill_rounded_grand_total_price,2); ?></td>
    <td class="tg-031e"></td>
  </tr>
  </tfoot>
</table>
<div>
  <p><strong>Note: </strong> Goods Once sold Will not be returned. Thank You.</p>
  <p>Received By: ............<i style="float:right;">_____________</i></p>
  <p>Signature:   ............<i style="float:right;">For Sabah</i></p>
</div>