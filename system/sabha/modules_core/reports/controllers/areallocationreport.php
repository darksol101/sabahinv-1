<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Areallocationreport extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("areallocation",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters'));

		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		$arr = array();
		$arr[] = '0';
		foreach($servicecenterOptions as $v){
			$arr[] = $v;
		}
		//array_unshift($servicecenterOptions, $this->mdl_html->option( '', 'Select All Store'));
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_select",array('class'=>'validate[required] text-input','multiple'=>'multiple'),'value','text',$arr);

		$data = array(
					'servicecenter_select'=>$servicecenter_select
		);
		$this->load->view('areallocation/index',$data);
	}

	function getreportslist()
	{
		ini_set("memory_limit","256M");
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','zones/mdl_districts','zones/mdl_zones','cities/mdl_cities','mdl_areallocationreport','mdl_productallocationreport','products/mdl_products','brands/mdl_brands','utilities/mdl_html'));
		$service_centers = $this->mdl_servicecenters->getServiceCentersOptionsBySc($this->input->post('sc_id'));
		//post the selected sc_id to the model for querying
		$reports = $this->mdl_areallocationreport->autocallassignreport($this->input->post('sc_id'));
		$data=array(
			'reports'=>$reports,
			'service_centers'=>$service_centers
		);
		$this->load->view('areallocation/aarlist', $data);
	}
	function generateexlreport()
	{
		$this->load->library('parser');
		$json = $this->input->post('json');
		$data = array(
						  'sc_name'=>$this->input->get('sc_name'),
						  'zone_name'=>$this->input->get('zone_name'),
						  'district_name'=>$this->input->get('district_name'),
						  'city_name'=>$this->input->get('city_name'),
						  'json'=>$json
		);

		$data = $this->parser->parse('areallocation/areallocation_email',$data,TRUE);
		//$data = '<table width="50%" cellpadding="0" cellspacing="0" border="0">'.$this->input->get('dt').'</table>';
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
		$this->load->model(array('reports/mdl_email_log'));
		$email_tags = $this->mdl_email_log->getTags();
		$data = array(
					  'email_tags'=>$email_tags
		);
		$this->load->view('dsr/emailform',$data);
	}
	function sendallocationreport(){
		if($this->input->post('sendmail')){
			$this->load->library('parser');
			$this->load->helper('mailer/phpmailer');
			$this->load->helper('text');
			$this->load->model(array('users/mdl_users'));
			$email_to = $this->input->post('email_to');
			$arr = explode(",",$email_to);
			$json = $this->input->post('json');
			//$to =$this->input->post('email_to');
			$to = '';
			$data = array(
						  'json'=>$json
			);
				
			$body = $this->parser->parse('areallocation/areallocation_email',$data,TRUE);
			$login_username = $this->mdl_users->getUsername($this->session->userdata('user_id'));
			$template_data = array(
								    'report_name'=>$this->lang->line('area_allocation_report'),
					  				'login_username'=>$login_username,
								   	'message'=>$body
			);
			$messsage = $this->parser->parse('email_templates/areassign_email_template',$template_data,TRUE);
			$this->load->library('email');
			$this->email->from($this->email->smtp_user, $this->lang->line('smtp_user'));
			//$this->email->to($to);
			$this->email->subject($this->lang->line('email_subject'));
			$this->email->message($messsage);
			$i= 0;
			$j=0;
			$this->load->model(array('reports/mdl_email_log'));
			$this->load->library('user_agent');
			foreach($arr as $to){
				$this->email->to($to);
				if($this->email->send()){
					$email_data = array(
										'email_receipient'=>$to,
										'email_sender_session'=>$this->session->userdata('session_id'),
										'email_report_type'=>'areallocationreport',
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
			$this->load->view('dashboard/ajax_messages',$error);
		}
	}}
	?>