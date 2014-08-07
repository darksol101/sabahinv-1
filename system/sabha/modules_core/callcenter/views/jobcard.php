<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
$this->load->model('mcb_data/mdl_mcb_data');
$call_type = $this->mdl_mcb_data->getStatusDetails($preview_details->call_type,'calltype');
?>
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
        <img src="<?php  echo base_url();?>assets/style/images/cglogo.jpg" />
        </td>
        <td colspan="3" style="border-top:none;border-left:none;border-right:none; color:#00689C; font-size:20px!important; font-weight:bold;">
       Service Job Sheet -  <?php echo $preview_details->call_uid;?>
        </td>
        <td colspan="5" style="border-top:none;border-left:none;border-right:none; text-align:right;">
        <img src="<?php //echo base_url();?>assets/style/images/cgelectronics.jpg" />
        </td>
        </tr>
</table>
    <table width="100%" cellpadding="5" cellspacing="0" class="tblgrid" border="1" align="center">	
        <tbody> 
         <tr>
            <td colspan="5" style="vertical-align:top;"  >
                <table class="tbl" cellpadding="5" cellspacing="0"  >
                    <tbody >
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label><?php echo $this->lang->line('name');?>: </label></th>
                            <td style="font-size:12px; text-align:left;"><span> <?php echo $preview_details->cust_first_name.' '.$preview_details->cust_last_name;?> </span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Address:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->cust_address;?><?php echo $this->lang->line('city')?>:<?php echo $preview_details->city_name;?>,&nbsp;District:<?php echo $preview_details->district_name;?>,&nbsp;Zone:<?php echo $preview_details->zone_name;?>&nbsp;</span>
                            </td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Landmark:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->cust_landmark;?></span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Phone:</label></th>
                            <td style="font-size:12px; text-align:left;"><span style="font-weight:bold;">(office)</span><?php echo $preview_details->cust_phone_office;?>&nbsp;<span style="font-weight:bold;">(home)</span><?php echo $preview_details->cust_phone_home;?>&nbsp;<span style="font-weight:bold;">(mobile)</span><?php echo $preview_details->cust_phone_mobile;?>
                            </td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Service Centre:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->sc_name;?></span></td>
                        </tr>
                         <tr style ="line-height:10px;">
                        <th style="font-size:12px; text-align:left;"><label>Warranty:</label></th>
                        <td style="font-size:12px; text-align:left;"><span><?php echo $call_type;?></span></td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td colspan="3" style="vertical-align:top;"   >
                <table class="tbl" cellpadding="5" cellspacing="0" >
                    <tbody>
                        
                       
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Product:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->product_name;?></span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>IMEI /Set Serial No:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php if($preview_details->call_serial_no){ echo $preview_details->call_serial_no;}else{ echo 'NOT CONF';}?></span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Complaint:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->call_service_desc;?></span></td>
                        </tr> 
                         <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;">&nbsp;</th>
                            <td style="font-size:12px; text-align:left;"><span>&nbsp;</span></td>
                        </tr>                       
                    </tbody>
            </table>
            </td>
            <td colspan="5" style="vertical-align:top;"  >
                <table class="tbl" cellpadding="5" cellspacing="0" >
                <tbody>
                    <tr style ="line-height:10px;">
                        <th style="font-size:12px; text-align:left;"><label>Complaint Date:</label></th>
                        <td style="font-size:12px; text-align:left;"><span><?php echo format_date(strtotime($preview_details->call_dt)).'&nbsp;&nbsp;&nbsp;&nbsp;'.date("H:i",strtotime($preview_details->call_tm));?></span></td>
                    </tr>
                    <tr style ="line-height:10px;">
                        <th style="font-size:12px; text-align:left;"><label>Brand:</label></th>
                        <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->brand_name;?></span></td>
                    </tr>
                    <tr style ="line-height:10px;">
                        <th style="font-size:12px; text-align:left;"><label>Model:</label></th>
                        <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->model_number;?></span></td>
                    </tr>
                    <tr style ="line-height:10px;">
                        <th style="font-size:12px; text-align:left;"><label>Dealer:</label></th>
                        <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->call_dealer_name;?></span></td>
                    </tr>
                    <tr style ="line-height:10px;">
                        <th style="font-size:12px; text-align:left;"><label>Purchase Date:</label></th>
                        <td style="font-size:12px; text-align:left;"><span><?php echo format_date(strtotime($preview_details->call_purchase_dt));?></span></td>
                    </tr>
                   
                </tbody>
            </table>
            </td>
        

    </tbody>
    </table>
   <table>
   <tr>
   	<td colspan = '2'>
   	<b>Terms and Conditions </b>
   	</td>
   <tr>
   <tr style ="line-height:10px;">
   <td>1. </td>
   <td  style="font-size:12px;" >The Customer must receive job sheet when the product is given for repairs at tele care Store and the contents of the job sheet must be verified by the customer. </td>
   </tr>
     <tr style ="line-height:10px;">
   <td>2. </td>
   <td style="font-size:12px;">The Customer must produce the original job sheet at the time of taking the delivery Tele-Care Store reserve the right to refuse delivery upon non production of the original job sheet.</td>
   </tr>
     <tr style ="line-height:10px;">
   <td>3. </td>
   <td style="font-size:12px;"> The Store is not liable and delays, non performs failure or non delivery of the product to contingencies arising from any force majeure, storm, earthquake, accident strikes, lock out, industial dispute, labour trouble, transportation embargo imminence or the exixtence of any emergency, war, war like condition, civil-commotion right in ability to obtain any material refusal of license approval of imposition of sanctions any measure taken by goverment which render it impossible or impractical for Store perform, supply, service or deliver the product to the customer.</td>
   </tr>
     <tr style ="line-height:10px;">
   <td>4. </td>
   <td style="font-size:12px;"> The case of unauthorized repair, liquid damage and physical damage is treated as out of warrenty and customer has to pay spares part and service charge.</td>
   </tr>
     <tr style ="line-height:10px;">
   <td>5. </td>
   <td style="font-size:12px;">In the case of out of warrenty repair the handset may be dead company will not be responsible for it. </td>
   </tr>
     <tr style ="line-height:10px;">
   <td>6. </td>
   <td style="font-size:12px;">It's not necessary customer has to get same IMEI NO handset. It may be different due to PCB problem for warrenty cases. </td>
   </tr>
     <tr style ="line-height:10px;">
   <td>7. </td>
   <td style="font-size:12px;"> The Customer has to collect their handset within the three month of submission date after that company will not be liable. </td>
   </tr>
     <tr style ="line-height:10px;">
   <td >8. </td>
   <td style="font-size:12px;"> The Store is not liable for any memory setting and data loss during the repair. By accepting the job sheet, it is deemed that the customer agree to all the term and condition mentioned in the job sheet.</td>
   </tr>
   </table>
    
    
     <div style="border-top:1px dashed #000; margin-top:10px; height:10px; font-size:11px; line-height:5px;">&nbsp;</div>
        <table width="100%" cellpadding="5" cellspacing="0" class="tblgrid" border="1" align="center" style="border-bottom:0px solid;">	
        <tbody> 
         <tr>
            <td style="vertical-align:top;"  >
                 <table class="tbl" cellpadding="5" cellspacing="0" style = "border-bottom:0px" >
                    <tbody >
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label><?php echo $this->lang->line('name');?>: </label></th>
                            <td style="font-size:12px; text-align:left;"><span> <?php echo $preview_details->cust_first_name.' '.$preview_details->cust_last_name;?> </span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Address:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->cust_address;?><?php echo $this->lang->line('city')?>:<?php echo $preview_details->city_name;?>,&nbsp;District:<?php echo $preview_details->district_name;?>,&nbsp;Zone:<?php echo $preview_details->zone_name;?>&nbsp;</span>
                            </td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Landmark:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->cust_landmark;?></span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Phone:</label></th>
                            <td style="font-size:12px; text-align:left;"><span style="font-weight:bold;">(office)</span><?php echo $preview_details->cust_phone_office;?>&nbsp;<span style="font-weight:bold;">(home)</span><?php echo $preview_details->cust_phone_home;?>&nbsp;<span style="font-weight:bold;">(mobile)</span><?php echo $preview_details->cust_phone_mobile;?>
                            </td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Service Centre:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->sc_name;?></span></td>
                        </tr>
                         <tr style ="line-height:10px;">
                        <th style="font-size:12px; text-align:left;"><label>Warranty:</label></th>
                        <td style="font-size:12px; text-align:left;"><span><?php echo $call_type;?></span></td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="vertical-align:top;"   >
                 <table class="tbl" cellpadding="5" cellspacing="0" style = "border-bottom:0px">
                    <tbody>
                        
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Jobcard NO:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->call_uid;?></span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Product:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->product_name;?></span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label> IMEI/Set Serial No:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php if($preview_details->call_serial_no){ echo $preview_details->call_serial_no;}else{ echo 'NOT CONF';}?></span></td>
                        </tr>
                        <tr style ="line-height:10px;">
                            <th style="font-size:12px; text-align:left;"><label>Complaint:</label></th>
                            <td style="font-size:12px; text-align:left;"><span><?php echo $preview_details->call_service_desc;?></span></td>
                        </tr>                        
                    </tbody>
            </table>
            </td>
            <td style="vertical-align:top;"  >
               <table class="tbl" cellpadding="5" cellspacing="0">
                <tbody>
                    <tr style ="line-height:10px;">
                        <th style="font-size:11px; text-align:left;"><label>Complaint Date:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo format_date(strtotime($preview_details->call_dt)).'&nbsp;&nbsp;&nbsp;&nbsp;'.date("H:i",strtotime($preview_details->call_tm));?></span></td>
                    </tr>
                    <tr style ="line-height:10px;">
                        <th style="font-size:11px; text-align:left;"><label>Brand:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->brand_name;?></span></td>
                    </tr>
                    <tr style ="line-height:10px;">
                        <th style="font-size:11px; text-align:left;"><label>Model:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->model_number;?></span></td>
                    </tr>
                    <tr style ="line-height:10px;">
                        <th style="font-size:11px; text-align:left;"><label>Dealer:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo $preview_details->call_dealer_name;?></span></td>
                    </tr>
                    <tr style ="line-height:10px;">
                        <th style="font-size:11px; text-align:left;"><label>Purchase Date:</label></th>
                        <td style="font-size:11px; text-align:left;"><span><?php echo format_date(strtotime($preview_details->call_purchase_dt));?></span></td>
                    </tr>
                   
                </tbody>
            </table>
            </td>
         </tr>
         </tbody>
         </table>
         <table width="100%" cellpadding="5" cellspacing="0" class="tblgrid" border="1" align="center" border-bottom="0px">
         <tbody>
        <tr style ="line-height:10px;">
            <th style="width:100px; font-size:11px;" width="50"><label>Date</label></th>
            <th style="width:100px; font-size:11px;" ><label>Tech. Name</label></th>
            <th style="width:650px; font-size:11px; white-space:nowrap;" width="650"><label>Action Taken</label></th>
            <th style="width: 250px;font-size:11px;" ><label>Parts Used</label></th>
            <th style="width: 50px; font-size:11px;"><label>Qty</label></th>
        </tr>
        <tr style ="line-height:10px;" class="">
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
        
           
           
           
            <td ><span>
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
            <td ><span>&nbsp;</span> </td>
            
        </tr>
        
        <?php for($i=1;$i<=3;$i++){?>
        <tr style ="line-height:10px;" class="">
            <td style="font-size:11px;"><span>&nbsp;</span> </td>
            <td style="font-size:11px;"><span>&nbsp;</span> </td>
            <td style="font-size:11px;"><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            
        </tr>
        <?php }?>            
        <tr style ="line-height:10px;">
            <th style="font-size:11px;"><label>Last Job No.</label></th> 
            <th style="font-size:11px;"><label>Tech. Name</label></th> 
           <th style="font-size:11px;"><label>Action Taken</label></th>
            <th style="font-size:11px;"><label>Used Parts</label></th>
            <th style="font-size:11px;"><label>Qty</label></th>
           
        </tr>
        <tr style ="line-height:10px;" class="">
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
          
            
        </tr>  
         <tr style ="line-height:10px;" class="">
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
          
            
        </tr>                         
       
        <tr style ="line-height:10px;" class="">
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
            <td><span>&nbsp;</span> </td>
          
            
        </tr>                         
                              
       
      
    </tbody>
    </table>
    <table width = "100%">
     <tr style ="line-height:10px;">
       <td  style= "text-align:right">Logged by </td>
        
        </tr>
         <tr style ="line-height:10px;">
       <td  style= "text-align:right">&nbsp; </td>
        
        </tr>
         <tr style ="line-height:10px;">
       <td  style= "text-align:right">Signature </td>
        
        </tr>
    </table>
       
</div>
<table align="right">
  <tr style ="line-height:10px;">
    <td style="text-align:right;font-size:11px;"><input type="button" name="print_card" id="print_card" value="Print" class="button" onclick="printJobCard();"  /></td>
    <td style="text-align:right;font-size:11px;"><input type="button" name="cancel_card" id="cancel_card" value="Cancel" class="button" onclick="javascript:$(document).trigger('close.facebox');"  /></td>
  </tr>
</table>


</div>
