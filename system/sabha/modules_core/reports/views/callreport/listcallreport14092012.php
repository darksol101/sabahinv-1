<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		generateCallReport();
	})
});
</script>
<div
	style="float: right; margin-bottom: 5px;"><?php
	$page = $this->input->get('currentpage');
	$start = 0;
	if($config['total_rows']>0){
		$start = $page+1;
		if($config['total_rows']>($page+$config['per_page'])){
			$end = $page+$config['per_page'];
		}else{
			$end = $config['total_rows'];
		}
		?> <span><strong><?php echo $start;?> - <?php echo $end?></strong></span>
of <span><strong><?php echo $config['total_rows'];?></strong></span> <?php }?>
<!--<input type="button" name="button" class="button" value="Download" onclick="excelDownload();"/>
<input type="button" name="button" class="button" value="Email" onclick="email_pop();"/>-->
</div>
<div style="clear: both;"></div>
<div id="liscalls">
<table class="tblgrid" cellpadding="0" cellspacing="0" width="100%">
	<col width="1%" />
	<col width="10%" />
	<col width="19%" />
	<col width="10%" />
	<col width="7%" />
	<col width="8%" />
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
	<col width="5%" />
	<thead>
		<tr>
			<th>S.No.</th>
			<th>Call ID</th>
			<th style="text-align: center;"><?php echo $this->lang->line('name');?></th>
			<th style="text-align: center">City</th>
			<th style="text-align: center;">Model Number</th>
			<th style="text-align: center"><?php echo $this->lang->line('serialno.');?></th>
			<th style="text-align: center;"><?php echo $this->lang->line('registration_date');?></th>
			<th style="text-align: center;">Engineer</th>
			<th style="text-align: center;">Store</th>
			<th style="text-align: center;">Aging Time</th>
			<th>Status</th>
		</tr>
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
		?>
		<tr <?php echo $trclass;?>>
			<td><?php echo $page+$i;?></td>
			<td><a <?php echo $color;?> target="_blank"
				href="<?php echo site_url();?>callcenter/callregistration/<?php echo $call->call_id;?>"><?php echo $call->call_uid;?></a></td>
			<td style="text-align: center;"><?php echo $call->cust_first_name.' '.$call->cust_last_name;?></td>
			<td style="text-align: center"><?php echo $call->city_name; ?></td>
			<td style="text-align: center;"><?php echo $call->model_number;?></td>
			<td style="text-align: center;"><?php echo $call->call_serial_no;?></td>
			<td style="text-align: center;"><?php echo format_date(strtotime($call->call_dt));?></td>
			<td style="text-align: center;"><?php echo $call->engineer_name;?></td>
			<td style="text-align: center; text-transform: capitalize;"><?php echo strtolower($call->sc_name);?></td>
			<td style="text-align: center;"><?php if($call->call_status<3) {echo CalculateAgingDurationInDays($call->call_dt,$call->call_tm);}
			if($call->call_status==3){
				$average_closing =  CalculateAvgClosingTimeStamp($call->call_dt,$call->call_tm,$call->closure_dt,$call->closure_tm);
				echo CalculateSecondsToDays($average_closing);
			}
			?></td>
			<td style="text-align: center;"><span <?php echo $color;?>><?php echo $cstatus;?></span></td>
		</tr>
		<?php $i++; }	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
</table>
</div>
