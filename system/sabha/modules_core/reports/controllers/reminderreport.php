<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reminderreport extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("reminderreport",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('mdl_reminderreport','servicecenters/mdl_servicecenters');		
	}
	
	function index()
	{
		$this->redir->set_last_index();		
		$this->load->model(array('utilities/mdl_html','reminders/mdl_reminders','callcenter/mdl_callcenter','servicecenters/mdl_servicecenters','brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel'));
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		$arr = array();
		$arr[] = '0';
		foreach($servicecenterOptions as $v){
			$arr[] = $v;
		}
		//select box for Store
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_id",array('class'=>'validate[required] text-input','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$arr);
		//select box for brand
		$brandOptions = $this->mdl_brands->getBrandOptions();
		//array_unshift($brandOptions, $this->mdl_html->option( '0', 'Select Brand'));
		$brand_select = $this->mdl_html->genericlist($brandOptions,'brand_id',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		//brand select box ends here
		
		//select box for products
		$productOptions = $this->mdl_products->getProductOptions(0);
		//array_unshift($productOptions, $this->mdl_html->option( '0', 'Select Product'));
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		
		$data = array(
					'servicecenter_select'=>$servicecenter_select,
					//'callid'=>$callid,
					'brand_select'=>$brand_select,
					'product_select'=>$product_select
					);
		$this->load->view('reminderreport/index',$data);

	}
	function getproductsbybrands()
	{
		$this->load->model(array('brands/mdl_brands','products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$productOptions = $this->mdl_products->getProductOptionsByBrands($this->input->post('brand_ids'));
		//array_unshift($productOptions,$this->mdl_html->option( '0', 'Select Product'));
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		echo $product_select;
		die();
	}
	
	
	function getreminderlist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('reports/mdl_reminderreport','productmodel/mdl_productmodel','reminders/mdl_reminders','callcenter/mdl_callcenter','servicecenters/mdl_servicecenters','products/mdl_products','productmodel/mdl_productmodel','utilities/mdl_html'));
		
		$reports = $this->mdl_reminderreport->getReminderReport();
		
		$data=array(
			'reports'=>$reports['list'],
		);
	
		$this->load->view('reminderreport/reminderreport_list', $data);
		
	}
	function generateexlreport()
	{
		$this->load->library('parser');
		$json = $this->input->post('json');
		$report_dt = $this->input->post('report_dt');
		$data = array(
					  'json'=>$json,
					  'report_dt'=>$report_dt
					  );
		
		$data = $this->parser->parse('reminderreport/reminderreport_email',$data,TRUE);
		//$data = '<table width="50%" cellpadding="0" cellspacing="0" border="0">'.$this->input->get('dt').'</table>';
		$this->load->helper('download');
		$name = 'reminder_report'.date("Y_m_d_H_i_s").'.xls';
		/*
		**keep track of downloads
		*/
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'reminderreport',
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
		$this->load->view('reminderreport/emailform',$data);
	}
	function sendreminderreport(){
		if($this->input->post('sendmail')){
			$this->load->library('parser');
			$this->load->helper('mailer/phpmailer');
			$this->load->helper('text');
			$this->load->model(array('users/mdl_users'));
			//$from = 'gchaudhari@nepotech.com';
			$email_to = $this->input->post('email_to');
			
			$arr = explode(",",$email_to);
			$to ='';
			$data = array(
						  'json'=>$this->input->post('json')
						  );
			$body = $this->parser->parse('reminderreport/reminderreport_email',$data,TRUE);
			$login_username = $this->mdl_users->getUsername($this->session->userdata('user_id'));
			$template_data = array(
								    'report_name'=>$this->lang->line('reminder_report'),
					  				'login_username'=>$login_username,
								   	'message'=>$body
								   );
			$messsage = $this->parser->parse('email_templates/reminderreport_email_template',$template_data,TRUE);
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
}
?>