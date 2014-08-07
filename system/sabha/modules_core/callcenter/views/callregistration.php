<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php
$call_details->call_purchase_dt = ($call_details->call_purchase_dt=='0000-00-00')?'':$call_details->call_purchase_dt;
$this->load->library('nepalicalendar');
//eng to nep date conversion
$nep_purchase_date = '';
if($call_details->call_purchase_dt){
	$str = $call_details->call_purchase_dt;
	$arr = explode("-",$str);
	$this->load->library('nepalicalendar');
	$date = $this->nepalicalendar->eng_to_nep($arr[0],$arr[1],$arr[2]);

	$nep_purchase_date = sprintf("%02d",$date['date']).'/'.sprintf("%02d",$date['month']).'/'.$date['year'];
}
?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.timepicker.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/jquery/jquery.ui.timepicker.js" type="text/javascript"></script>
<script>
	$(function() {
		$( ".datepicker" ).datepicker({
			buttonText:'English Calendar',
			changeMonth: true,
			changeYear: true,
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
		});
	});
	
</script>
<style>
#npcalendar img {
	padding-left: 5px;
	margin-top: -4px;
	vertical-align: middle;
}

#product_purchase_date {
	
}

#select_district_box,#select_city_box,#select_product_box,#select_model_box,#sc_box,#engineer_select_box,#defect_select_box,#reasonlist,#repair_select_box,#symptom_select_box,#cal
	{
	position: relative;
}

#select_district_box .loading,#select_city_box .loading,#select_product_box .loading,#select_model_box .loading,#sc_box .loading,#engineer_select_box .loading,#reasonlist .loading,#defect_select_box .loading,#repair_select_box .loading,#symptom_select_box .loading,#cal .loading
	{
	position: absolute;
	left: 0px;
	top: 0;
	width: 100%;
	height: 30px;
	margin: 0 auto;
	text-align: center;
}



.basic_button {
	margin: 0;
	padding: 0;
	font-size: 9px;
}
.col-1, .col-2 {
float: left;

width: 49.5%;
margin-left: 0px;
}
.full_width {
float: left;
width: 95.8%;
margin-left: 9px;
}
#submit_btn {
    float: left;
    width: 100%;
	margin-left:35px;
	margin-bottom:11px;
}
</style>
<?php if($this->session->flashdata('call_same_serialnumber')){?>
<script type="text/javascript">
if(confirm('<?php echo sprintf($this->lang->line('call_serial_no_same_msg'),$this->session->flashdata('call_same_serialnumber'),$this->session->flashdata('call_uid'));?>')){
	window.location.href= '<?php echo site_url();?>callcenter/callregistration/<?php echo $this->session->flashdata('call_id');?>';
}
</script>
<?php }?>
<script type="text/javascript">
$(document).ready(function(){
	$("#callRegistrationForm").validationEngine('attach', {
		promptPosition : "centerRight",scroll:false
	});
});
</script>
<?php
if((int)$call_details->call_id==0){?>
<script type="text/javascript">
$(document).ready(function(){
if($("#zone_select").length){
	getdistrictbyzone($("#zone_select").val(),<?php echo $call_details->district_id;?>);
	}
});
</script>
<?php }?>
<?php if ($this->session->flashdata('save_status')) { ?>
<script type="text/javascript">
$(document).ready(function(){
	var is_new = '<?php echo $this->session->flashdata('is_new');?>';
	if(is_new){alert('<?php echo $this->session->flashdata('call_save'); ?>.\nCall ID is: <?php echo $call_details->call_uid;?>');}
	else{ alert('<?php echo $this->session->flashdata('call_save');?>');}
});
</script>
<?php }if ($this->session->flashdata('save_status')==false && $this->session->flashdata('call_save')!=''){?>
<script>
$(document).ready(function(){
	alert('<?php echo $this->session->flashdata('call_save'); ?>');	
});
</script>
<?php }?>
<script type="text/javascript">
            $(document).ready(function() {
                $('.timepicker').timepicker({
                    showMinutes: true,
                    showPeriod: false,
                    showLeadingZero: true,
					hours: {
						starts: 0,                
						ends: 23                  
					},
					showCloseButton: true,
					closeButtonText: 'Done',
					showNowButton: true,
					nowButtonText: 'Now',
                });
            })
        </script>
