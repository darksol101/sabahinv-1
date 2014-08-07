<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.timepicker.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		$( ".datepicker" ).datepicker({
			showOn: "button",
			buttonImage: "<?php echo base_url();?>assets/style/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: '<?php echo $this->mdl_mcb_data->setting('default_date_format_picker')?>'
		});
	});
</script>
<style type="text/css">
.datepicker{margin:5px 5px 0 0;}
#engineer,#cstatuslist,#scenter{width:215px;}
input[type="checkbox"]{vertical-align:middle!important;}
.chbx{line-height:10px;}
</style>
<div class="toolbar1" style="width:97%;">
  <form id="callForm" name="callForm" method="post" action="<?php echo base_url()?>/callcenter/searchcall">
  <table cellspacing="0" cellpadding="0" width="100%">
  	<tbody style="width:80%">
    <col width="10%" /><col width="40%" /><col width="10%" /><col width="40%" />
  	<tr>
    	<th><label><?php echo $this->lang->line('call_id');?></label></th>
        <td><input type="text" class="text-input" value="<?php echo $this->input->get('cid');?>" name="call_uid" onkeydown="Javascript: if (event.keyCode==13) javascript:document.getElementById('callForm').submit();"  /></td>
        <th><label><?php echo $this->lang->line('name');?></label></th>
        <td><input type="text" class="text-input" value="<?php echo $this->input->get('cn');?>" name="cust_name" onkeydown="Javascript: if (event.keyCode==13) javascript:document.getElementById('callForm').submit();" /></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('product');?></label></th>
        <td><input type="text" class="text-input" value="<?php echo $this->input->get('pn');?>" name="product_name" onkeydown="Javascript: if (event.keyCode==13) javascript:document.getElementById('callForm').submit();" /></td>
        <th><label><?php echo $this->lang->line('serialno.');?></label></th>
        <td><input type="text" class="text-input" value="<?php echo $this->input->get('sn');?>" name="serial_number" onkeydown="Javascript: if (event.keyCode==13) javascript:document.getElementById('callForm').submit();" /></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('phone');?></label></th>
        <td><input type="text" class="text-input" value="<?php echo $this->input->get('ph');?>" name="phone" onkeydown="Javascript: if (event.keyCode==13) javascript:document.getElementById('callForm').submit();" /></td>
        <th><label><?php echo $this->lang->line('engineer');?></label></th>
        <td><?php echo $engineer_select;?></td>
    </tr>
    <tr>
    	<th><label><?php echo $this->lang->line('date_from');?></label></th>
        <td><input type="text" name="from_date" id="from_date" class="datepicker text-input" value="<?php echo $this->input->get('from');?>" onkeydown="Javascript: if (event.keyCode==13) javascript:document.getElementById('callForm').submit();" />
        </td>
        <th><label><?php echo $this->lang->line('date_to');?></label></th>
        <td><input type="text" name="to_date" id="to_date" class="datepicker text-input" value="<?php echo $this->input->get('to');?>" onkeydown="Javascript: if (event.keyCode==13) javascript:document.getElementById('callForm').submit();" />
        </td>
    </tr>
    <tr>
    	<th style="vertical-align:middle;"><label><?php echo $this->lang->line('status');?></label></th>
        <td><?php
			//echo $call_status;
			$status_ids = $this->input->get('cs');
			
			
				$arr_status = explode("_",$status_ids);
			
		
			$cstatuslist=$this->mdl_mcb_data->getStatusOptions('callstatus');
			foreach($cstatuslist as $chk){
			$cs = (in_array($chk->value,$arr_status))?TRUE:FALSE;
			
				//if(($chk->value == 0 || $chk->value == 1 || $chk->value == 2) && count($arr_status)==0 ){$cs = TRUE;}else{$cs = (in_array($chk->value,$arr_status))?TRUE:FALSE;}
				echo form_checkbox('cstatuslist[]', $chk->value, $cs).'<span class="chbx">'.$chk->text.'&nbsp;&nbsp;</span>';
			}
			?>
           </td>
        <td><label><?php echo $this->lang->line('service_center');?></label></td>
        <td><?php echo $scenters;?></td>
    </tr>

 <tr>
			<th> <label> <?php echo $this->lang->line('reopened');?></label></th>
			<td>
			
           <input type="hidden" name="reopened" class="reopened" id="reopened" value="0" />
            <input type="checkbox" name="reopened" class="reopened" id="reopened" value="1" <?php if($this->session->userdata('reo')==1){?> checked="checked"  <?php }?> /> Reopened
           
             
            
            </td>
			<td><label><?php echo $this->lang->line('happy_call');?></label></td>
			<td>   <input type="hidden" name="verified" class="verified" id="verified" value="0" />
            <input type="checkbox" name="verified" class="verified" id="verified" value="1" <?php if($this->session->userdata('ver')==1){?> checked="checked"  <?php }?>/> Verified</td>
		</tr>


    </tbody>
    <tfoot style="width:100%"><tr><td style="text-align:right!important;" colspan="4"><input type="button" name="search"  onclick="javascript:document.getElementById('callForm').submit();" value="Search" class="button"  />&nbsp;<!--<input type="button" name="button" class="button" value="Download" onclick="excelDownload();"/>--></td></tr></tfoot>
  </table>
 </form> 
 </div>
