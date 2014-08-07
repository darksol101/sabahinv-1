<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div id="jobcard">
<iframe id="ifmcontentstoprint" style="height: 0px; width: 0px; position: absolute"></iframe>
<div id="cardContent" style="height:500px; overflow:auto; margin:0; ">
<style>
#jobcard{width:1100px;}#jobcard td{font-size:11px!important;}.space{float:left;width:420px}.space1{float:left;width:245px}#facebox .popup #cardContent .tblgrid{background:#fff;border-collapse:collapse;border:0}#facebox .popup #cardContent .tblgrid td,#facebox .popup #cardContent .tblgrid th{border:1px solid #ccc}#facebox .popup #cardContent .tblgrid .tbl td,#facebox .popup #cardContent .tblgrid .tbl th{border:none}#facebox .popup #cardContent .tblgrid th{background:none;color:#000;padding:4px 10px;}#facebox .popup #cardContent .tblgrid td{line-height:15px}#facebox .popup #cardContent .tblgrid tr{}#facebox .popup #cardContent .tblgrid .even{background:none repeat scroll 0 0 #F2F9FC}
#facebox .popup #cardContent .tblgrid .tbl td{padding:0px 5px; }
#facebox .popup #cardContent .tblgrid .tbl{height:150px;}
#facebox .popup td.body{padding:0;}  
</style>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
        <td colspan="5" style="border-top:none;border-left:none;border-right:none; vertical-align:middle;">
      &nbsp;
        </td>
        <td colspan="3" style="border-top:none;border-left:none;border-right:none; color:#00689C; font-size:20px!important; font-weight:bold; text-align:center;">
        Customer Service Management
        </td>
        <td colspan="5" style="border-top:none;border-left:none;border-right:none; text-align:right;">
       &nbsp;
        </td>
        </tr>
