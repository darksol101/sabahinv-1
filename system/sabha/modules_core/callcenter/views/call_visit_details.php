<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php if($call_details->call_id>0){?>
<?php
$timeOptions =array();
for($i=1;$i<=24;$i++){
	$timeOptions[$i]['value'] = $i;
	$timeOptions[$i]['text'] = $i;
}
$hr_list = $this->mdl_html->genericlist($timeOptions,'call_visit_hr',array('style'=>'width:50px;'),'value','text','');
$call_details->call_visit_dt = ($call_details->call_visit_dt=='0000-00-00')?'':$call_details->call_visit_dt;
?>
    	<fieldset>
		 <legend><?php echo $this->lang->line('call_visit_details'); ?></legend>
        
		<div class="form_row">
			<div class="col">
				<div id="field_visitdate">
                    <?php echo $this->lang->line('call_visit_dt');?><br />
                    <input type="text" name="call_visit_dt" id="call_visit_dt" value="<?php echo format_date(strtotime($call_details->call_visit_dt));?>" class="text-input datepicker" readonly="readonly" />
			</div>		
			</div>		
			<div class="col">
				<div id="field_visittime">				
                    <?php echo $this->lang->line('call_visit_tm_in');?><br />
                    <input type="text" name="call_visit_tm_in" id="call_visit_tm_in" readonly="readonly" class="timepicker text-input" value="<?php echo date("H:i",strtotime($call_details->call_visit_tm_in));?>" />
				</div>	
			</div>	
			
					<div class="col">
						<div id="field_visittimeout">
							<?php echo $this->lang->line('call_visit_tm_out');?><br />
						
							<input type="text" name="call_visit_tm_out" readonly="readonly" id="call_visit_tm_out" class="timepicker text-input" value="<?php echo date("H:i",strtotime($call_details->call_visit_tm_out));?>" />
						</div>
					</div>
			</div>
        </fieldset>
<?php }?>