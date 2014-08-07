<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Cron extends Client_Center_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("dsrreports",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->helper('date');
	}
	function index(){

	}
	
	function dsremailreport()
	{
		$total_registered_calls = 0;
		$total_part_pending_calls = 0;
		$total_closed_calls = 0;
		$total_cancelled_calls = 0;
		$total_calls_pending = 0;
		$total_other_pending_calls = 0;
		$total_not_assigned_calls = 0;
		$total_calls_not_assigned = 0;
		$total_pending_less_than12hrs_calls = 0;
		$total_pending_between_12and24hrs_calls = 0;
		$total_pending_between_1and2_calls = 0;
		$total_pending_between_2and7_calls = 0;
		$total_pending_between_7and15_calls = 0;
		$total_pending_between_15and30_calls = 0;
		$total_pending_greater_than30_calls = 0;
		$this->load->model(array('users/mdl_users','cron/mdl_dsrdata','servicecenters/mdl_servicecenters','cron/mdl_cronedailyservicereport'));
		$service_centers=$this->mdl_servicecenters->getServiceCentersOptions();
		$arr->value = 0;
		$arr->text = $this->lang->line('service_center_not_allocated');
		array_unshift($service_centers, $arr);
	
		$json = array();
		$i=0;
		$format = 'DATE_COOKIE';
		$time = time();
		$report_dt = standard_date($format, $time); 

		foreach($service_centers as $service_center){

			$sc_id=$service_center->value;
			$sc_name=$service_center->text;
			$service_center->total_call_registered = $this->mdl_cronedailyservicereport->getTotalRegisteredCalls($service_center->value);
			$total_registered_calls+=$service_center->total_call_registered;
	
			//get total part pending calls
			$service_center->total_part_pending = $this->mdl_cronedailyservicereport->getTotalPartpendingCallsByDate($service_center->value);
			$total_part_pending_calls+=$service_center->total_part_pending;
	
			//get total closed calls
			$service_center->total_closed = $this->mdl_cronedailyservicereport->getTotalClosedCallsByDate($service_center->value);
			$total_closed_calls+=$service_center->total_closed;
	
			//get total cancelled calls
			$service_center->total_cancelled = $this->mdl_cronedailyservicereport->getTotalCancelledCallsByDate($service_center->value);
			$total_cancelled_calls+=$service_center->total_cancelled;
	
			//get total pending calls
			$service_center->total_pending_calls =  $this->mdl_cronedailyservicereport->getTotalPendingCallsByDate($service_center->value);
			$total_calls_pending+=$service_center->total_pending_calls;
	
			//get total other pending calls
			$service_center->total_other_pending = $this->mdl_cronedailyservicereport->getTotalOtherpendingCallsByDate($service_center->value);
			$total_other_pending_calls+=$service_center->total_other_pending;
			//get total not assigned calls
			$service_center->total_not_assigned = $this->mdl_cronedailyservicereport->getTotalCallsNotAssignedByDate($service_center->value);
			$total_not_assigned_calls+=$service_center->total_not_assigned;
	
			//get pending calls less than 12 hours
			$service_center->total_pending_calls_less_than12hrs = $this->mdl_cronedailyservicereport->getTotalPendingCallsLess12Hrs($service_center->value);
			$total_pending_less_than12hrs_calls+=$service_center->total_pending_calls_less_than12hrs;
	
			//get pending calls between 12 and 24 hrs
			$service_center->total_pending_calls_between_12and24hrs = $this->mdl_cronedailyservicereport->getTotalPendingCallsBetween12and24hrs($service_center->value);
			$total_pending_between_12and24hrs_calls+=$service_center->total_pending_calls_between_12and24hrs;
	
			//get pendign calls between 1 and 2 days
			$service_center->total_pending_calls_between_1and2 = $this->mdl_cronedailyservicereport->getTotalPendingCallsBetween1and2($service_center->value);
			$total_pending_between_1and2_calls+=$service_center->total_pending_calls_between_1and2;
			//get pending calls between 2 and 7 days
			$service_center->total_pending_between_2and7 = $this->mdl_cronedailyservicereport->getTotalPendingCallsBetween2and7($service_center->value);
			$total_pending_between_2and7_calls+=$service_center->total_pending_between_2and7;
	
			//get pending calls between 7 and 15 days
			$service_center->total_pending_between_7and15 = $this->mdl_cronedailyservicereport->getTotalPendingCallsBetween7and15($service_center->value);
			$total_pending_between_7and15_calls+=$service_center->total_pending_between_7and15;
	
			//get pending calls between 15 and 30 days
			$service_center->total_pending_between_15and30 = $this->mdl_cronedailyservicereport->getTotalPendingCallsBetween15and30($service_center->value);
			$total_pending_between_15and30_calls+=$service_center->total_pending_between_15and30;
	
			//get pending calls greater than 30 days
			$service_center->total_pending_greater_than30 = $this->mdl_cronedailyservicereport->getTotalPendingCallsGreaterthan30($service_center->value);
			$total_pending_greater_than30_calls+=$service_center->total_pending_greater_than30;	
			$data_dsr = array(
								  	'sc_id'=>$sc_id,
									'sc_name'=>$sc_name,
									'report_dt'=>$report_dt,
									'total_call_registered'=>$service_center->total_call_registered,
									'total_part_pending'=>$service_center->total_part_pending,
									'total_closed'=>$service_center->total_closed,
									'total_cancelled'=>$service_center->total_cancelled,
									'total_pending_calls'=>$service_center->total_pending_calls,
									'total_other_pending'=>$service_center->total_other_pending,
									'total_not_assigned'=>$service_center->total_not_assigned,
									'total_pending_calls_less_than12hrs'=>$service_center->total_pending_calls_less_than12hrs,
									'total_pending_calls_between_12and24hrs'=>$service_center->total_pending_calls_between_12and24hrs,
									'total_pending_calls_between_1and2'=>$service_center->total_pending_calls_between_1and2,
									'total_pending_between_2and7'=>$service_center->total_pending_between_2and7,
									'total_pending_between_7and15'=>$service_center->total_pending_between_7and15,
									'total_pending_between_15and30'=>$service_center->total_pending_between_15and30,
									'total_pending_greater_than30'=>$service_center->total_pending_greater_than30
							);
			$this->load->model(array('cron/mdl_dsrdata'));
			$this->mdl_dsrdata->save($data_dsr);
			
			//Total for each Store
			
			$json[$i][] = $service_center->text;
			$json[$i][] = $service_center->total_call_registered;
			$json[$i][] = $service_center->total_closed;
			$json[$i][] = $service_center->total_cancelled;
			$json[$i][] = $service_center->total_pending_calls;
			$json[$i][] = $service_center->total_part_pending;
			$json[$i][] = $service_center->total_other_pending;
			$json[$i][] = $service_center->total_not_assigned;
			$json[$i][] = $service_center->total_pending_calls_less_than12hrs;
			$json[$i][] = $service_center->total_pending_calls_between_12and24hrs;
			$json[$i][] = $service_center->total_pending_calls_between_1and2;
			$json[$i][] = $service_center->total_pending_between_2and7;
			$json[$i][] = $service_center->total_pending_between_7and15;
			$json[$i][] = $service_center->total_pending_between_15and30;
			$json[$i][] = $service_center->total_pending_greater_than30;
			$i++;			
		}
		//For grand total at footer

			$json[$i][] = $this->lang->line('total');
			$json[$i][] = $total_registered_calls;
			$json[$i][] = $total_closed_calls;
			$json[$i][] = $total_cancelled_calls;
			$json[$i][] = $total_calls_pending;
			$json[$i][] = $total_part_pending_calls;
			$json[$i][] = $total_other_pending_calls;
			$json[$i][] = $total_not_assigned_calls;
			$json[$i][] = $total_pending_less_than12hrs_calls;
			$json[$i][] = $total_pending_between_12and24hrs_calls;
			$json[$i][] = $total_pending_between_1and2_calls;
			$json[$i][] = $total_pending_between_2and7_calls;
			$json[$i][] = $total_pending_between_7and15_calls;
			$json[$i][] = $total_pending_between_15and30_calls;
			$json[$i][] = $total_pending_greater_than30_calls;
			$i++;
					
		//for sending mail
		
		$this->load->library('parser');
		$this->load->helper('mailer/phpmailer');
		$this->load->helper('text');
		$this->load->model(array('users/mdl_users','servicecenters/mdl_servicecenters'));
		$data = array(
				  'json'=>$json,
				  'report_dt'=>$report_dt
		);
		$body = $this->parser->parse('cron/dsr_email',$data,TRUE);
		$template_data = array(
							    'report_name'=>$this->lang->line('dsr_reports'),
							   	'message'=>$body
		);
		$messsage = $this->parser->parse('email_templates/email_first_dsr_template',$template_data,TRUE);
		$this->load->library('email');
		$this->email->from($this->email->smtp_user, $this->lang->line('smtp_user'));
		$this->email->subject($this->lang->line('email_subject'));
		$this->email->message($messsage);
		$i= 0;
		$j=0;
		$arr =array('gchaudhari@nepotech.com','subash@nepotech.com');
		$this->load->model(array('cron/mdl_email_log'));
		$this->load->library('user_agent');
		foreach($arr as $to){
			$this->email->to($to);
			if($this->email->send()){
				$email_data = array(
									'email_receipient'=>$to,
									'email_sender_session'=>$this->session->userdata('session_id'),
									'email_report_type'=>'dailyservicereport',
									'email_sent_by'=>$this->session->userdata('user_id'),
									'user_agent'	=> $this->agent->agent_string(),
									'user_ip'=>$this->input->ip_address(),
									'email_sent_ts'=>date('Y-m-d H:i:s')
				);
				$this->mdl_email_log->save($email_data);
				$i++;
			}else{
				$j++;
			}
		}
		$msg = '';
		//$msg = ($i==count($arr))?"":" but email could not be sent to some email-address";
		$error = array('type'=>'success','message'=>$this->lang->line('email_has_been_sent_successfully').$msg);
	}
}
?>