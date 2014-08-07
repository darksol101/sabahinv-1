<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php 
$nep_purchase_date = '';

	$str =   date('Y-m-d',strtotime($bill_details->bill_sale_date));
	$arr = explode("-",$str);
	$this->load->library('nepalicalendar');
	$date = $this->nepalicalendar->eng_to_nep($arr[0],$arr[1],$arr[2]);

	$nep_purchase_date = sprintf("%02d",$date['date']).'/'.sprintf("%02d",$date['month']).'/'.$date['year'];

$this->load->library('wordconverter');?>
<style>
#jobcard {
	width: 1100px;
}

#jobcard td {
	font-size: 11px !important;
}

.space {
	float: left;
	width: 420px
}

.space1 {
	float: left;
	width: 245px
}

#facebox .popup #cardContent .tblgrid {
	background: #fff;
	border-collapse: collapse;
	border: 0
}

#facebox .popup #cardContent .tblgrid td,#facebox .popup #cardContent .tblgrid th
	{
	border: 0px solid #ccc;
}
#facebox .popup #cardContent .tblgrid th{
	width:100px;
}
#facebox .popup #cardContent .tblgrid td{
	width:200px;
}
#facebox .popup #cardContent .tblgrid .tbl td,#facebox .popup #cardContent .tblgrid .tbl th
	{
	border: none
}

#facebox .popup #cardContent .tblgrid th {
	background: none;
	color: #000;
	padding: 4px 10px;
}

#facebox .popup #cardContent .tblgrid td {
	line-height: 15px
}

#facebox .popup #cardContent .tblgrid tr {
	
}

#facebox .popup #cardContent .tblgrid .even {
	background: none repeat scroll 0 0 #F2F9FC
}

#facebox .popup #cardContent .tblgrid .tbl td {
	padding: 0px 5px;
}

#facebox .popup #cardContent .tblgrid .tbl {
	height: 150px;
}

#facebox .popup td.body {
	padding: 0;
}
#partdesc th {
  background: none repeat scroll 0 0 #FFFFFF !important;
  border-bottom: 1px solid #000000;
  color: #000000 !important;
}
#partdesc th, #partdesc td {
}
.close_image.button {
  margin-bottom: 10px;
}
.address{
padding-bottom:10px;
}
.vat_no tr td{
	border:1px solid #000;
	border-bottom:1px solid #000 !important;
}
.vat_no tr th{
	background:none !important;
	color:#000 !important;
	font-weight:bold;
}
</style>

<div id="jobcard"><iframe id="ifmcontentstoprint"
	style="height: 0px; width: 0px; position: absolute"></iframe>
<div id="cardContent" style="height: 500px; overflow: auto; margin: 0;">
<div class="icon" style="height:30px">
	<h3 style="font-family:courier new;text-align:center;">SABAH NEPAL</h4>
</div>
<div class="address" style="font-family:courier new;text-align:center;">Branch Office: <?php $result=$this->mdl_servicecenters->getscdetailsbyid($bill_details->sc_id); echo $bill_details->sc_address;?> <br> Tel:<?php echo $bill_details->sc_phone1;?>, Fax: <?php echo $result->sc_fax;?></div>
<div class="vat_no">
	<table width="40%" border="0">
		<tr>
			<th style="border:0 none; font-family:courier new; width:200px; padding: 0px 0px 0px 0px;text-align:left;">VAT NO.</td>
			<td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">3</td>
			<td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">0</td>
			<td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">2</td>
			<td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">7</td>
			<td style="font-family:courier new; border:1px solid #000;padding-left:3px;padding-right:3px;">8</td>
			<td style="font-family:courier new;border:1px solid #000;padding-left:3px;padding-right:3px;">3</td>
			<td style="font-family:courier new;border:1px solid #000;padding-left:3px;padding-right:3px;">8</td>
			<td style="font-family:courier new;border:1px solid #000;padding-left:3px;padding-right:3px;">0</td>
			<td style="font-family:courier new;border:1px solid #000;padding-left:3px;padding-right:3px;">3</td>
		</tr>
	</table>
</div>
<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin:5px 0 0 0">
 <tr> 
   <td colspan = "11" style="font-size: 11px; text-align: right;font-family:courier new;"><?php if($bill_details->bill_status == 2){echo 'Cancelled Bill';} else {if ($bill_details->printed == 1){ echo 'Duplicate Copy';} else {echo 'Original Copy';}}?></td>
   </tr>
   
	<tr>
		<td colspan="1"
			style="border-top: none; border-left: none; border-right: none; vertical-align: middle;">&nbsp;
		</td>
		<td colspan="5"
			style="border-top: none; border-left: none; border-right: none; color: #00689C; font-size: 20px !important;  text-align:center;">&nbsp;
		</td>
		<td colspan="5"
			style="border-top: none; border-left: none; border-right: none; text-align: right;">&nbsp;
		
		</td>
	</tr>
