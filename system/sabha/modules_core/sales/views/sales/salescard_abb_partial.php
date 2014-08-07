<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php
$Cust_vat =0;
?>
<?php $this->load->library('wordconverter');?>
<style type="text/css">
@font-face {
    font-family: 'printBill';
    src: url('<?php echo site_url("assets/fonts/Merchant Copy.ttf");?>');
    font-weight: 400 !important;
    font-style: normal;
}

.tg{width: 79mm; padding: 0px !important; margin: 0px !important;}
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-size:10px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-size:10px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-s6z2{text-align:center}
.tg th,.tg td{border: 1px solid !important;}

#facebox .content table th {
background: none repeat scroll 0 0 #FFF;
color: #000;
border: 0.5px dotted #000;
}
#facebox{
  width: 79mm;
}
.tg tfoot tr td{text-align: left !important;}
.sabah_headings{ text-align: center;}
.sabah_headings h1,.sabah_headings p{padding: 0px;}
</style>

<div style="width:80mm !important; margin-top:-10px;padding:5px; border-bottom: 1px dotted black;">
<div class="sabah_headings">
  <p style="text-align:center;"><img  style="width:120px;" src="<?php echo site_url('assets/style/images/sn.jpg');?>"></p>
  <h5 style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;">SAARC Business Association of Home Based Workers</h5>
  <p style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;"><?php echo $bill_details->sc_name; ?></p>  
  <p style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;"><?php echo $bill_details->sc_address; ?></p>
  <p style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;">Telephone: <?php echo $bill_details->sc_phone1; ?></p>
  <p style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;">Email:info@sabahnp.org / www.sabahnp.org</p>
  <p style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;font-weight:600;">Invoice Number: <?php echo $bill_details->bill_number; ?>
    <?php /*change TEJ*/ echo '<br>'.$sales_type_name; ?></p>
  <p style="float: left;">
   <table width="100%" border="0">
      <tr>
        <td style="padding:0px; margin:0px;border:none;line-height:1; font-family:printBill; text-transform:uppercase; font-size:15.5px; float:left;"> PAN NO. : 303414449</td>
        <td style="padding:0px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px; float:right;">Date: <?php echo $bill_details->bill_sale_date; ?></td>
      </tr>
      <tr>
        <td colspan="12" style="padding:0px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;">Customer's Name: <?php echo $bill_details->bill_customer_name; ?></td>
      </tr>
      <tr>
        <td colspan="12" style="padding:0px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;">Address: <?php echo $bill_details->bill_customer_address; ?></td>
      </tr>
    </table>
</p>

</div>


<table class="tg" border="1">
<thead>
    <col width="2%" />
    <col width="40%" />
    <col width="4%" />
    <col width="10%" />
    <col width="10%" />
  <tr>
    <th style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:14px;" class="tg-031e">Sn.</th>
    <th style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:14px;" class="tg-031e">Description</th>
    <th style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:14px;" class="tg-031e">Qty.</th>
    <th style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:14px;" class="tg-031e">Rate</th>
    <th style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:14px;" class="tg-031e">Total</th>
  </tr>
    <!--  <tr>
    <td style="line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e">Rs.</td>
 <td class="tg-031e">Ps.</td> 
  </tr>-->
</thead>
  <tbody>
  <?php
  $i=1; 
  foreach ($bill_part_details as $bill_part):
  $item_rate = Modules::run('parts/parts/itemPrice', $bill_part->part_number); 
  ?>

    <tr>
    <td style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;"class="tg-031e"><?php echo $i++; ?></td>
    <td style="padding:1px;  margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;"class="tg-031e"><?php echo $bill_part->part_number;?>
    <?php $maker_detail = Modules::run('sales/sale/getMakerDetail', $sales_id, $bill_part->part_id); ?>
    
    <?php if($maker_detail){
          echo "(".ucwords($maker_detail->sale_name)." ";
                  if($maker_detail->sale_deduction_type == 1){
                      echo $maker_detail->sale_deduction_value."%)";
                     
                  }else if ($maker_detail->sale_deduction_type==2) {
                      echo sprintf('%.2f',($maker_detail->sale_deduction_value)).")";
                    $item_rate = ($maker_detail->sale_deduction_value+$bill_part->part_rate);
                  }


      }
     
      ?>


    </td>

    <td style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e"><?php echo $bill_part->part_quantity; ?></td>
    <td style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e"><?php echo number_format($bill_part->item_rate,2); ?></td>

    <td style="padding:1px; margin:0px;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e"><?php echo number_format(($bill_part->part_quantity*$bill_part->part_rate),2); ?></td>
    <!-- <td class="tg-031e"></td> -->
  </tr>
  <?php endforeach; ?>

 <tr>
    <td style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e">&nbsp;</td>
    <td style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e">&nbsp;</td>
    <td style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e">&nbsp;</td>
    <td style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e">&nbsp;</td>
    <td style="padding:0px;margin:0px;font-weight:300;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;" class="tg-031e">&nbsp;</td>
   <!--  <td class="tg-031e">&nbsp;</td>  -->
  </tr>
  

  </tbody>

  <tfoot>
  <tr>
    <td class="tg-031e"  colspan="2" rowspan="3"  style="padding:1px !important;text-align: left !important;font-family:printBill; text-transform:uppercase;font-size:15.5px;"><label><?php echo ucwords($this->wordconverter->convert_number_to_words($bill_details->bill_rounded_grand_total_price)); ?> &nbsp;Only.</label></td> 
    <td class="tg-031e" style="padding:1px !important;font-family:printBill; text-transform:uppercase;font-size:15.5px;text-align: right !important; text-align: right !important;" colspan="2">Total</td>
    <td class="tg-031e" style="padding:1px !important;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;"><?php echo number_format($bill_details->total_price,2);?></td>
    <!-- <td class="tg-s6z2"></td>
   --></tr>
  <tr>
   
    <td class="tg-031e" colspan="2" style="padding:1px !important;font-family:printBill; text-transform:uppercase;font-size:15.5px;text-align: right !important;">
    <?php echo (($bill_details->discount_type == 1)?'('.$bill_details->discount_amount. '%)':''); ?>Discount</td>
    <td class="tg-031e" style="padding:1px !important;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;"><?php echo number_format($bill_details->discount_value,2); ?></td>
    <!-- <td class="tg-031e"></td>
   --></tr>
  <tr>
    <td class="tg-031e" colspan="2" style="padding:1px !important;font-family:printBill; text-transform:uppercase;font-size:15.5px;text-align: right !important;">Grand Total</td>
    <td class="tg-031e" style="padding:1px !important;line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;"><?php echo number_format($bill_details->bill_rounded_grand_total_price,2); ?></td>
    <!-- <td class="tg-031e"></td>
   --></tr>
  </tfoot>
</table>
<div>
  <p style="line-height:1;padding:1px;margin:0px;font-family:printBill; text-transform:uppercase;font-size:15.5px;"><strong>Note: </strong> Goods Once sold Will not be returned. Thank You.</p><!-- 
  <p style="line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;">Received By: .........<i style="float:right;">_____________</i></p>
  <p style="line-height:1;font-family:printBill; text-transform:uppercase;font-size:15.5px;">Signature:   .........<i style="float:right;">For Sabah</i></p>-->
</div> 


