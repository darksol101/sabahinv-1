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
		$this->load->model(array('products/mdl_products','productmodel/mdl_productmodel','brands/mdl_brands','utilities/mdl_html','servicecenters/mdl_servicecenters'));

		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		$arr = array();
		$arr[] = '0';
		foreach($servicecenterOptions as $v){
			$arr[] = $v;
		}
		array_unshift($servicecenterOptions, $this->mdl_html->option( '0', $this->lang->line('service_center_not_allocated')));
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_select",array('class'=>'validate[required] text-input','multiple'=>'multiple'),'value','text',$arr);
		
		$brandOptions = $this->mdl_brands->getBrandOptions();
		
		$b =array();
		foreach($brandOptions as $row){
			$b[] = 	$row->value;
		}
		$brand_select = $this->mdl_html->genericlist($brandOptions,'brand_id',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$b);
		//select box for products
		$productOptions = $this->mdl_products->getProductOptionsByBrands(implode(",",$b));
		
		$p = array();
		foreach ($productOptions as $row1){
			$p[] = $row1->value;
		}
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$p);
		
		$data = array(
					'servicecenter_select'=>$servicecenter_select,
					'brand_select'=>$brand_select,
					'product_select'=>$product_select
		);
		$this->load->view('dsr/index',$data);

	}
	function getreportslist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('products/mdl_products','productmodel/mdl_productmodel','servicecenters/mdl_servicecenters','utilities/mdl_html','callcenter/mdl_callcenter'));

		//get listing of Stores
		$reports= $this->mdl_dailyservicereport->getreportsingle();
		
		$data=array(
			
			'reports'=>$reports
		);
		$this->load->view('dsr/dsrlist', $data);
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

		$data = $this->parser->parse('dsr/dsr_email',$data,TRUE);
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
}
?>