<div style="float:right; margin-bottom:5px;">
<?php
$page = $this->uri->segment(3);
$start = 0;
if($config['total_rows']>0){
	$start = $page+1;
	if($config['total_rows']>($page+$config['per_page'])){
		$end = $page+$config['per_page'];
	}else{
		$end = $config['total_rows'];
	}
?>
<span><strong><?php echo $start;?> - <?php echo $end?></strong></span> of <span><strong><?php echo $config['total_rows'];?></strong></span>
<?php }?>
<input type="button" name="button" class="button" value="Download" onclick="excelDownload();"/>
</div>
<div style="clear:both;"></div>
<div id="liscalls">
	<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
    	<col width="1%" /><col width="10%" /><col width="19%" /><col width="10%" /><col width="7%" /><col width="8%" /><col width="10%" /><col width="10%" /><col width="10%" /><col width="10%" /><col width="5%" />
    	<thead>
        	<tr><th>S.No.</th><th>Call ID</th><th style="text-align:center;"><?php echo $this->lang->line('name');?></th><th style="text-align:center">City</th><th style="text-align:center;">Model Number</th><th style="text-align:center"><?php echo $this->lang->line('serialno.');?></th><th style="text-align:center;"><?php echo $this->lang->line('registration_date');?></th><th style="text-align:center;">Engineer</th><th style="text-align:center;">Store</th><th style="text-align:center;">Aging Time</th><th>Status</th></tr>
        </thead>
        <tbody>
        	<?php
            $i=1;
			foreach ($calls as $call){
			$trclass=$i%2==0?" class='even' ": " class='odd' ";
			if($call->call_status==1 && $call->call_reason_pending=='Part Pending'){
				$call->call_status=2;
			}
			$status=$call->call_status=="0"?icon("enabled","Active","png"):icon("disabled","Inactive","png");
			$cstatus = $this->mdl_mcb_data->getStatusDetails($call->call_status,'callstatus');
			$color = '';
			//for open
			if($call->call_status==0){
				$color = 'style="color:#005B93;"';
			}
			// for part pending and courior in transit
			if($call->call_status== 1 && $call->call_reason_pending == 'Courier - In Transit'){
			$color = 'style="color:#E4287C;"';
		}
			//for pending
			if($call->call_status==1){
				$color = 'style="color:#1A9F04;"';
			}
			//for part pending
			if($call->call_status==2){
				$color = 'style="color:#ff9900;"';
			}
			//for closed
			if($call->call_status==3){
				$color = 'style="color:#A9A9A9;"';
			}
			//for cancelled
			if($call->call_status==4){
				$color = 'style="color:#C42000;"';
			}
// for part pending and courior in transit
			if($call->call_status== 1 && $call->call_reason_pending == 'Courier - In Transit'){
			$color = 'style="color:#E4287C;"';
		}
			
			?>
            <tr<?php echo $trclass;?>>
            	<td><?php echo $page+$i;?></td>
                <td><a <?php echo $color;?> target="_blank" href="<?php echo site_url();?>callcenter/callregistration/<?php echo $call->call_id;?>"><?php echo $call->call_uid;?></a></td>
                <td style="text-align:center;"><?php echo $call->cust_first_name.' '.$call->cust_last_name;?></td>
                <td style="text-align:center"><?php echo $call->city_name; ?></td>
                <td style="text-align:center;"><?php echo $call->model_number;?></td>
                <td style="text-align:center;"><?php echo $call->call_serial_no;?></td>
                <td style="text-align:center;"><?php echo format_date(strtotime($call->call_dt));?></td>
                <td style="text-align:center;"><?php echo $call->engineer_name;?></td>
                <td style="text-align:center; text-transform:capitalize;"><?php echo strtolower($call->sc_name);?></td>
                <td style="text-align:center;"><?php if($call->call_status<3) {echo CalculateAgingDurationInDays($call->call_dt,$call->call_tm);}
				if($call->call_status==3){
				$average_closing =  CalculateAvgClosingTimeStamp($call->call_dt,$call->call_tm,$call->closure_dt,$call->closure_tm);
				echo CalculateSecondsToDays($average_closing);
			}
				?></td>
                <td style="text-align:center;"><span <?php echo $color;?>><?php echo $cstatus;?> <?php if ($call->happy_status == 1){echo '  '.'(Verified)';}?></span></td>
            </tr>
			<?php $i++; }	?>
        </tbody>
        <tfoot><tr><td colspan="5"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table>
</div>
