<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php $sc_details=$this->mdl_servicecenters->getServiceCenterdetails_alloc($this->input->get('sc_id')); ?>
<?php $engineer_details=$this->mdl_engineers->getEngineerlist_alloca($this->input->get('engineer')); ?>

<div id="jobcard" style="width:700px;"><iframe id="ifmcontentstoprint"
	style="height: 0px; width: 0px; position: absolute"></iframe>
<div id="cardContent">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td	style=" text-align:center;border-top: none; border-left: none; border-right: none; color: #00689C; font-size: 20px !important; font-weight: bold;"><label>
		C.G.Electronics Pvt. Ltd.</td>
        <tr></tr>
        <td	style=" text-align:center;border-top: none; border-left: none; border-right: none; color: #00689C; font-size: 15px !important; font-weight: bold;"><label>
		Parts Loan Slip</td>
	</tr>
    </table>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <table width="50%" style="float:left;" >
    <tr> <td> <b>Store: </b></td> 
    <td> <?php  echo $sc_details->sc_name;?></td>
    </tr>
    <tr><td> <b>Address:</b></td>
    <td><?php  echo $sc_details->sc_address;?></td>
    </tr>
    </table>
    <table width="50%" style="float:right;">
    <tr> <td> <b>Engineer Name: </b></td> 
    <td> <?php  echo $engineer_details->engineer_name;?></td>
    </tr>
    <tr><td> <b>Phone Number:</b></td>
    <td><?php  echo $engineer_details->engineer_phone;?></td>
    </tr>
    </table>
  
</table>

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
<table id="tbl_alloc_print" cellpadding="0" cellspacing="0" width="100%" border="1px" style="border:1px solid #000;">
	<col width="1%" /><col width="20%"/><col width="20%" /><col width="20%" /><col width="10%" /><col width="15%"/>
	<thead>
    	<tr>
        	<th style="border:1px solid #000;"><?php echo $this->lang->line('sn');?></th>
             <th style="text-align:center; border:1px solid #000;"><?php echo $this->lang->line('service_center');?></th>
             <th style="text-align:center; border:1px solid #000;"><?php echo $this->lang->line('part_number');?></th>
             <th style="text-align:center; border:1px solid #000;"><?php if($this->input->get('allco_select')==1){echo $this->lang->line('allocated_date');}else{echo $this->lang->line('revoked_date');}?> </th>
             <th style="text-align:center; border:1px solid #000;"><?php echo $this->lang->line('quantity');?></th>
             <th style="text-align:center; border:1px solid #000;"><?php echo $this->lang->line('status');?></th>
            
        </tr>
    </thead>
    <tbody>
	
<?php $i=1;
	foreach($lists as $list){ ?>
		
			<tr>
            	<td style="text-align:center;border-width:0 1px;"><?php echo $i;?></td>
                 <td style="text-align:center;border-width:0 1px;"><?php echo $list->sc_name;?></td>
                 <td style="text-align:center;border-width:0 1px;"><?php echo $list->part_number?></td>
                <td style="text-align:center;border-width:0 1px;"><?php echo $list->created_date?></td>
                 <td style="text-align:center;border-width:0 1px;"><?php echo $list->quantity;?></td>
                 <td style="text-align:center;border-width:0 1px;"><?php if(($list->signed) == 0) { echo "Unsigned" ;}else { echo "Signed";}?>
                 </td>
               	 
            </tr>
	<?php $i++; }?>
    </tbody>
</table>
<table width="100%">
<tr>
<td colspan="6" style="text-align:right; padding-top:80px;"><label>-----------------</label> <br /> <label style="text-align:center"><?php echo "Engineer"."<br>".$engineer_details->engineer_name."</br>";?></label> </td>
</tr>
</table>

</div>
<div>&nbsp;</div>
<table >
<tr><td style="text-align: right;"><input type="button"
			name="print_card" id="print_card" value="Print" class="button"
			onclick="printAllocationReport();" /></td>
<td style="text-align: right; font-size: 11px;"><input type="button"
			name="cancel_card" id="cancel_card" value="Close" class="button"
			onclick="javascript:$(document).trigger('close.facebox');" /></td>
            
</tr>
</table>