<style>
.datepicker {
	width: 70%;
	margin: 5px 5px 0 0;
}

.timepicker {
	box-shadow: 2px 0px 0px 0px #49AB3C inset;
}


#model_id,#calltype_select {
	
}
</style>


<script language="javascript">
$(document).ready(function(){
	var eng= $('#engineer_select').val();
	//alert (eng);
$('#cengineer_id').val(eng);						   
						   
})
	
</script>
<form method="post" name="callRegistrationForm"	id="callRegistrationForm">
    <div class="col-1">
        <fieldset>
        <legend><?php echo $this->lang->line('customer_information'); ?></legend>
        <div class="form_row">
		<div class="col colb">
            <div id="field_fname">
            <label><?php echo $this->lang->line('name'); ?>: </label>
            <span class="lblreq"></span><input type="text"
                    value="<?php echo $call_details->cust_first_name;?>"
                    id="cust_first_name" name="cust_first_name"
                    class="validate[required] text-input" />
            </div>
            </div>
			<div class="col colb">
            <div id="field_mname">
                <span class="lblreq"></span><input type="text"
                    value="<?php //echo $call_details->filed_two;?>"
                    id="filed_two" name="filed_two"
                    class="text-input" />
            </div>
			</div>
            <div class="col colb">
            <div id="field_lname">
                <input type="text"
                    value="<?php echo $call_details->cust_last_name;?>"
                    id="cust_last_name" name="cust_last_name" class="text-input" /> <img
                    alt="Search Customer" title="Search Customer" class="btn"
                    onclick="getCustomerList();"
                    src="<?php echo base_url();?>assets/style/img/icons/search.gif"
                    style="margin-bottom: -8px;" />
            </div>
			</div>
        </div>
            
        <div class="form-row">
		<div class="col">
            <div id="field_zone">
                <label><?php echo $this->lang->line('zone'); ?>: </label>
                <span class="lblreq"></span><?php echo $zone_select;?>
            </div>
			</div>
           
            <div class="col">
			<div id="field_district">
                <label><?php echo $this->lang->line('district'); ?>: </label>
                <span class="lblreq"></span><span
                id="select_district_box"><?php echo $district_select;?></span>
            </div>
			</div>
            <div class="col">
            <div id="field_city">
                <label>City:</label>
                <span class="lblreq"></span><span id="select_city_box"><?php echo $city_select;?></span>
            <img alt="Search City" title="Search City" class="btn"
                onclick="getCitySearchList();"
                src="<?php echo base_url();?>assets/style/img/icons/search.gif"
                style="margin-bottom: -8px;" />
            </div>
            </div>
		</div>
		
			<div class="form_row">
			<div class="col">
				<div id="field_address">
					<label><?php echo $this->lang->line('address'); ?></label>
					<input type="text"
					value="<?php echo $call_details->cust_address;?>" id="cust_address"
					name="cust_address" class=" text-input" />
				</div>
			</div>
			
           	<div class="col">
            <div id="field_landmark">
                <label>Landmark</label>
                <input type="text"
                value="<?php echo $call_details->cust_landmark;?>"
                id="cust_landmark" name="cust_landmark" class="text-input" />
            </div>
			</div>
			</div>
			
            <div class="form_row">
			<div class="col">
            <div id="field_phone">
                <label>Home</label>
                <div class="innertd"><input
                name="cust_phone_home" id="cust_phone_home" style="" type="text"
                class="validate[custom[integer],maxSize[10]] text-input"
                value="<?php echo $call_details->cust_phone_home;?>" maxlength="10" />
            <div class="clear"></div>
            </div>
            </div>
			</div>
            <div class="col">
            <div id="field_office">
                <label>Office</label>
                <input name="cust_phone_office"
                id="cust_phone_office" style="" type="text"
                class="validate[custom[integer],maxSize[10]] text-input"
                value="<?php echo $call_details->cust_phone_office;?>"
                maxlength="10" />
            </div>
			</div>
            
			<div class="col">
            <div id="field_mobile">
                <label><?php echo $this->lang->line('mobile'); ?>: </label>
                <input type="text"
                value="<?php echo $call_details->cust_phone_mobile;?>" 
                id="cust_phone_mobile" name="cust_phone_mobile" style=""
                class="validate[required,custom[integer],maxSize[10],minSize[10]] text-input"
                maxlength="10" />
            </div>
			</div>
			</div>
			
			
            <div class="form_row">
				<div class="col cola">
				<div id="field_appointment">
					<label><?php echo $this->lang->line('customer_preferred_date_time'); ?></label>
					<input style="140px" type="text"
					value="<?php echo format_date(strtotime($call_details->call_cust_pref_dt));?>"
					id="call_cust_pref_dt" name="call_cust_pref_dt" readonly="readonly"
					class="datepicker text-input" /><input type="text"
					name="call_cust_pref_tm" id="call_cust_pref_tm"
					class="timepicker text-input" readonly="readonly"
					value="<?php echo date("H:i",strtotime($call_details->call_cust_pref_tm));?>" />
				</div>
				</div>
            <div class="col cola">
            <div id="field_nocall">
                <label><?php echo $this->lang->line('call_nocall_tm'); ?>: </label>
                <input type="text"
                name="call_nocall_tm" id="call_nocall_tm"
                value="<?php echo $call_details->call_nocall_tm;?>"
                class="text-input" />
            </div>	
			</div>
        </div>		
        </fieldset>
		
        
		<div id="call">
        <fieldset>
        <legend><?php echo $this->lang->line('call_information'); ?></legend>
           	<div class="form_row">
				<div class="col">
				     <div id="field_callid">
					<?php if($call_details->call_uid){?>
						<label><?php echo $this->lang->line('call_id'); ?>: <br /><?php echo $call_details->call_uid;?></label><br />
						
						<?php }else{?>
                                                <?php } ?>
                </div>
				</div>
				<div class="col">
				<div id="reg">
                <div id="field_registered">
                    <label><?php echo $this->lang->line('registered_date'); ?>:	<br /><?php echo format_date(strtotime($call_details->call_dt));?>
                    <span style="padding-left: 10px;">&nbsp;&nbsp;&nbsp;<?php 
                    $call_details->call_dt = ($call_details->call_dt=='0000-00-00')?'':$call_details->call_dt;
                    $str1 = $call_details->call_dt;
                    if($str1){
                        $arr1 = explode("-",$str1);
                        $this->load->library('nepalicalendar');
                        $date1 = $this->nepalicalendar->eng_to_nep($arr1[0],$arr1[1],$arr1[2]);
                            
                        echo 'B.S.&nbsp;&nbsp;'.sprintf("%02d",$date1['date']).'/'.sprintf("%02d",$date1['month']).'/'.$date1['year'];
                    }else{
                        echo "&nbsp;";
                    }
                    ?></span></label><br />
                </div>
				
				</div>
				</div>
              <div class="col">
                <div id="field_regtime">
                    <label><?php echo $this->lang->line('registered_time'); ?>: </label>
                    <label><?php echo $call_details->call_tm;?></label>
                </div>
				
            </div>
			</div>
			
			
               <div class="form_row">
			   <div class="col">
				<div id="field_service">
                    <label><?php echo $this->lang->line('service_center'); ?>: </label>
                    <span id="sc_box"><?php echo $servicecenter_select;?></span>
                </div>
				</div>
				<div class="col">
				<div id="field_engineer">
                    <label><?php echo $this->lang->line('engineer'); ?>: </label>
                    <span id="engineer_select_box"><?php echo $engineer_select;?></span>
                </div>
				</div>
				
				
				
            </div>
        </fieldset>
		</div>
		
		
			
        
        <?php if($call_details->call_id>0){?>
        	<?php $this->load->view('call_visit_details');?>
			<?php $this->load->view('call_fault_details');?>
            
        <?php }?>
        
    </div>
	
	<div class="col-2">
    	<fieldset>
		<legend><?php echo $this->lang->line('product_information'); ?></legend>
				<div class="form_row">
			<div class="col">
				<div id="field_brand">
					<label><?php echo $this->lang->line('brand_type'); ?>: </label>
					<span class="lblreq"></span><?php echo $brand_select;?>
				</div>
				</div>
				<div class="col">
					<div id="field_product">
						<label><?php echo $this->lang->line('product_type'); ?>: </label>
						<span class="lblreq"></span><span
						id="select_product_box"><?php echo $product_select;?></span>
					</div>
			    </div>
				<div class="col">
				<div id="field_model">
					<label><?php echo $this->lang->line('model'); ?>: </label>
                	<span class="lblreq"></span><span id="select_model_box"><?php echo $model_select;?></span>
					<img alt="Search Model" title="Search Model" class="btn" onclick="getmodellist();" src="<?php echo base_url();?>assets/style/img/icons/search.gif" style="margin-bottom: -8px;" />
				</div>
				</div>
				</div>
				<div class="form_row">
                    <div class="col cl">
                        <div id="field_serial">
                            <label><?php echo $this->lang->line('serial_number');?>	:</label>
                            <input type="text"
                            value="<?php echo $call_details->call_serial_no;?>"
                            id="product_serial_number" name="product_serial_number"
                            class="text-input validate[required]" />
                        </div>
                    </div>
					
                    <div class="col cl">	
                        <div id="field_pdate">	
                            <label><?php echo $this
                            ->lang->line('pur_date'); ?>: </label>
                        </div>
                        
                        <input type="text" value="<?php echo format_date(strtotime($call_details->call_purchase_dt));?>" id="product_purchase_date" name="product_purchase_date" readonly="readonly" class="datepicker text-input validate[required]" />
                        <span><?php echo ($nep_purchase_date=='')?'':'&nbsp;&nbsp;B.S.&nbsp;&nbsp;';?>
                            <span id="lblnepalidate" style=" padding: 0 5px;"><?php echo $nep_purchase_date;?></span>
                            <!--<span id="npcalendar" title="Nepali Calendar"><?php echo icon('nepali_calendar','Nepali Calendar','png');?></span>-->
                            <?php $this->load->view('calendar/calendar/nepali_calendar');?>
                        </span>
                    </div>
				</div>
				
				
				
				<div class="form_row">
				<div class="col">
					<div id="field_newserial">
						<?php if($call_details->call_id>0){?>
								<label><?php echo $this->lang->line('new_serial_number');?></label>
								<input type="text"
									name="product_serial_number_new" id="product_serial_number_new"
									value="" class="text-input" />
										<?php
										if($total_serial_numbers>0){?>
										<input type="button"
										onclick="getSerialHistory('<?php echo $call_details->call_id;?>');"
										class="basic_button" value="View Serial History" />
										<?php }?>
					</div>	
					</div>
					<div class="col">
						<div id="field_dealer">
							<label>Dealer Name :</label>
							<input style=""
							type="text" value="<?php echo $call_details->call_dealer_name;?>"
							id="product_dealer_name" name="product_dealer_name"
							class="text-input" />
								<?php }?>
						</div>
					</div>	
				</div>	
							
				<div class="form_row">
				<div class="col">
				<div id="field-calltype">				
						<label>Call Type</label>
						<?php echo $calltype_select;?>
				</div>		
				</div>
				
				<div class="col">	
				<div id="field_callat">	
						<label><?php echo $this->lang->line('call_at');?></label>
						<?php echo $callat_select;?>
				</div>	
				</div>
				
				<div class="col">
				<div id="field_callform">	
						<label><?php echo $this->lang->line('call_from');?></label>	
						<?php echo $callfrom_select;?>
				</div>		
				</div>
				</div>
				<div class="form_row">
				<div class="col">
				<div id="field_servdes">
					<label><?php echo $this->lang->line('service_description'); ?>:
					</label>	
					<span class="lblreq"
					style="vertical-align: top;"></span><textarea
					id="service_desc" name="service_desc" style="height:60px"
					class="validate[required] text-input"><?php echo $call_details->call_service_desc;?></textarea>
				</div>
				</div>
					<div class="col">
				<div id="field_servtype">
					<label><?php echo $this->lang->line('service_type_name');?></label>
                    <?php echo $callservicetype_select;?>
				</div>
            </div> 
			
			</div>
        </fieldset>
		 
		<?php if($call_details->call_id>0){?>
			
            <?php $this->load->view('call_closure_summary');?>	
        <?php }?>
		
	</div>
    
    <div class="full_width">
    	<?php if($call_details->call_id>0){?>
            <?php $this->load->view('used_parts');?>
            <?php $this->load->view('porequest');?>
        <?php }?>
    </div>
	
        <?php //if ($this->session->userdata('usergroup_id')!=7){?>
    <div id="submit_btn">
            <?php
            $show_message = TRUE;
            if(($call_details->call_status!=3 && $call_details->call_status!=4) || $this->session->userdata('usergroup_id')==6 ){
                $show_message = FALSE;
                ?> <input type="submit"
                value="<?php echo $this->lang->line('save'); ?>" name="btn_save"
                id="btn_save" class="button" /> <?php }?>
                 <?php if((int)$call_details->call_id==0){?><input
                type="button" value="<?php echo $this->lang->line('clearform'); ?>"
                name="btn_edit" id="btn_clear" class="button"
                onclick="clearForm();" /><?php }?> <input type="button"
                name="btn_close" id="btn_close" value="Close"
                onclick="closeForm();" class="button" /> <?php if((int)$call_details->call_id>0){?>
            <input type="button" name="print_job_card" id="print_job_card"
                value="Print Job Card" class="button" onclick="showJobCard();" /> <?php }?>
                 <?php if($call_details->call_status==3||$call_details->call_status==4){?>
                <input type="button" name="reRegisterCall" id="reRegisterCall"
                value="Re-Register" class="button" onclick="showReRegister(<?php echo $call_details->call_id; ?>);" />
                <?php } ?>
                 <?php if($call_details->call_status==3){?>
                <input type="button" name="happycall" id="happycall"
                value="Happy Call" class="button" onclick="showhappycall(<?php echo $call_details->call_id;?>);" />
                <?php } ?>
                
                <?php if($call_details->call_status==3){?>
                <input type="button" name="generatebill" id="generatebill"
                value="Generate Bill" class="button" onclick="billgeneration(<?php echo $call_details->call_id;?>);" />
                <?php } ?>
                
              <span style=  "<?php if($call_details->call_type==3 && $call_details->call_reason_pending == 'Part Pending' ){?>display:block; float:right<?php }else{ ?> display:none <?php }?>;" id="generate_ro" >
                <input type="button" name="generatero" id="generatero"
                value="Generate RO" class="button" onclick="rogeneration(<?php echo $call_details->call_id;?>);" />
                </span>
               
                <?php if($show_message==TRUE){?> <span class="lblreq"><?php printf($this->lang->line('cancel_closed_message'),$this->mdl_mcb_data->getStatusDetails($call_details->call_status,'callstatus'));?></span>
                <?php }?>
    <?php // }?>
    <input type="hidden" name="hdncallid" id="hdncallid"
    value="<?php echo $call_details->call_id;?>" /> <input type="hidden"
    name="hdncallcust_id" id="hdncallcust_id"
    value="<?php echo $call_details->call_cust_id;?>" />
    </div>
    </form>
    </div>
