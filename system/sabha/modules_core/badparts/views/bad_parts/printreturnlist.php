<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php $sc_details=$this->mdl_servicecenters->getServiceCenterdetails_alloc($this->input->get('sc_id')); ?>
<?php $engineer_details=$this->mdl_engineers->getEngineerlist_alloca($this->input->get('engineer_id')); ?>
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

#jobcard #cardContent {
    padding: 5px 15px;
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
#tbl_alloc_print th {
  background: none repeat scroll 0 0 #FFFFFF !important;
  border-bottom: 1px solid #000000 !important;
  color: #000000 !important;
}
#tbl_alloc_print th, #partdesc td {
  border: 1px solid #000000;
}
.close_image.button {
  margin-bottom: 10px;
}
.headintbl td{border:0px solid #000;}

</style>

<div id="jobcard" style="width:700px;"><iframe id="ifmcontentstoprint"
	style="height: 0px; width: 0px; position: absolute"></iframe>
<div id="cardContent">
<table cellpadding="0" cellspacing="0" width="100%" border="0" class="tblgrid">
	<tr>
		<td style=" text-align:center;border-top: none; border-left: none; border-right: none; color: #00689C; font-size: 20px !important; font-weight: bold;"><label>
		C.G.Electronics Pvt. Ltd.</label></td></tr>
        <tr> </tr>
        <tr>
        <td style=" text-align:center;border-top: none; border-left: none; border-right: none; color: #00689C; font-size: 15px !important; font-weight: bold;"><label>
		Return parts Slip</label></td>
	</tr>
    </table>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <div>
   
    <table width="50%" style="float:left; border:1px solid #000;"  border="0" cellpadding="0" cellspacing="0" class="headintbl" >
   <tbody>
    <tr> 
    		<td style="text-align:left; font-size: 12px; padding:3px;"> <b>Store: </b></td> 
   		 	<td  style="text-align:left; font-size: 12px; padding:3px;"> <?php  echo $sc_details->sc_name;?></td>
    </tr>
    <tr >
    		<td style="text-align:left; font-size: 12px; padding:3px;"> <b>Address:</b></td>
    		<td style="text-align:left; font-size: 12px; padding:3px;"><?php  echo $sc_details->sc_address;?></td>
    </tr>
    <tr >
    		<td style="text-align:left; font-size: 12px; padding:3px;"> <b>Return Date:</b></td>
    		<td style="text-align:left; font-size: 12px; padding:3px;"><?php  echo $this->input->get('fromdate');?></td>
    </tr>
	</tbody>
    </table>
    
    <table width="50%" style="float:right; border:1px solid #000; border-width:1px 1px 1px 0;" border="0" cellpadding="0" cellspacing="0"  class="headintbl">
    <tbody>
    <tr> 
    	<td style="text-align:left; font-size: 12px; padding:3px;"> <b>Engineer Name: </b></td> 
   		 <td style="text-align:left; font-size: 12px; padding:3px;"> <?php  echo $engineer_details->engineer_name;?>&nbsp;</td>
    </tr>
    <tr>
    	<td style="text-align:left; font-size: 12px; padding:3px;"> <b>Phone Number:</b></td>
   		 <td style="text-align:left; font-size: 12px; padding:3px;"><?php  echo $engineer_details->engineer_phone;?>&nbsp;</td>
    </tr>
    <tr>
    <td style="text-align:left; font-size: 12px; padding:3px;">&nbsp;</td>
   	<td style="text-align:left; font-size: 12px; padding:3px;">&nbsp;</td>
    </tr>
    </tbody>
    </table>
     
</div>


<style>
#tbl_alloc_print td{ border:1px solid #000;}
#facebox .content table#tbl_alloc_print th {
  background: none repeat scroll 0 0 #FFFFFF;
  border: 1px solid #000000;
  color: #000000;
  font-size: 11px;
}
</style>
 <div>&nbsp;</div>
<table id="tbl_alloc_print" cellpadding="0" cellspacing="0" width="100%" border="0" style="border:0px solid #000;">
	<col width="1%" /><col width="18%"/><col width="40%" /><col width="15%" />
	<thead>
    	<tr>
        	<th style="border:1px solid #000;"><?php echo $this->lang->line('sn');?></th>
             <th style="text-align:center; border:1px solid #000; font-size: 12px;"><?php echo $this->lang->line('part_number');?></th>
             <th style="text-align:left; border:1px solid #000; font-size: 12px; padding-left:10px;"><?php echo $this->lang->line('part_desc');?></th>
             <th style="text-align:center; border:1px solid #000; font-size: 12px;"><?php echo $this->lang->line('quantity');?></th>
             <!--<th style="text-align:center; border:1px solid #000 font-size: 11px;;"><?php //echo $this->lang->line('status');?></th>-->
            
        </tr>
    </thead>
    <tbody>
	
<?php $i=1;
	foreach($lists as $list){ ?>
		
			<tr>
            	<td style="text-align:center;border-width:0 1px ;font-size: 12px;"><?php echo $i;?></td>							                 <td style="text-align:center;border-width:0 1px ;font-size: 12px;"><?php echo $list->part_number?></td>
                <td  style="text-align:left;border-width:0 1px ;font-size: 12px; padding-left:10px;"><?php echo $list->part_desc;?></td>
                 <td style="text-align:center;border-width:0 1px ;font-size: 12px;"><?php echo $list->part_quantity;?></td>
                                	 
            </tr>
	<?php $i++; }?>
    
    		<tr>
            	<td style="text-align:center;border-width:0 1px 1px 1px;font-size: 12px;">&nbsp;</td>							                 
                <td style="text-align:center;border-width:0 1px 1px 1px;font-size: 12px;">&nbsp;</td>
                <td  style="text-align:left;border-width:0 1px 1px 1px;font-size: 12px;">&nbsp;</td>
                 <td style="text-align:center;border-width:0 1px 1px 1px;font-size: 12px;">&nbsp;</td>
                               	 
            </tr>
    </tbody>
</table>
<div>&nbsp;</div><div>&nbsp;</div>
<table width="100%">
<tr>
<td style="text-align:left; padding-top:80px; font-size: 12px;"> <label>---------------------</label> <br /> 
<label style="text-align:center font-size: 12px;"><?php echo "Engineer"."<br>".$engineer_details->engineer_name."</br>";?></label></td>
<td style="text-align:right; padding-top:80px; font-size: 12px;"><label>---------------------</label> <br /> 
<label style="text-align:center font-size: 12px;"> Store Incharge </label> </td>
</tr>
</table>

</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<table>
<tr><td style="text-align: right;font-size: 12px;"><input type="button"
			name="print_card" id="print_card" value="Print" class="button"
			onclick="printAllocationReport();" /></td>
<td style="text-align: right; font-size: 12px;"><input type="button"
			name="cancel_card" id="cancel_card" value="Close" class="button"
			onclick="javascript:$(document).trigger('close.facebox');" /></td>
            
</tr>
</table>