</table>

<table width="100%" cellpadding="5" cellspacing="0" class="tblgrid"	border="0" align="center">
    <tbody>
    <tr> 
	   <th style="font-size: 11px; text-align: left; font-family:courier new; border-top:1px dashed #000;border-bottom:1px dashed #000;" >
	   	<table width="100%" border="0">
	   		<tr>
	   			<th style="width:130px; font-family:courier new; text-align:left;font-size:15px;"><label>Bill Number:</label></td>
	   			<td id='sales_number'  style="width:130px; font-family:courier new;font-size:15px;"><span><?php echo $bill_details->bill_number; //$bill_details->sc_code.($bill_details->bill_type==1?'SI':'TI').'/'.$bill_details->bill_number;?></span></td>
	   			<td>&nbsp;</td>
	   			<th style="width:130px; font-family:courier new;font-size:15px;"><label>&nbsp;</label></td>
	   			<td style="width:400px; font-family:courier new;font-size:15px; text-align:right;"><span><strong>Date: </strong> <?php echo  date('Y-m-d',strtotime($bill_details->bill_sale_date)).'       '.'['.$nep_purchase_date.']' ;?></span></td>
	   		</tr>
	   	</table>
	   </th>
   	</tr>     
    <tr> 
	   <th style="font-size: 11px; text-align: left;font-family:courier new; border-bottom:1px dashed #000;" >
	   	<table width-"100%" border="0">
	   		<tr>
	   			<td style="width:150px; font-family:courier new;font-size:15px;"><label>Customer Name</label><span style="float:right;">:</span></th>
	   			<td style="width:200px; font-family:courier new;font-size:15px;"><span> <?php echo substr($bill_details->bill_customer_name,0,24);?></span></td>
	   			<td style="width:5%;">&nbsp;</td>
	   			<td style="width:150px; font-family:courier new;font-size:15px;">Bill Type:</td>
	   			<td style="width:150px;">&nbsp;</td>
	   		</tr>
	   		<tr>
	   			<td style="width:170px;font-family:courier new;font-size:15px; "><label>Customer Address</label><span style="float:right;">:</span></th>
	   			<td style="width:200px;font-family:courier new;font-size:15px; "><span> <?php echo substr($bill_details->bill_customer_address,0,24);?></span></td>
	   			<td>&nbsp;</td>
	   			<td style="width:150px; font-family:courier new;font-size:15px;">Due Date:</td>
	   			<td style="width:150px;">&nbsp;</td>
	   		</tr>
	   		<!-- <tr>
	   			<td style="width:150px; font-family:courier new;font-size:15px;"><label>Vat Reg. No</label><span style="float:right;">:</span></th>
	   			<td style="width:150px;font-family:courier new;font-size:15px;"><span><?php  echo wordwrap($bill_details->bill_customer_vat, 1, " ", true);?></span></td>
	   			<td>&nbsp;</td>
	   			<td style="width:150px; font-family:courier new;font-size:15px;">Due Miti:</td>
	   			<td style="width:150px;">&nbsp;</td>
	   		</tr> -->
	   	</table>
	   </th>
	</tr>
	<tr>
		<td style="text-align:right; font-family:courier new;">Payment:&nbsp;&nbsp;Cash/Cheque/Credit/Other</td>
	</tr>
     
   
     
    
       
    </tbody>
    
    </table>

			</td>
         
  
 <table id="partdesc"  width="100%" border="0" cellpadding="0" cellspacing="0">
         <tr>
         <th style="font-family:courier new;font-size: 12px; text-align: left; border-top:1px dashed #000; border-bottom:1px dashed #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>S.No:</label></th>
             <th style="font-family:courier new;font-size: 12px; text-align: left; border-top:1px dashed #000; border-bottom:1px dashed #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Item Number:</label></th>
             <th style="font-family:courier new;font-size: 12px; text-align: left; border-top:1px dashed #000; border-bottom:1px dashed #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Item Description:</label></th>
             <th style="font-family:courier new;font-size: 12px; text-align: left; border-top:1px dashed #000; border-bottom:1px dashed #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Sales Discount:</label></th>
             <th style="font-size: 12px;font-family:courier new; text-align: right; border-top:1px dashed #000; border-bottom:1px dashed #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Rate:</label></th>
              <th style="font-size: 12px;font-family:courier new; text-align: right; border-top:1px dashed #000; border-bottom:1px dashed #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Quantity:</label></th>
               <th style="font-size: 12px;font-family:courier new; text-align: right; border-top:1px dashed #000; border-bottom:1px dashed #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Total:</label></th>
        </tr>

        <?php 
			  $i = 1;
			 
			?>
        <?php foreach($bill_part_details as $bill_part){
       	?>
        	
        <tr style="height:auto;">
         <td style="font-family:courier new;font-size: 13px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:1px; height:10px; "><?php echo $i;?></td>
             <td style="font-family:courier new;font-size: 13px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:1px; height:10px; "><?php echo $bill_part->part_number;?></td>
             <td style="font-family:courier new;font-size: 12px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:1px; height:10px;"><?php echo $bill_part->part_desc;?></td>
             <td style="font-family:courier new;font-size: 12px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:1px; height:10px;">
             	<?php  $maker_detail = Modules::run('sales/sale/getMakerDetail', $sales_id, $bill_part->part_id);
             	 ?>	
             	<?php 
             		if(!empty($maker_detail)){
             			echo $maker_detail->sale_name;
             			if($maker_detail->sale_deduction_type == 1){
             		  		echo "(".$maker_detail->sale_deduction_value."%)";
             		  }else if ($maker_detail->sale_deduction_type==2) {
             		  		echo "(".sprintf('%.2f',($maker_detail->sale_deduction_value)).")";
             		  }
             		}else{
             			echo "N/A";
             		}
             	?>
				
             </td>
              <td style="font-family:courier new;font-size: 13px; text-align: right; border-width:0 1px!important; padding-left:10px; padding-top:1px; height:10px; "><?php echo $bill_part->part_rate;?></td>
             <td style="font-family:courier new;font-size: 13px; text-align: right; border-width:0 1px!important; padding-left:10px; padding-top:1px;height:10px;"><?php echo $bill_part->part_quantity;?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td style="font-family:courier new;font-size: 13px; text-align: right; border-width:0 1px!important; padding-left:10px; padding-top:1px;height:10px;"><?php echo sprintf('%.2f',($bill_part->part_quantity*$bill_part->part_rate));?></td>
             
              
        </tr>
        <?php 
			   $i = $i + 1;
		?>
        <?php }?>
        <?php //if ($totrow < 15){$tbhght=250;}else{$tbhght=20;} ?>
        <?php 
		$totrow = count($bill_part_details);
		
		for($p=1;$p<=15-$totrow;$p++){
		?>
		<tr>
			<td colspan="6" style="font-family:courier new;font-size: 13px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:1px;height:10px;">&nbsp;</td>
		</tr>
		<?php
		}
		?>
         <tr>
             <td style="font-size: 11px; text-align: left; border-top:1px dashed #000;"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left; border-top:1px dashed #000;"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left; border-top:1px dashed #000;"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left; border-top:1px dashed #000;"><label>&nbsp;</label></th>
             <td  style="font-family:courier new;font-size: 13px; text-align: right; padding-left:10px;padding-top:2px; border-top:1px dashed #000;"><label>Total :</label></th>
                <td style="font-family:courier new;font-size: 13px; text-align: right; padding-left:10px;padding-top:2px; border-top:1px dashed #000;" id='total_price'><label> <?php echo $bill_details->total_price;;?></label></td>    
           </tr>
           <tr>
             <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
             
             <td  style="font-size: 11px; text-align: left; padding-left:10px"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left; padding-left:10px"><label> &nbsp;</label></td>    
               <td style=" font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px;padding-bottom:2px; border-bottom:1px dashed #000;" ><label>Discount: </label> </td>    
                <td style="font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px; border-bottom:1px dashed #000;" id = 'discount'><label> <?php  echo $bill_details->discount_value;?> </label></td>    
           </tr>
		   <tr>
             <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
             <td  style="font-size: 11px; text-align: left; padding-left:10px"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left; padding-left:10px"><label> &nbsp;</label></td>    
               <td style="font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px; width:120px" ><label>Service Charge: </label> </td>    
                <td style="font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px;" id='tax'><label> 
						&nbsp;
					</label></td>    
           </tr>
		   
            <tr>
             <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
            
             <td  style="font-size: 11px; text-align: left; padding-left:10px"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left; padding-left:10px"><label> &nbsp;</label></td>    
               <td style="font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px; width:120px" ><label>Taxable Amount: </label> </td>    
                <td style="font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px;" id='tax'><label> 
						<?php 
							
						echo  sprintf('%.2f',($bill_details->total_price -  $bill_details->discount_value));?>
					</label></td>    
           </tr>
		     
           <tr>
            <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
            <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
            <td  style="font-size: 11px; text-align: left; padding-left:10px"><label>&nbsp;</label></th>
            <td style="font-size: 11px; text-align: left; padding-left:10px"><label> &nbsp;</label></td>    
            <td style="font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px;" ><label>VAT 13%: </label> </td>    
            <td style="font-family:courier new;font-size: 13px; text-align: right; padding-top:2px;padding-left:10px;" id='tax'><label> 
						<?php echo $bill_details->tax_amount; ?>
					</label></td>    
           </tr>
           <tr>
             <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
            <td  style="font-size: 11px; text-align: left; padding-left:10px"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left; padding-left:10px"><label> &nbsp;</label></td>    
               <td style="font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px;" ><label>Rounded Off: </label> </td>    
                <td style="font-family:courier new;font-size: 13px; text-align: right;padding-top:2px; padding-left:10px;" id='tax'><label> 
							  <?php echo  $bill_details->bill_rounded_off;    ?>
					</label></td>    
           </tr>
          
            <tr>
             <td  style=" font-weight:bold;width: 70px;font-family:courier new;font-size: 13px; text-align: left;border-top:1px dashed #000;border-bottom:1px dashed #000;"><label>In Words:</label></td>    
			  <td colspan ="3"  style="font-family:courier new;font-size: 13px; text-align: left;border-top:1px dashed #000;border-bottom:1px dashed #000;"><label>Rs.  <?php echo $this->wordconverter->convert_number_to_words( (int)$bill_details->bill_rounded_grand_total_price); ?> &nbsp;Only.</label></td>    
               <td style="font-family:courier new;font-size: 13px; text-align: right; padding-left:10px;padding-top:2px; border-top:1px dashed #000;border-bottom:1px dashed #000;"  ><label>Grand Total: </label> </td>    
                <td style=" font-family:courier new;font-size: 13px; text-align: right; padding-left:10px;padding-top:2px; border-top:1px dashed #000;border-bottom:1px dashed #000;" id='grand_total'><label> 
						<?php echo $bill_details->bill_rounded_grand_total_price;?>
					</label></td>    
           </tr>
          
       </table>
       <table id="partdesc"  width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tfoot>
                    <tr>
                    <td colspan ="2" style="font-family:courier new; border-width:0px; font-size: 12px; text-align: left! important; vertical-align: bottom; padding: 10px 5px 22px 0px; height:22px"><label>Terms and Conditions:</label> <br /> <label>1.  Please Pay Via A/c payee cheques/Drafts only. </label> <br /> <label>2.  Endoresement of payment should be its reliasation only. </label> </td>
                    </tr>
                    <tr>
                     <td style="font-family:courier new;border-width:0px; font-size: 11px; text-align: left! important; vertical-align: bottom;  height:0px"><label>-----------------------</label> </td>
                     <td style="font-family:courier new;border-width:0px; font-size: 11px; text-align: right! important; vertical-align: bottom; height:0px"><label>-------------------------------</label> </td>
                    </tr>
                    <td style="font-family:courier new;border-width:0px; font-size: 13px; text-align: left! important; vertical-align: bottom; padding: 0px 0px 0px 0px; height:5px"><label>Received By</label> </td>
                     <td style="font-family:courier new;border-width:0px; font-size: 13px; text-align: right! important; vertical-align: bottom; padding: 0px 0px 0px 0px; height:5px"><label>For SABAH Nepal</label> </td>
                    </tr>
                    </tfoot> 

</table>

<input type="hidden" id='discount_type' value ='<?php echo $bill_details->discount_type;?>'>
<input type="hidden" id='sales_id' value ='<?php echo $bill_details->sales_id;?>'>
<input type="hidden" id='bill_id' value ='<?php echo $bill_details->bill_id;?>'>
 </div>
<table align="right" style="margin-top:11px">
	<tr>
		<td style="text-align: right; font-size: 11px;"><input type="button"
			name="print_card" id="print_card" value="Print" class="button"
			onclick="printordercard();" /></td>
		<td style="text-align: right; font-size: 11px;"><input type="button"
			name="cancel_card" id="cancel_card" value="Cancel" class="button"
			onclick="javascript:$(document).trigger('close.facebox');" /></td>
	</tr>
</table>

