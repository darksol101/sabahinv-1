<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class closedcallreports extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("closedcallreports",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('mdl_closedcallreports');	
		$this->load->model('servicecenters/mdl_servicecenters');
	
	}
	function index()
	{
		$this->redir->set_last_index();		
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters'));
		
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($servicecenterOptions, $this->mdl_html->option( '', 'Select Store'));
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_select",array('class'=>'validate[required] text-input'));
		
		$data = array(
					'servicecenter_select'=>$servicecenter_select
					);
		$this->load->view('closedcall/index',$data);

	}
	function getclosedcallreportslist()
	{
	    $this->redir->set_last_index();
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters'));
	
		$ajaxaction=$this->input->post('ajaxaction');
		/*
		*Calculate Total number of calls closed
		*/
		$totalclosed = $this->mdl_closedcallreports->getTotalClosedCallsByDate();
		$closedcallreports['total_closed_calls'] = $totalclosed;
		
		/**
		* Calculate total average aging time of closed calls
		*/
		$averageclosing = $this->mdl_closedcallreports->getAverageClosingTimeByDate();
		$closedcallreports['average_closing'] = $averageclosing;
		
		/**
		* Calculate Total calls whose aging time is less than 2 days
		*/
		$closedcalls = $this->mdl_closedcallreports->getClosedCallsByTimeLess();
		$closedcallreports['closed_calls'] = $closedcalls;
		
		/**
		* Calculate total calls whose aging time is between >=2 and <7 days
		*/
		$closedcallsbetween1 = $this->mdl_closedcallreports->getClosedCallsByTimeBetween1();
		$closedcallreports['closed_calls_between1'] = $closedcallsbetween1;
		
		/*
		* Calculate total calls whose aging time is between >=7 and <=15 days
		**/
		$closedcallsbetween2 = $this->mdl_closedcallreports->getClosedCallsByTimeBetween2();
		$closedcallreports['closed_calls_between2'] = $closedcallsbetween2;
		
		/*
		* Calculate total calls whose aging time is greater than <15 days
		**/
		$closedcallsgreater = $this->mdl_closedcallreports->getClosedCallsByTimeGreater();
		$closedcallreports['closed_calls_greater'] = $closedcallsgreater;
		
		/**
		*Get name of servic center
		*/
		$result = $this->mdl_servicecenters->get_ScByid(intval($this->input->post('sc_id')));
		$closedcallreports['service_center_name'] = '';
		if($this->input->post('sc_id')==''){
			$closedcallreports['service_center_name'] = 'All Store';
		}
		if(count($result)>0){
			$closedcallreports['service_center_name'] = $result[0]->sc_name;
		}
		$closedcallreports['report_from_date'] = $this->input->post('from_date');
		$closedcallreports['report_to_date'] = $this->input->post('to_date');
		$data=array(
			'closedcallreports'=>$closedcallreports,
			"ajaxaction"=>$this->input->post('ajaxaction')
		);
		$this->load->view('closedcall/closedcall_list', $data);
	}
	function generateexlreport()
	{
		$data = '<table width="50%">'.$this->input->get('dt').'</table>';
		$this->load->helper('download');
		$name = 'closed_call_report_'.date("Y_m_d_H_i_s").'.xls';
		/*
		**keep track of downloads
		*/
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'closed_callreports',
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
	function sendclosedcallreport(){
		if($this->input->post('sendmail')){
			$this->load->library('parser');
			$this->load->helper('mailer/phpmailer');
			$this->load->helper('text');
			$this->load->model(array('users/mdl_users'));
			$from = 'gchaudhari@nepotech.com';
			$email_to = $this->input->post('email_to');
			$arr = explode(",",$email_to);
			$data = array(
						  'service_center_name'=>$this->input->post('service_center_name'),
						  'report_from_date'=>$this->input->post('from_date'),
						  'report_to_date'=>$this->input->post('to_date'),
						  'total_closed_calls'=>$this->input->post('total_closed_calls'),
						  'average_closing'=>$this->input->post('average_closing'),
						  'closed_calls'=>$this->input->post('closed_calls'),
						  'closed_calls_between1'=>$this->input->post('closed_calls_between1'),
						  'closed_calls_between2'=>$this->input->post('closed_calls_between2'),
						  'closed_calls_greater'=>$this->input->post('closed_calls_greater')
						  );
			$login_username = $this->mdl_users->getUsername($this->session->userdata('user_id'));
			$body = $this->parser->parse('closedcall/report_email',$data,TRUE);
			$template_data = array(
								    'report_name'=>$this->lang->line('closedcallreports'),
					  				'login_username'=>$login_username,
								   	'message'=>$body
								   );
			$messsage = $this->parser->parse('email_templates/closedcall_email_template',$template_data,TRUE);
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