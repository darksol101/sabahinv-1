<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Callreport extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("callreport",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel','brands/mdl_brands','cities/mdl_cities','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','mcb_data/mdl_mcb_data','customers/mdl_customers','servicecenters/mdl_servicecenters','utilities/mdl_html'));
		$this->load->model('engineers/mdl_engineers');
		$engineerOptions = $this->mdl_engineers->getEngineerOptions();
		array_unshift($engineerOptions, $this->mdl_html->option( '0', '< Not Assigned >'));
		array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
		$engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer',array('class'=>''),'value','text',$this->input->get('eg'));
		$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($scentersOptions,$this->mdl_html->option( '0', '< Not Assigned >'));
		array_unshift($scentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$scenters=$this->mdl_html->genericlist( $scentersOptions, "scenter",array('class'=>'validate[required] text-input'),'value','text',$this->input->get('sc'));
		
		$brandOptions = $this->mdl_brands->getBrandOptions();
	
		$brand_select = $this->mdl_html->genericlist($brandOptions,'brand_id',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);

		//select box for products
		$productOptions = $this->mdl_products->getProductOptions(0);
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id[]',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		
		$data = array(
					  'engineer_select'=>$engineer_select,
					  'scenters'=>$scenters,
					  'brand_select'=>$brand_select,
					  'product_select'=>$product_select
		);
		$this->load->view('reports/callreport/index',$data);
	}
	function generatecallreport(){
		ini_set("memory_limit","256M");
		$this->load->helper('url');
		$this->load->helper('calls');
		$this->load->model(array('mdl_callreports','productmodel/mdl_productmodel','brands/mdl_brands','cities/mdl_cities','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','customers/mdl_customers','servicecenters/mdl_servicecenters'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->get('currentpage');
		$calls = $this->mdl_callreports->getCallReports($page);
		$config['total_rows'] = $calls['total'];
		$this->ajaxpagination->cur_page=$this->input->get('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
					  'calls'=>$calls['list'],
					  'page'=>$page,
					  'config'=>$config,
					  'navigation'=>$navigation
		);
		$this->load->view('callreport/listcallreport',$data);
	}
	function getbrands(){
		$this->load->model(array('products/mdl_products','brands/mdl_brands','utilities/mdl_html'));
		$brands = $this->mdl_brands->getBrandOptions();
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'brands'=>$brands,
					  'ajaxaction'=>$ajaxaction
		);
		$this->load->view('callreport/listbrands',$data);
	}
	function getproductsbybrands()
	{
		$this->load->model(array('brands/mdl_brands','products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$productOptions = $this->mdl_products->getProductOptionsByBrands($this->input->post('brand_ids'));
		//array_unshift($productOptions,$this->mdl_html->option( '0', 'Select Product'));
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id[]',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		echo $product_select;
		
	}
	
	function create_excel()
	{
		ini_set("memory_limit","256M");
		$this->load->model(array('users/mdl_users','reports/mdl_callreports','productmodel/mdl_productmodel','brands/mdl_brands','cities/mdl_cities','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','mcb_data/mdl_mcb_data','customers/mdl_customers'));
		$this->load->plugin('to_excel');
		$this->load->helper('calls');
		$list = $this->mdl_callreports->getCallReportExcel();		
		$data = '';
		/*echo '<pre>';
		print_r($list);
		die();*/
		$call_id =array();
		foreach($list['list'] as $row){
			$serials = array();
			$call_id[] = $row->call_id;
			if($row->call_id){
				$this->load->model('productmodel/mdl_product_serial_number');
				$serial_numbers = $this->mdl_product_serial_number->getProductSerialNumbersByCall($row->call_id);
				if(count($serial_numbers)>0){
					foreach($serial_numbers as $numbers){
						$serials[] = $numbers->call_serial_no;
					}
				}
			}
			unset($row->call_id);
			$call_status = $row->call_status ;
			$row->call_status = $this->mdl_mcb_data->getStatusDetails($row->call_status,'callstatus');
			if($row->call_status<3) {
				$row->call_aging = CalculateAgingDurationInDays($row->call_dt,$row->call_tm);}
				else{
					$row->call_aging='';
				}
				$row->call_dt = $row->call_dt." ".$row->call_tm;
				$row->pending_dt = strtotime($row->pending_dt)?$row->pending_dt." ".$row->pending_tm:'';
				$row->closure_dt = strtotime($row->closure_dt)?$row->closure_dt." ".$row->closure_tm:'';
				if($call_status=='4'){
					$row->closure_dt =$row->call_last_mod_ts ;
				}
				$row->call_created_by = ($row->call_created_by)?$this->mdl_users->getUserNameByUserId($row->call_created_by):'';
				$row->call_last_mod_by = ($row->call_last_mod_by)?$this->mdl_users->getUserNameByUserId($row->call_last_mod_by):'';
				$row->call_type = $this->mdl_mcb_data->getStatusDetails($row->call_type,'calltype');
				$row->call_old_serial_no = '';
				if(count($serials)>0){
					$row->call_old_serial_no = implode(",<br/>",array_unique($serials));
				}
				unset($row->call_tm);
				unset($row->pending_tm);
				unset($row->closure_tm);
				unset($row->call_last_mod_ts);
				unset($serials);
				$data[] = $row;
		}

		$fields = array('Call ID','Reg Date/Time','First Name','Last Name','City','Address','Office Phone','Home Phone','Mobile Number','Brand','Product','Model Number','Engineer','Aging Date/Time','Store','Complain Remark','Engineer Remark','Details of work done','Pending Reason','Status','Pending Date/Time','Closure Date/Time','Registered By','Call Last Modified By','Serial Number','Old Serial Number','Purchase Date','Service Type','Dealer Name');
		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		$name = 'calls_'.date("Y_m_d_H_i_s").'.xls';
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
		$this->load->view('callreport/emailform',$data);
	}
	
	function sendemail()
	{
		ini_set("memory_limit","256M");
		$this->load->model(array('users/mdl_users','reports/mdl_callreports','productmodel/mdl_productmodel','brands/mdl_brands','cities/mdl_cities','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','mcb_data/mdl_mcb_data','customers/mdl_customers'));
		$this->load->plugin('to_excel');
		$this->load->helper('calls');
		$listdata = $this->mdl_callreports->getCallReportMail();		
		$data = '';
		
		$call_id =array();
		foreach($listdata['listdata'] as $row){
			$serials = array();
			$call_id[] = $row->call_id;
			if($row->call_id){
				$this->load->model('productmodel/mdl_product_serial_number');
				$serial_numbers = $this->mdl_product_serial_number->getProductSerialNumbersByCall($row->call_id);
				if(count($serial_numbers)>0){
					foreach($serial_numbers as $numbers){
						$serials[] = $numbers->call_serial_no;
					}
				}
			}
			unset($row->call_id);
			$call_status = $row->call_status ;
			$row->call_status = $this->mdl_mcb_data->getStatusDetails($row->call_status,'callstatus');
			if($row->call_status<3) {
				$row->call_aging = CalculateAgingDurationInDays($row->call_dt,$row->call_tm);}
				else{
					$row->call_aging='';
				}
				$row->call_dt = $row->call_dt." ".$row->call_tm;
				$row->pending_dt = strtotime($row->pending_dt)?$row->pending_dt." ".$row->pending_tm:'';
				$row->closure_dt = strtotime($row->closure_dt)?$row->closure_dt." ".$row->closure_tm:'';
				if($call_status=='4'){
					$row->closure_dt =$row->call_last_mod_ts ;
				}
				$row->call_created_by = ($row->call_created_by)?$this->mdl_users->getUserNameByUserId($row->call_created_by):'';
				$row->call_last_mod_by = ($row->call_last_mod_by)?$this->mdl_users->getUserNameByUserId($row->call_last_mod_by):'';
				$row->call_type = $this->mdl_mcb_data->getStatusDetails($row->call_type,'calltype');
				$row->call_old_serial_no = '';
				if(count($serials)>0){
					$row->call_old_serial_no = implode(",<br/>",array_unique($serials));
				}
				unset($row->call_tm);
				unset($row->pending_tm);
				unset($row->closure_tm);
				unset($row->call_last_mod_ts);
				unset($serials);
				$data[] = $row;
		}

		$fields = array('Call ID','Reg Date/Time','First Name','Last Name','City','Address','Office Phone','Home Phone','Mobile Number','Brand','Product','Model Number','Engineer','Aging Date/Time','Store','Complain Remark','Engineer Remark','Details of work done','Pending Reason','Status','Pending Date/Time','Closure Date/Time','Registered By','Call Last Modified By','Serial Number','Old Serial Number','Purchase Date','Service Type','Dealer Name');
		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		$name = 'calls_'.date("Y_m_d_H_i_s").'.xls';
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
		//$ourFileName = 'calls_'.date("Y_m_d_H_i_s").'.xls';
		$stringData = "Bobby Bopper\n";

		$emailhandle = fopen('uploads/calls/'.$name, 'w');
		fwrite($emailhandle, $data);
		
		/*
		 **Send Email
		 */
		$this->load->library('parser');
		$this->load->helper('mailer/phpmailer');
		$this->load->helper('text');
		//$from = 'gchaudhari@nepotech.com';
		$email_to = $this->input->post('email_to');
		$arr = explode(",",$email_to);
		$login_username = $this->mdl_users->getUsername($this->session->userdata('user_id'));

		$data = array(
					  'report_name'=>$this->lang->line('report_name'),
					  'login_username'=>$login_username
		);
		$messsage =$this->parser->parse('email_templates/calls_email_template',$data,TRUE);
		$this->load->library('email');
		$this->email->attach('uploads/calls/'.$name);
		$this->email->from($this->email->smtp_user, $this->lang->line('smtp_user'));
		//$this->email->to($to);
		$this->email->subject($this->lang->line('email_subject'));
		$this->email->message($messsage);
		$i= 0;
		$j=0;
		$this->load->model(array('reports/mdl_tags'));
		$this->load->model(array('reports/mdl_email_log'));
		$this->load->library('user_agent');
		foreach($arr as $to){
			$this->email->to($to);
			if($this->email->send()){
				$this->mdl_tags->save(array('tag_text'=>$to,'tag_created_by'=>$this->session->userdata('user_id')));
				$email_data = array(
										'email_receipient'=>$to,
										'email_sender_session'=>$this->session->userdata('session_id'),
										'email_report_type'=>'callreport',
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
		fclose($emailhandle);
		@unlink('uploads/calls/'.$name);
		$msg = '';
		//$msg = ($i==count($arr))?"":" but email could not be sent to some email-address";
		$error12 = array('type'=>'success','message'=>$this->lang->line('email_has_been_sent_successfully').$msg);
		$this->load->view('dashboard/ajax_messages',$error12);		
	}
}
?>