</table>
    <table width="100%" cellpadding="5" cellspacing="0" class="tblgrid" border="1" align="center">	
        <tbody> 
         <tr>
            <td colspan="5" style="vertical-align:top;"  >
                <table class="tbl" cellpadding="5" cellspacing="0"  >
                    <tbody >
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label><?php echo $this->lang->line('name');?>: </label></th>
                            <td style="font-size:11px; text-align:left;"><span> <?php echo $preview_details->cust_first_name.' '.$preview_details->cust_last_name;?> </span></td>
                        </tr>
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label>Address:</label></th>
                            <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->cust_address;?><?php echo $this->lang->line('city')?>:<?php echo $preview_details->city_name;?>,&nbsp;District:<?php echo $preview_details->district_name;?>,&nbsp;Zone:<?php echo $preview_details->zone_name;?>&nbsp;</span>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label>Landmark:</label></th>
                            <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->cust_landmark;?></span></td>
                        </tr>
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label>Phone:</label></th>
                            <td style="font-size:11px; text-align:left;"><span style="font-weight:bold;">(office)</span><?php echo $preview_details->cust_phone_office;?>&nbsp;<span style="font-weight:bold;">(home)</span><?php echo $preview_details->cust_phone_home;?>&nbsp;<span style="font-weight:bold;">(mobile)</span><?php echo $preview_details->cust_phone_mobile;?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size:11px; text-align:left; padding-left:10px;"><label style="font-weight:bold;">Appointment Date & Time:</label> <span><?php echo format_date(strtotime($preview_details->call_cust_pref_dt));?>&nbsp;<?php echo date("H:i",strtotime($preview_details->call_cust_pref_tm));?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td colspan="3" style="vertical-align:top;"   >
                <table class="tbl" cellpadding="5" cellspacing="0" >
                    <tbody>
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label>Service Centre:</label></th>
                            <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->sc_name;?></span></td>
                        </tr>
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label>Call ID:</label></th>
                            <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->call_uid;?></span></td>
                        </tr>
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label>Product:</label></th>
                            <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->product_name;?></span></td>
                        </tr>
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label>Set Serial No:</label></th>
                            <td style="font-size:11px; text-align:left;"><span><?php if($preview_details->call_serial_no){ echo $preview_details->call_serial_no;}else{ echo 'NOT CONF';}?></span></td>
                        </tr>
                        <tr>
                            <th style="font-size:11px; text-align:left;"><label>Complaint:</label></th>
                            <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->call_service_desc;?></span></td>
                        </tr>                        
                    </tbody>
            </table>
            </td>
            <td colspan="5" style="vertical-align:top;"  >
                <table class="tbl" cellpadding="5" cellspacing="0" >
                <tbody>
                    <tr>
                        <th style="font-size:11px; text-align:left;"><label>Complaint Date:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo format_date(strtotime($preview_details->call_dt)).'&nbsp;&nbsp;&nbsp;&nbsp;'.date("H:i",strtotime($preview_details->call_tm));?></span></td>
                    </tr>
                    <tr>
                        <th style="font-size:11px; text-align:left;"><label>Brand:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->brand_name;?></span></td>
                    </tr>
                    <tr>
                        <th style="font-size:11px; text-align:left;"><label>Model:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->model_number;?></span></td>
                    </tr>
                    <tr>
                        <th style="font-size:11px; text-align:left;"><label>Dealer:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->call_dealer_name;?></span></td>
                    </tr>
                    <tr>
                        <th style="font-size:11px; text-align:left;"><label>Purchase Date:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo format_date(strtotime($preview_details->call_purchase_dt));?></span></td>
                    </tr>
                    <tr>
                        <th style="font-size:11px; text-align:left;"><label>Warranty:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php if((int)$preview_details->model_warranty>0){echo $preview_details->model_warranty.' days';}?></span></td>
                    </tr>
                </tbody>
            </table>
            </td>
         </tr>
        <tr>
            <th style="width:50px; font-size:11px;"><label>Date</label></th>
            <th style="width:50px; font-size:11px;"><label>Tech. Name</label></th>
            <th style="width:50px; font-size:11px;"><label>Time In</label></th>
            <th style="width:50px; font-size:11px;"><label>Time Out</label></th>
            <th style="width:50px; font-size:11px;"><label>Fault</label></th>
            <th style="width:50px; font-size:11px;"><label>Section</label></th>
            <th style="width:250px; font-size:11px;"><label>Action</label></th>
            <th style="font-size:11px;"><label>Location</label></th>
            <th style="font-size:11px;"><label>Old Item</label></th>
            <th style="font-size:11px;"><label>Qty</label></th>
            <th style="font-size:11px;"><label>New Item</label></th>
            <th style="font-size:11px;"><label>Qty</label></th>
            <th style="font-size:11px;"><label>Amount</label></th>
        </tr>
        <tr class="">
            <td style="font-size:11px;"><span><?php echo $preview_details->call_dt;?></span> </td>
            <td style="font-size:11px;"><span><?php
            $this->load->model('engieers/mdl_engineers');
			$engineers = $this->mdl_engineers->getEngineerNameById($preview_details->engineer_id);
			if($engineers==''){
				echo '&nbsp;';
			}else{
				echo ucfirst(strtolower($engineers));
			}
			?></span> </td>
            <td style="font-size:11px;  text-align:center"><span><?php
			if($preview_details->call_visit_tm_in!='00:00:00'){
				echo date("H:i",strtotime($preview_details->call_visit_tm_in));
			}else{
				echo '&nbsp;';
			}
			?>
			</span> </td>
            <td style="text-align:center"><span><?php
            if($preview_details->call_visit_tm_in!='00:00:00'){
				echo date("H:i",strtotime($preview_details->call_visit_tm_in));
			}else{
				echo '&nbsp;';
			}
			?>
            </span> </td>
            <td><span>
            <?php
			if(intval($preview_details->repair_id)>0){
				$this->load->model('faultsettings/mdl_repair');
				$repairs = $this->mdl_repair->getRepairCodeById($preview_details->repair_id);
				if(count($repairs)>0){
					echo $repairs[0]->repair_code;
				}
			}elseif(intval($preview_details->defect_id)>0){
				$this->load->model('faultsettings/mdl_defect');
				$defects = $this->mdl_defect->getDefectCodeById($preview_details->defect_id);
				if(count($defects)>0){
					echo $defects[0]->defect_code;
				}
			}elseif(intval($preview_details->symptom_id)>0){
				$this->load->model('faultsettings/mdl_symptom');
				$symptoms = $this->mdl_symptom->getSymptomCodeById($preview_details->symptom_id);
				if(count($symptoms)>0){
					echo $symptoms[0]->symptom_code;
				}
			}else{
				echo '&nbsp;';
			}
			?>
            </span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>
            <?php
            $arr = array();
			$arr[]=$preview_details->call_engineer_remark;
			$arr[]=$preview_details->call_detail_wrk_done;
			if(count($arr)>0){
				echo implode("<br>",$arr);
			}else{
				echo '&nbsp;';
			}
			?>
            </span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
        </tr>
        <?php for($i=1;$i<=3;$i++){?>
        <tr class="">
            <td style="font-size:11px;"><span>&nbsp;</span> </td>
            <td style="font-size:11px;"><span>&nbsp;</span> </td>
            <td style="font-size:11px;"><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
        </tr>
        <?php }?>            
        <tr>
            <th style="font-size:11px;"><label>Last Job No.</label></th> 
            <th style="font-size:11px;"><label>Tech. Name</label></th> 
            <th style="font-size:11px;"><label>Time In</label></th>
            <th style="font-size:11px;"><label>Time Out</label></th>
            <th style="font-size:11px;"><label>Fault</label></th>
            <th style="font-size:11px;"><label>Section</label></th>
            <th style="font-size:11px;"><label>Action</label></th>
            <th style="font-size:11px;"><label>Location</label></th>
            <th style="font-size:11px;"><label>Old Item</label></th>
            <th style="font-size:11px;"><label>Qty</label></th>
            <th style="font-size:11px;"><label>New Item</label></th>
            <th style="font-size:11px;"><label>Qty</label></th>
            <th style="font-size:11px;"><label>Amount</label></th>
        </tr>
        <tr class="">
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
        </tr>                         
        <tr>
        <th colspan="5"><label>&nbsp;</label></th>
        <th colspan="9"><label>&nbsp;</label></th>
        </tr>
        <tr>
            <td colspan="5" style="font-size:11px;"><span>I have read and agree to terms and conditions<br />
            of repairs as mentioned.</span></td>
            <th colspan="3" style="font-size:11px;"><label>Signature:</label></th>
            <th colspan="3" style="font-size:11px;"><label>Supervisor:</label></th>
            <th colspan="3" style="font-size:11px;"><label>Cashier:</label></th>
        </tr>
        <tr>
            <td colspan="14" style="font-size:11px;">
                <div>
                <span>  Customer Signature:</span><br /><br />
                <span> Set has been repaired to my satisfaction.</span>
                </div>
                <!--<div class="space">
                <span>Last  Job No:</span><br /><br /><br />
                </div>-->
            </td>
          </tr>  
          <tr style="height:10px;">
          <td colspan="14" style="padding:0; border-bottom:none; height:10px;">
          <div style="border-top:1px dashed #000; margin-top:10px; height:10px; font-size:11px; line-height:5px;">&nbsp;</div>
          </td>
          </tr>
        <tr>
        	<td colspan="5" style="border-bottom:none; border-right:none; font-size:11px;"> <span>Billing(applicable for chargable job only)</span></td>
            <td colspan="2" style="font-size:11px; border-right:none; border-left:none;border-bottom:none;"><label>Call ID:</label> <span><?php echo $preview_details->call_uid;?></span></td>
            <td colspan="7" style="font-size:11px; border-right:none; border-left:none; border-bottom:none;"><label>Complaint Date:</label> <span><?php echo format_date(strtotime($preview_details->call_dt)).'&nbsp;&nbsp;&nbsp;&nbsp;'.date("H:i",strtotime($preview_details->call_tm));?></span></td>
        </tr>    
        </tr>
        <tr>
            <td colspan="5" style="font-size:11px;border-top:none; border-right:none; border-bottom:none;"><span>Collected Rs.</span></td>
            <td colspan="2" style="font-size:11px;border-top:none; border-left:none;border-right:none;border-bottom:none;"> <span>From:</span></td>
            <td colspan="7" style="font-size:11px;border-top:none; border-left:none;border-bottom:none;"><span>For Repairs:</span></td>
        </tr>
        <tr>
        	<td colspan="7"  style="font-size:11px;border-top:none; border-right:none;"><span>Permanent receipt  shall be dispatched from the office.</span></td>
            <td colspan="7" style="font-size:11px;border-top:none; border-left:none;border-right:none;"> <span>Technician's Signature and Date:</span></td>
        </tr>
    </tbody>
    </table>
        <table cellpadding="0" cellspacing="0" width="100%" border="0">
      <tr>
        <td colspan="14" style="border-bottom:none;border-left:none;border-right:none; font-size:12px!important; font-weight:normal; text-align:right;"><strong>HELPLINE:</strong> (NTC) 16600100211; (Others) 4100141 </td>
      </tr>
    </table>
</div>
<table align="right">
  <tr>
    <td style="text-align:right;font-size:11px;"><input type="button" name="print_card" id="print_card" value="Print" class="button" onclick="printJobCard();"  /></td>
    <td style="text-align:right;font-size:11px;"><input type="button" name="cancel_card" id="cancel_card" value="Cancel" class="button" onclick="javascript:$(document).trigger('close.facebox');"  /></td>
  </tr>
</table>
</div>
