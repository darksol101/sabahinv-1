<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php
$Cust_vat =0;
?>

  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
      <td style="text-align: center; border-top: none; font-family:courier new; border-left: none; border-right: none; vertical-align: middle;">SABAH Nepal</td><br>

    </tr>
    <tr>
          <td style="width:30px;text-align:center">Phone:<?php echo $bill_details->sc_phone1;?> </td>
    </tr>
    <tr>
      <td style="text-align: center; border-top: none; border-left: none; font-family:courier new; border-right: none; vertical-align: middle;"><?php echo $bill_details->sc_address;?></td>
      <td style="text-align: center; width:30px; font-family:courier new;"></td>
    </tr>
    <tr>
      <td style="text-align: center; border-top: none; border-left: none; border-right: none; vertical-align: middle; font-family:courier new;">302783803 </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="text-align: center; border-top: none; border-left: none; border-right: none; vertical-align: middle;font-size:11px;font-family:courier new;">INVOICE</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="100%" cellpadding="0" cellspacing="0" class="tblgrid"
	border="0" align="center" style ="margin-top : 10px;" >
    <tbody>
      <tr height="20">
        <th style="font-size: 13px; text-align: left;width: 173px;font-family:courier new;padding:0 0 0px 0;margin:0;"><label>Customer Name &nbsp;&nbsp;:</label></th>
        <td colspan="3" style="font-size: 13px; text-align: left; width:200px;font-family:courier new;padding:0;margin:0;"><span> <?php echo substr($bill_details->bill_customer_name,0,35);?></span></td>
        <th style="font-size: 13px;padding:0; text-align: left; width:280px;font-family:courier new;margin:0;"><label> Bill Number :&nbsp;<?php echo  $bill_details->bill_number;//$bill_details->sc_code.($bill_details->bill_type==1?'SI':'TI').'/'.substr($bill_details->bill_number,0,16);?></label></th>
        <td style="padding:0;margin:0;width:10px;"><span> &nbsp;</span></td>
      </tr>
      <tr height="20">
        <th style="font-size: 13px; text-align: left;font-family:courier new;padding: 0 0 0px 0;"><label>Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</label></th>
        <td colspan="3" style="font-size: 13px; text-align: left;font-family:courier new;padding: 0px;"><span> <?php echo substr($bill_details->bill_customer_address,0,30);?></span></td>
        <th style="font-size: 13px; text-align: left;padding:0;font-family:courier new;"><label>Bill Date &nbsp;:</label>
          <?php echo  date('Y-m-d',strtotime($bill_details->bill_sale_date)) ;?>
          </label></th>
        <td style="font-size: 13px; text-align: left;padding:0;"><span>&nbsp;</span></td>
      </tr>
      <tr height="20" style="display:none;">
        <th style="font-size: 13px; text-align: left;font-family:courier new;padding: 0 0 0px 0;"><label>Ref. Job Sheet#&nbsp;:</label></th>
        <td style="font-size: 13px; width:100px; text-align: left;font-family:courier new; padding: 0px;"><span> <?php echo  substr($bill_details->call_uid ,0,12) ;?></span></td>
        <th style="font-size: 13px; width:50px; text-align: right;font-family:courier new;padding: 0px;"><label>Model:</label></th>
        <td style="font-size: 13px; width:190px; text-align: left;font-family:courier new;padding: 0px;"><?php echo substr($bill_details->model_number ,0,18);?>
        </td>
        <th style="font-size: 13px; text-align: left;font-family:courier new;padding: 0px;"><label>Ref. IMEI&nbsp;&nbsp;&nbsp;:</label>
          <?php echo substr($bill_details->call_serial_no,0,16);?></th>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <th colspan ='6' style="font-size: 11px; text-align: center; font-family:courier new"><?php if($bill_details->bill_status == 2){echo 'Cancelled Bill';} else {if ($bill_details->printed == 1){ echo 'Regenerated Bill';} else {echo 'Original Copy';}}?></th>
      </tr>
    </tbody>
  </table>
  </td>
  <table id="partdesc"  width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;table-layout:fixed;">
    <col width="40px" />
    <col width="200px" />
    <col width="125px" />
    <col width="125px" />
    <col width="125px" />
    <col width="80px" />
    <col width="80px" />
    <tr>
      <th style="font-size: 13px; font-family:courier new;text-align: left; border-bottom:1px dashed #000 !important;border-top:1px dashed #000 !important; padding-top:2px; padding-bottom: 2px; padding-left:10px;width:20px;"><label>S.no:</label></th>
      <th style="font-size: 13px; font-family:courier new; text-align: left; border-bottom:1px dashed #000 !important;border-top:1px dashed #000 !important; padding-top:2px; padding-bottom: 2px; padding-left:10px"><label>Item Number:</label></th>
      <th style="font-size: 13px; font-family:courier new; text-align: left; border-bottom:1px dashed #000 !important;border-top:1px dashed #000 !important; padding-top:2px; padding-bottom: 2px; padding-left:10px"><label>Item Description:</label></th>
     
      <th style="font-size: 13px; font-family:courier new; text-align: right; border-bottom:1px dashed #000 !important;border-top:1px dashed #000 !important; padding-top:2px; padding-bottom: 2px; padding-left:10px"><label>Rate:</label></th>
      <th style="font-size: 13px;font-family:courier new; text-align: right; border-bottom:1px dashed #000 !important;border-top:1px dashed #000 !important; padding-top:2px; padding-bottom: 2px; padding-left:10px"><label>Quantity:</label></th>
      <th style="font-size: 13px; font-family:courier new; text-align: right; border-bottom:1px dashed #000 !important;border-top:1px dashed #000 !important; padding-top:2px; padding-bottom: 2px; padding-left:10px"><label>Total:</label></th>
    </tr>
    <?php 
			  $i = 1;
			  $totrow = count($bill_part_details);
			?>
    <?php foreach($bill_part_details as $bill_part){

      ?>

    <tr style="height:auto;">
      <td style="font-size: 14px; font-family:courier new;text-align: left; border-width:0 0px!important; height:10px; padding-left:10px; padding-top:1px;overflow:hidden "><?php echo $i;?></td>
      <td style="font-size: 14px; text-align: left; font-family:courier new; border-width:0 0px!important;height:10px; padding-left:10px; padding-top:1px;overflow:hidden "><?php echo $bill_part->part_number;?></td>
      <td style="font-size: 14px; text-align: left; font-family:courier new; border-width:0 0px!important;height:10px; padding-left:10px; padding-top:1px;overflow:hidden "><?php echo $bill_part->part_desc;?>

            <?php  $maker_detail = Modules::run('sales/sale/getMakerDetail', $sales_id, $bill_part->part_id);
               ?> 
              <?php 
                if(!empty($maker_detail)){
                  echo ucwords($maker_detail->sale_name);
                  if($maker_detail->sale_deduction_type == 1){
                      echo "(".$maker_detail->sale_deduction_value."%)";
                  }else if ($maker_detail->sale_deduction_type==2) {
                      echo "(".sprintf('%.2f',($maker_detail->sale_deduction_value)).")";
                  }
                }else{
               
                }
              ?>
      </td>


      <td style="font-size: 14px; text-align: right; font-family:courier new; border-width:0 0px!important;height:10px; padding-left:10px; padding-top:1px;overflow:hidden "><?php echo $bill_part->part_rate;?></td>
      <td style="font-size: 14px; text-align: right; font-family:courier new; border-width:0 0px!important;height:10px; padding-left:10px; padding-top:1px;overflow:hidden">&nbsp;&nbsp;<?php echo $bill_part->part_quantity;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td style="font-size: 14px; text-align: right; font-family:courier new; border-width:0 0px!important;height:10px; padding-left:10px; padding-top:1px;overflow:hidden"><?php echo sprintf('%.2f',$bill_part->part_quantity * $bill_part->part_rate);?></td>
    </tr>
    <?php 
			   $i = $i + 1;
		?>
    <?php }?>
    <?php //if ($totrow < 15){$tbhght=250;}else{$tbhght=20;} ?>
    <?php if ($totrow < 5){ $tbhght=50-($totrow*10);} else {$tbhght=10;} 
		for($p=1;$p<=5-$totrow;$p++){
		?>
    <tr style="">
      <td colspan="5" style="font-size: 14px; font-family:courier new;text-align: left; border-width:0 0px!important; height:10px; padding-left:10px; padding-top:1px; ">&nbsp;</td>
    </tr>
    <?php }?>
    <tr id="tab3">
      <td colspan="4" style="font-size: 11px; border :0 none !important ; text-align: left;"><label>&nbsp;</label>
        </th>
      <td style="font-size: 14px;border :0 none !important ; text-align: right; font-weight:bold; padding: 0px 0px 0px 10px;border-top:1px dashed #000 !important;font-family:courier new;"><label>Total :</label>
        </th>
      <td style="font-size: 14px;border :0 none !important ; text-align: right; padding: 0px 0px 0px 10px;border-top:1px dashed #000 !important;font-family:courier new;" id='total_price'><label> <?php echo $bill_details->total_price;?></label></td>
    </tr>
    <tr id="dis">
      <td colspan="5" style="font-size: 14px;border :0 none !important ; text-align: right; padding-left:10px; font-weight:bold ; font-family:courier new;" ><label>Discount: </label></td>
      <td style="font-size: 14px;border :0 none !important ; text-align: right; padding-left:10px;font-family:courier new;" id = 'discount'><label>         
          <?php echo $bill_details->discount_value;?></label></td>
    </tr>
  
    <tr>
      <td colspan="5" style="font-size: 14px; border :0 none !important ;text-align: right; padding-left:10px;font-family:courier new; font-weight:bold;padding-bottom:3px !important;"  ><label>Final Price: </label></td>
      <td style="font-size: 14px;border :0 none !important ; font-family:courier new; text-align: right; padding-left:10px;padding-bottom:3px !important;" id='grand_total'><label> <?php
	  echo $bill_details->bill_rounded_grand_total_price;
	  ?> </label></td>
	</tr>
    <tr id="tax1">
      <td colspan="3" style="font-size: 14px;border :0 none !important ;  font-family:courier new;text-align: left; padding-left:10px; font-weight:bold; border-top:1px dashed #000 !important; border-bottom:1px dashed #000 !important;" ><!-- <label>Tax: </label>
        <label>
          <?php if($bill_details->tax){echo $bill_details->tax ; ?>
          %
          <?php  }?>
        </label> --></td>
     <!--  <td style="font-size: 11px;padding-top:2px !important;border :0 none !important ; text-align: left;border-top:1px dashed #000 !important;border-bottom:1px dashed #000 !important;border-bottom:1px dashed #000 !important;"><label>&nbsp;</label>
       </th> -->
      <td colspan="3" style="font-size: 11px; padding-top:2px !important;  border :0 none !important ; text-align: left;border-top:1px dashed #000 !important;border-bottom:1px dashed #000 !important;"><label>&nbsp;</label>
        </th>
    </tr>
  </table>
  <table id="partdesc"  width="100%" border="0" cellpadding="0" cellspacing="0">
    <tfoot>
      <tr>
        <th style="font-size: 11px;border :0 none !important ; font-family:courier new;text-align: left;padding-top:3px;"><label>&nbsp;</label>
          <span> &nbsp;</span></th>
        <th style="font-size: 11px;border :0 none !important ; text-align: right; font-family:courier new;"><label>&nbsp;</label></th>
        <th style="font-size: 11px;border :0 none !important ; text-align: right; font-family:courier new;"><label>&nbsp;</label></th>
        <td style="font-size: 11px; border :0 none !important ;text-align: left;width:100px;">&nbsp;</td>
      </tr>
      <tr>
        <th style="width:50px;font-size: 11px;border :0 none !important ; font-family:courier new;text-align: left;padding-top:3px;"><label>Cashier:</label></th>
        <th style="font-size: 11px;border :0 none !important ; font-family:courier new;text-align: left;padding-top:3px;"><?php echo ($this->session->userdata('username'));?></th>
        <th style="font-size: 11px;border :0 none !important ; text-align: right; font-family:courier new;"><label>Signature:</label></th>
        <td style="font-size: 11px; border :0 none !important ;text-align: left;width:100px;">&nbsp;</td>
      </tr>
    </tfoot>
  </table>
