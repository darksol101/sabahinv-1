<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<?php $totrow=$total; foreach($transit_details as $transit_detail){}

?>
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
  border-bottom: 1px solid #000000 !important;
  color: #000000 !important;
}
#partdesc th, #partdesc td {
  border: 1px solid #000000;
}
.close_image.button {
  margin-bottom: 10px;
}

</style>

<div id="jobcard"><iframe id="ifmcontentstoprint"
	style="height: 0px; width: 0px; position: absolute"></iframe>
<div id="cardContent" style="height: 500px; overflow: auto; margin: 0;">
<table cellpadding="0" cellspacing="0" width="100%" border="0">

	<tr>
		<td colspan="5"
			style="border-top: none; border-left: none; border-right: none; vertical-align: middle;">&nbsp;
		</td>
		<td colspan="3"
			style="border-top: none; border-left: none; border-right: none; color: #00689C; font-size: 20px !important; font-weight: bold;">
		C.G.Electronics Pvt. Ltd.</td>
		<td colspan="5"
			style="border-top: none; border-left: none; border-right: none; text-align: right;">&nbsp;
		
		</td>
	</tr>
</table>

<table width="100%" cellpadding="5" cellspacing="0" class="tblgrid"
	border="0" align="center">
    <tbody>
    <tr> 
   <th style="font-size: 11px; text-align: left;"><label>Chalan Number:</label></th>
   <td style="font-size: 11px; text-align: left;"><span> <?php echo $transit_detail->courior_number ;?></span></td>
   <th style="font-size: 11px; text-align: left;"><label>Docket Number:</label></th>
	<td style="font-size: 11px; text-align: left;"><span> <?php echo $transit_detail->courior_number ;?></span></td>
    <th style="font-size: 11px; text-align: left;"><label>Chanal Date:</label></th>
	<td style="font-size: 11px; text-align: left;"><span> <?php echo $transit_detail->courior_date ;?></span></td>
    </tr>
      <tr> 
   <th style="font-size: 11px; text-align: left;"><label>Order Number:</label></th>
   <td style="font-size: 11px; text-align: left;"><span> <?php echo $detailorders->order_number ;?></span></td>
   <th style="font-size: 11px; text-align: left;"><label>Order Date:</label></th>
	<td style="font-size: 11px; text-align: left;"><span> <?php echo  date('Y-m-d',strtotime($detailorders->order_created_ts)) ;?></span></td>
    <th style="font-size: 11px; text-align: left;"><label>Reference Date:</label></th>
	<td style="font-size: 11px; text-align: left;"><span> <?php //echo $transit_detail->transit_number ;?></span></td>
    </tr>
    
    <tr> 
   <th style="font-size: 11px; text-align: left;"><label>Store:</label></th>
   <td style="font-size: 11px; text-align: left;"><span> <?php echo $detailorders->sc_name?></span></td>
   <td colspan=2>&nbsp;</td>
  
    <th style="font-size: 11px; text-align: left;"><label>Reference Number:</label></th>
	<td style="font-size: 11px; text-align: left;"><span> <?php //echo $transit_detail->transit_number ;?></span></td>
    </tr>
    
    <tr> 
   <th style="font-size: 11px; text-align: left;"><label>Address:</label></th>
   <td style="font-size: 11px; text-align: left;"><span> <?php echo $detailorders->sc_address ;?></span></td>
   <td colspan=2>&nbsp;</td>
  
    <th style="font-size: 11px; text-align: left;"><label>Number of Boxes:</label></th>
	<td style="font-size: 11px; text-align: left;"><span> <?php echo $transit_detail->box_number ;?></span></td>
    </tr>
    </tbody>
    
    </table>

			</td>
            
  
  
 <table id="partdesc"  width="100%" border="1" cellpadding="0" cellspacing="0">
         <tr>
         <th style="font-size: 11px; text-align: left; border-bottom:1px solid #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>S.no:</label></th>
             <th style="font-size: 11px; text-align: left; border-bottom:1px solid #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Item Number:</label></th>
             <th style="font-size: 11px; text-align: left; border-bottom:1px solid #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Item description:</label></th>
             <th style="font-size: 11px; text-align: left; border-bottom:1px solid #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Rate:</label></th>
              <th style="font-size: 11px; text-align: left; border-bottom:1px solid #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Quantity:</label></th>
               <th style="font-size: 11px; text-align: left; border-bottom:1px solid #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Total:</label></th>
                <th style="font-size: 11px; text-align: left; border-bottom:1px solid #000; padding-top:5px; padding-bottom: 10px; padding-left:10px"><label>Job Card Number:</label></th>
        </tr>
        <?php $total =0;
			  $unitprice= 0;
			  $total_price = 0;
			  $i = 1;
			?>
        <?php foreach($orderparts as $orderpart){?>
        <tr style="height:auto;">
         <td style="font-size: 11px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:5px; "><?php echo $i;?></td>
             <td style="font-size: 11px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:5px; "><?php echo $orderpart->part_number;?></td>
             <td style="font-size: 11px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:5px;"><?php echo $orderpart->part_description;?></td>
              <td style="font-size: 11px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:5px;"><?php echo $orderpart->part_sc_price;?></td>
             <td style="font-size: 11px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:5px;"><?php echo $orderpart->part_quantity;?></td>
              <td style="font-size: 11px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:5px;"><?php echo ($orderpart->part_quantity * $orderpart->part_sc_price);?></td>
              <td style="font-size: 11px; text-align: left; border-width:0 1px!important; padding-left:10px; padding-top:5px;"><?php echo ($orderpart->call_uid);?></td>
              
        </tr>
        <?php $total = $total+$orderpart->part_quantity;
			   $unitprice = $unitprice+ $orderpart->part_sc_price;
			   $total_price = $total_price + ($orderpart->part_quantity * $orderpart->part_sc_price);
			   $i = $i + 1;
		?>
        <?php }?>
        <?php //if ($totrow < 15){$tbhght=250;}else{$tbhght=20;} ?>
        <?php if ($totrow < 24){ $tbhght=250-($totrow*10);} else {$tbhght=10;} 
		?>
        <tr style="">
             <td style="font-size: 11px; text-align: left; border-width:0 1px!important; height:<?php echo $tbhght; ?>px; padding-left:10px">&nbsp;</td>
             <td style="font-size: 11px; text-align: left; border-width:0 1px!important; height:<?php echo $tbhght; ?>px; padding-left:10px">&nbsp;</td>
             <td style="font-size: 11px; text-align: left; border-width:0 1px!important; height:<?php echo $tbhght; ?>px; padding-left:10px">&nbsp;</td>
             <td style="font-size: 11px; text-align: left; border-width:0 1px!important; height:<?php echo $tbhght; ?>px; padding-left:10px">&nbsp;</td>
             <td style="font-size: 11px; text-align: left; border-width:0 1px!important; height:<?php echo $tbhght; ?>px; padding-left:10px">&nbsp;</td>
              <td style="font-size: 11px; text-align: left; border-width:0 1px!important; height:<?php echo $tbhght; ?>px; padding-left:10px">&nbsp;</td>
               <td style="font-size: 11px; text-align: left; border-width:0 1px!important; height:<?php echo $tbhght; ?>px; padding-left:10px">&nbsp;</td>
        </tr>
         <tr>
             <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
              <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>
             <td  style="font-size: 11px; text-align: left; font-weight:bold; padding-left:10px"><label>Total Quantity:</label></th>
              <td style="font-size: 11px; text-align: left; padding-left:10px"><label> <?php echo $unitprice;?></label></td>    
               <td style="font-size: 11px; text-align: left; padding-left:10px"><label> <?php echo $total;?></label></td>    
                <td style="font-size: 11px; text-align: left; padding-left:10px"><label> <?php echo $total_price;?></label></td>    
                 <td style="font-size: 11px; text-align: left;"><label>&nbsp;</label></th>   
            </tr>
       </table>
       <table id="partdesc"  width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tfoot>
                    <tr>
                    <td style="border-width:0px; font-size: 11px; text-align: right ! important; vertical-align: bottom; padding: 0px 20px 40px 0px; height:<?php echo $tbhght; ?>px"><label>Chaudhary Group </label> <br /> <label>Autharised Signature </label> </td>
                    </tr>
                    </tfoot> 

</table>

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

