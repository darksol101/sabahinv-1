<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Engineerreport extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("engineerreport",  $this->mdl_mcb_data->setting('default_language'));
		
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','engineers/mdl_engineers','brands/mdl_brands','utilities/mdl_html','servicecenters/mdl_servicecenters'));

		
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		
		$b =array();
		foreach($servicecenterOptions as $row){
			$b[] = 	$row->value;
		}
		$servicecenter_select = $this->mdl_html->genericlist($servicecenterOptions,'brand_id',array('onchange'=>'getengineer($(this).val());','class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$b);

		
		
		
		$engineerOptions = $this->mdl_engineers->getEngineerOptions();
		
		$b =array();
		foreach($engineerOptions as $row){
			$b[] = 	$row->value;
		}
		$engineer_select = $this->mdl_html->genericlist($engineerOptions,'engineer_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$b);
		//select box for products
	
		
		$data = array(
					'servicecenter_select'=>$servicecenter_select,
					'engineer_select'=>$engineer_select,
		);
		$this->load->view('engineerreport/index',$data);

	}
	function getreportslist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('products/mdl_products','callcenter/mdl_callcenter','servicecenters/mdl_servicecenters','engineers/mdl_engineers','utilities/mdl_html'));

		$engineer_id = $this->input->post('engineer_id');
		$engineer_id = explode(',',$engineer_id);
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		
		$data=array(
			'engineer_id'=>$engineer_id,
			'fromdate'=>$fromdate,
			'todate'=> $todate
		);
		$this->load->view('engineerreport/erreportlist', $data);
	}
	
	
	function generateexlreport(){
	ini_set("memory_limit","256M");
		$this->load->model(array('stocks/mdl_parts_stocks','servicecenters/mdl_servicecenters','company/mdl_company'));
		$this->load->plugin('to_excel');
		$this->load->helper('calls');
		//$list = $this->mdl_parts_stocks->getStocksdownload();
		
		
		$data = '';
		if($list->num_rows()==0){
			redirect('stocks');
		}
		foreach($list->result() as $row){
			$row->available_quantity = $row->stock_quantity + $row->allocated_quantity;
			$data[]=$row;
			
		}
		
		$fields = array('Store','Item Number','Company','Item description','Available Quantity','Parts in transit','Allocated_quantity','Total Quantity');
		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		$name = 'parts_'.date("Y_m_d_H_i_s").'.xls';
		/*
		 **keep track of downloads
		 */
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'calls',
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
	function senddailyreport(){
		if($this->input->post('sendmail')){
			$this->load->library('parser');
			$this->load->helper('mailer/phpmailer');
			$this->load->helper('text');
			$this->load->model(array('users/mdl_users'));
			$from = 'gchaudhari@nepotech.com';
			$email_to = $this->input->post('email_to');
				
			$arr = explode(",",$email_to);
			$to ='';
			$data = array(
						  'report_dt'=>$this->input->post('report_dt'),
						  'json'=>$this->input->post('json')
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
			$this->load->model(array('reports/mdl_email_log'));
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
			$this->load->view('dashboard/ajax_messages',$error);
		}
	}
	
	
	
	
	function claimdetail(){
		$this->redir->set_last_index();
		$this->load->model(array('callcenter/mdl_callcenter','productmodel/mdl_productmodel','brands/mdl_brands','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','customers/mdl_customers','servicecenters/mdl_servicecenters'));
		
		$verifieddetails = $this->mdl_callcenter->verifieddetail();
		$data = array(
					  'verifieddetails'=>$verifieddetails
					  );
		
		$this->load->view('reports/engineerreport/claimdetail',$data);
		
		}
		
		
	function getengineerbysc(){
		$this->redir->set_last_index();
		$this->load->model(array('engineers/mdl_engineers','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$sc_id = $this->input->post('sc_id');
		
		$engineer_selects = $this->mdl_engineers->getengineersreport($sc_id);
		$engineer_select = $this->mdl_html->genericlist($engineer_selects,'product_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		echo $engineer_select;
		die();
		
		}
		
		function excelprint()
		{
			
			$this->load->view('reports/engineerreport/excelprint');
			}
	
}
?>