

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php $this->load->model(array('stocks/mdl_parts_stocks','orders/mdl_Order_parts_details'));?>
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
  /*border: 1px solid #000000;*/
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
		<td colspan="1"
			style="border-top: none; border-left: none; border-right: none;float:left; vertical-align: middle;">  <img src="<?php  echo base_url();?>assets/style/images/cglogo1.png"  width="191" height="61"/>
		</td>
		<td colspan="2" style="border-top: none;margin-left:200px; border-left: none; float:left;border-right: none; color: #00689C; font-size: 20px !important; font-weight: bold; text-align: center;">
		Sabha Chalan
		</td>
		
	</tr>
</table>
<table>

<tr><td> &nbsp;</td>
<td>&nbsp; </td> </tr>
 
<tr><td> Order Number :</td>
<td><?php echo $order_details->order_number;?> </td> </tr>

<tr><td> To SVC :</td>
<td><?php echo $order_details->sc_name;?> </td> </tr>

<tr><td> Ordered Date:</td>
<td><?php echo $order_details->order_dt;?> </td> </tr>

</table>
  <div id="table-border">
 <table id="partdesc"  width="100%" border="0" cellpadding="0" cellspacing="0" style="border-top:1px solid; border-bottom:1px solid; border-left:1px solid; border-right:1px solid;">
         <tr>
         <th style="font-size: 11px; text-align: left; padding-top:5px; padding-bottom: 10px; padding-left:10px; border-right:1px solid; border-bottom:1px solid;"><label>S.no:</label></th>
             <th style="font-size: 11px; text-align: left; padding-top:5px; padding-bottom: 10px; padding-left:10px; border-right:1px solid; border-bottom:1px solid;"><label>Item Number:</label></th>
             <th style="font-size: 11px; text-align: left; padding-top:5px; padding-bottom: 10px; padding-left:10px; border-right:1px solid; border-bottom:1px solid;"><label>Item description:</label></th>
             <th style="font-size: 11px; text-align: left; padding-top:5px; padding-bottom: 10px; padding-left:10px; border-right:1px solid; border-bottom:1px solid;"><label>Bin Name:</label></th>
              <th style="font-size: 11px; text-align: left; padding-top:5px; padding-bottom: 10px; padding-left:10px; border-right:1px solid; border-bottom:1px solid;"><label>Available Quantity:</label></th>
               <th style="font-size: 11px; text-align: left;  padding-top:5px; padding-bottom: 10px; padding-left:10px; border-right:1px solid; border-bottom:1px solid;"><label>Required_quantity:</label></th>
        </tr>
      <?php $i =1;?>

        <?php foreach($lists as $list){
		//$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		$quantity = $this->mdl_parts_stocks->checkPartsStock($list->requested_sc_id,$list->part_id,'1');
		
		
		$dispatched_quantity = $this->mdl_Order_parts_details->getDispatchedQuantity($list->order_part_id);
	
		
		if ($list->part_quantity > $dispatched_quantity-> dispatched_quantity) {?>
        <tr style="height:auto;">
         <td style="font-size: 11px; text-align: left; padding-left:10px; padding-top:5px; border-right:1px solid; "><?php echo $i;?></td>
             <td style="font-size: 11px; text-align: left; padding-left:10px; padding-top:5px; border-right:1px solid; "><?php echo $list->part_number;?></td>
             <td style="font-size: 11px; text-align: left; padding-left:10px; padding-top:5px; border-right:1px solid;"><?php echo $list->part_desc;?></td>
              <td style="font-size: 11px; text-align: left; padding-left:10px; padding-top:5px; border-right:1px solid;"><?php echo $list->partbin_name;?></td>
             <td style="font-size: 11px; text-align: left; padding-left:10px; padding-top:5px; border-right:1px solid;"><?php echo $quantity->stock_quantity;?></td>
              <td style="font-size: 11px; text-align: left; padding-left:10px; padding-top:5px; border-right:1px solid;"><?php echo $list->part_quantity -  $dispatched_quantity-> dispatched_quantity;?></td>
             
        </tr>
        <?php 
			   $i = $i + 1;}
		 }?>
        <?php //if ($totrow < 15){$tbhght=250;}else{$tbhght=20;} ?>
        <?php $tbhght =10;  
		?>
        
       </table>
       </div>
       <table id="partdesc"  width="100%" border="0" cellpadding="0" cellspacing="0">
                    

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

