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
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters','brands/mdl_brands','products/mdl_products'));
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		$arr = array();
		$arr[] = '0';
		foreach($servicecenterOptions as $v){
			$arr[] = $v;
		}
		//array_unshift($servicecenterOptions, $this->mdl_html->option( '0', 'Select Store'));
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_id",array('class'=>'validate[required] text-input','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$arr);
		$calltypeOptions = $this->mdl_mcb_data->getStatusOptions('calltype');
		
		//unset($calltypeOptions[0]); 
		$calltypeOptions[0]->text = 'None';
		$arr_ctype = array();
		foreach($calltypeOptions as $c){
			$arr_ctype[] = $c->value;
		}
		$calltype_select  =  $this->mdl_html->genericlist($calltypeOptions,'call_type',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$arr_ctype);
		
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
					'calltype_select'=>$calltype_select,
					'brand_select'=>$brand_select,
					'product_select'=>$product_select
					);
		$this->load->view('closedcall/index',$data);

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
	function getclosedcallreportslist()
	{
	    $this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','products/mdl_products','productmodel/mdl_productmodel','utilities/mdl_html'));
		//get listing of Stores
		$service_centers = $this->mdl_servicecenters->getServiceCentersOptionsBySc($this->input->post('sc_id'));
		$arr = explode(",",$this->input->post('sc_id'));
		if(in_array("0",$arr)){
			array_unshift($service_centers, $this->mdl_html->option( '0', $this->lang->line('service_center_not_allocated')));
		}
		$report_from_date = $this->input->post('from_date');
		$report_to_date = $this->input->post('to_date');
		$data=array(
			'report_from_date'=>$report_from_date,
			'report_to_date'=>$report_to_date,
			'service_centers'=>$service_centers
		);
		$this->load->view('closedcall/closedcall_list', $data);
	}
	function generateexlreport()
	{
		$data = '<table width="50%">'.$this->input->post('content').'</table>';
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
			$email_to = $this->input->post('email_to');
			$arr = explode(",",$email_to);
			$data = array(
						  'report_from_date'=>$this->input->post('from_date'),
						  'report_to_date'=>$this->input->post('to_date'),
						  'json'=>$this->input->post('json')
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
			$this->load->model(array('reports/mdl_email_log'));
			$this->load->library('user_agent');
			foreach($arr as $to){
				$this->email->to($to);
				if($this->email->send()){
					$email_data = array(
										'email_receipient'=>$to,
										'email_sender_session'=>$this->session->userdata('session_id'),
										'email_report_type'=>'tatreport',
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