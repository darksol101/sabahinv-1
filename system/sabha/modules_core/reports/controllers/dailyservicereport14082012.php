<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Dailyservicereport extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("dsrreports",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('reports/mdl_dailyservicereport');	
		$this->load->model('servicecenters/mdl_servicecenters');
	}
	function index()
	{
		$this->redir->set_last_index();		
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters'));
		
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($servicecenterOptions, $this->mdl_html->option( '', 'Select All Store'));
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_select",array('class'=>'validate[required] text-input'));
		
		$data = array(
					'servicecenter_select'=>$servicecenter_select
					);
		$this->load->view('dsr/index',$data);

	}
	function getreportslist()
	{
	    $this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','utilities/mdl_html'));
		
		//Count total number of calls registered on this date
		$total = $this->mdl_dailyservicereport->getTotalRegisteredCalls();
		$reports['total_call_registered'] = $total;
		
		//Count total number of total pending call up to this date
		$pending = $this->mdl_dailyservicereport->getTotalPendingCallsByDate();
		$reports['total_pending_calls'] = $pending['total'];
		$reports['average_aging_current'] = $pending['avg_aging'];
		
		//Count total number of open calls
		$totalopen = $this->mdl_dailyservicereport->getTotalOpenCallsByDate();
		$reports['total_open_calls'] = $totalopen;
		
		//Count total number of partpending calls upto this date
		$partpending = $this->mdl_dailyservicereport->getTotalPartPendingCallsByDate();
		$reports['total_part_pending'] = $partpending['total'];
		$reports['average_aging_part'] = $partpending['avg_aging'];
		
		//Total number of cancelled calls for this date
		$totalcancelled = $this->mdl_dailyservicereport->getTotalCancelledCallsByDate();
		$reports['total_cancelled'] = $totalcancelled;
		
		//Total number of closed calls for this date
		//$totalclosed = $this->mdl_dailyservicereport->getTotalClosedCallsByDate();
		//$reports['total_closed_calls'] = $totalclosed['total'];		
		
		//Get name of Store
		$result = $this->mdl_servicecenters->get_ScByid(intval($this->input->post('sc_id')));
		$reports['service_center_name'] = '';
		if($this->input->post('sc_id')==''){
			$reports['service_center_name'] = 'All Store';
		}
		if(count($result)>0){
			$reports['service_center_name'] = $result[0]->sc_name;
		}
		$data=array(
			'reports'=>$reports,
		);
		$this->load->view('dsr/dsrlist', $data);
	}
	function generateexlreport()
	{
		$data = '<table width="50%" cellpadding="0" cellspacing="0" border="0">'.$this->input->get('dt').'</table>';
		$this->load->helper('download');
		$name = 'daily_service_report_'.date("Y_m_d_H_i_s").'.xls';
		/*
		**keep track of downloads
		*/
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'dailyservicereport',
						  'user_agent'	=> $this->agent->agent_string(),
						  'user_ip'=>$this->input->ip_address(),
						  'download_by'=>$this->session->userdata('user_id'),
						  'download_ts'=>date('Y-m-d H:i:s')
						  );
		$this->mdl_downloads->save($download_data);
		/*
		**ends here
		*/
		force_download($name, $data);
	}
	function getemailform()
	{
		$this->redir->set_last_index();
		$this->load->model(array('reports/mdl_tags'));
		$email_tags = $this->mdl_tags->getTags();
		$data = array(
					  'email_tags'=>$email_tags
					  );
		$this->load->view('dsr/emailform',$data);
	}
	function senddailyreport(){
		if($this->input->post('sendmail')){
			$this->load->library('parser');
			$this->load->helper('mailer/phpmailer');
			$this->load->helper('text');
			$this->load->model(array('users/mdl_users'));
			$from = 'gchaudhari@nepotech.com';
			$email_to = $this->input->post('email_to');
			$arr = explode(",",$email_to);
			//$to =$this->input->post('email_to');
			$to = 'gchaudhari@nepotech.com';
			$data = array(
						  'service_center_name'=>$this->input->post('service_center_name'),
						  'report_dt'=>$this->input->post('report_dt'),
						  'total_call_registered'=>$this->input->post('total_call_registered'),
						  'total_open_calls'=>$this->input->post('total_open_calls'),
						  'total_pending_calls'=>$this->input->post('total_pending_calls'),
						  'total_part_pending'=>$this->input->post('total_part_pending'),
						  'total_cancelled'=>$this->input->post('total_cancelled'),
						  'total_closed_calls'=>$this->input->post('total_closed_calls'),
						  'average_aging_current'=>$this->input->post('average_aging_current'),
						  'average_aging_part'=>$this->input->post('average_aging_part')
						  );
			$body = $this->parser->parse('dsr/dsr_email',$data,TRUE);
			$login_username = $this->mdl_users->getUsername($this->session->userdata('user_id'));
			$template_data = array(
								    'report_name'=>$this->lang->line('dsr_reports'),
					  				'login_username'=>$login_username,
								   	'message'=>$body
								   );
			$messsage = $this->parser->parse('email_templates/dsr_email_template',$template_data,TRUE);
			$this->load->library('email');
			$this->email->from($this->email->smtp_user, $this->lang->line('smtp_user'));
			//$this->email->to($to);
			$this->email->subject($this->lang->line('email_subject'));
			$this->email->message($messsage);
			$i= 0;
			$j=0;
			$this->load->model(array('reports/mdl_tags'));
			foreach($arr as $to){
				$this->email->to($to);
				if($this->email->send()){
					$this->mdl_tags->save(array('tag_text'=>$to,'tag_created_by'=>$this->session->userdata('user_id')));
					$i++;
				}else{
					$j++;
				}
			}
			$msg = '';
			//$msg = ($i==count($arr))?"":" but email could not be sent to some email-address";
			$error = array('type'=>'success','message'=>$this->lang->line('email_has_been_sent_successfully').$msg);
			$this->load->view('dashboard/ajax_messages',$error);
		}
	}
}
?>