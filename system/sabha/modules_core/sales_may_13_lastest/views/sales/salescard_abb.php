<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
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
#dis {
}
#facebox .popup #cardContent .tblgrid {
	background: #fff;
	border-collapse: collapse;
	border: 0
}
#facebox .popup #cardContent .tblgrid td, #facebox .popup #cardContent .tblgrid th {
	border: 0px solid #ccc;
}
#facebox .popup #cardContent .tblgrid th {
	width:100px;
}
#facebox .popup #cardContent .tblgrid td {
	width:200px;
}
#facebox .popup #cardContent .tblgrid .tbl td, #facebox .popup #cardContent .tblgrid .tbl th {
	border: none
}
#facebox .popup #cardContent .tblgrid th {
	background: none;
	color: #000;
	padding: 0px 10px;
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
#facebox .content table th {
	padding:0px 10px;
}
#facebox .popup td.body {
	padding: 0;
}
#partdesc th {
	background: none repeat scroll 0 0 #FFFFFF !important;
	border-bottom: 1px solid #000000 !important;
	color: #000000 !important;
}
.close_image.button {
	margin-bottom: 10px;
}
</style>
<div id="jobcard">
<iframe id="ifmcontentstoprint"
	style="height: 0px; width: 0px; position: absolute"></iframe>
<div id="cardContent" style="height: 400px; overflow: auto; margin: 0;">
<?php 
		$data = array(
						'sales_id'=>$sales_id,
						'bill_details'=>$bill_details,
						'bill_part_details'=>$bill_part_details
						);
$this->load->view('sales/sales/salescard_abb_partial',$data);?>
<div style="height:130px;"></div>
<?php $this->load->view('sales/sales/salescard_abb_partial',$data);?>
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
