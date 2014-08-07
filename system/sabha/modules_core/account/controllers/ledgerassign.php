<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ledgerassign extends Account_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language("ledgerassign",  $this->mdl_mcb_data->setting('default_language'));	
		if($this->session->userdata('global_admin')!=1){
			$this->session->set_flashdata('custom_warning','Unathorised access');
			redirect('account');
		}   				
	}
	function index(){
		$this->load->model(array('account/ledger_model', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
		$ledgerOptions = $this->ledger_model->getLedgerOptions();
		array_unshift($ledgerOptions,$this->mdl_html->option('','Select Ledger'));
		$ledger_select = $this->mdl_html->genericlist($ledgerOptions,'ledger_id',array('class'=>'validate[required] select-one'),'value','text',0);
		
		$filter_ledger_select = $this->mdl_html->genericlist($ledgerOptions,'filter_ledger_select',array('class'=>''),'value','text',0);
		
		$billingOptions = array();
		$billingOptions[] = $this->mdl_html->option('','Select Billing Heads');
		$billingOptions[] = $this->mdl_html->option('Total:LDGR1','Total');
		$billingOptions[] = $this->mdl_html->option('Sales:LDGR2','Sales');
		$billingOptions[] = $this->mdl_html->option('Cash:LDGR3','Cash');
		$billingOptions[] = $this->mdl_html->option('Purchase:LDGR4','Purchase');
		$billingOptions[] = $this->mdl_html->option('Vat:LDGR5','Vat');
		$billingOptions[] = $this->mdl_html->option('Discount:LDGR6','Discount');
		$billingOptions[] = $this->mdl_html->option('Warranty Claim:LDGR7','Warranty Claim');
		
		$billing_select = $this->mdl_html->genericlist($billingOptions,'billing_select',array('class'=>'validate[required] select-one'),'value','text',0);
		
		$billingTypeOptions = array();
		$billingTypeOptions[] = $this->mdl_html->option('','Select Type');
		$billingTypeOptions[] = $this->mdl_html->option('D','Dr');
		$billingTypeOptions[] = $this->mdl_html->option('C','Cr');
		$billing_type_select = $this->mdl_html->genericlist($billingTypeOptions,'ledger_assign_type',array('class'=>'validate[required] select-one'),'value','text',0);
		
	   $serviceCentersOptions = $this->mdl_servicecenters->getServiceCentersOptions();
	   array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Service Centre'));
	   $servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'sc_id',array('class'=>'validate[required] select-one'),'value','text',0);
	   
	   $servicecenter_filter  =  $this->mdl_html->genericlist($serviceCentersOptions,'filter_sc_id',array(),'value','text',0);
		
		$data = array(
					'ledger_select'=>$ledger_select,
					'billing_select'=>$billing_select,
					'servicecenter_select'=>$servicecenter_select,
					'billing_type_select'=>$billing_type_select,
					'filter_ledger_select'=>$filter_ledger_select,
					'servicecenter_filter'=>$servicecenter_filter
					);
		$this->load->view('ledgerassign/index',$data);
	}
	function saveledgerassign(){
		$this->load->model(array('account/mdl_ledgerassign'));
		$this->form_validation->set_rules('ledger_id','Ledger','required');
		$this->form_validation->set_rules('billing_select','Billing Head','required');
		$this->form_validation->set_rules('sc_id','Service Center','required');
		$this->form_validation->set_error_delimiters('<span>', '</span>');
		if($this->form_validation->run() == FALSE){
			$error = array('type'=>'validation-error','message'=>validation_errors());
		}else{
			$ledger_assign_id = $this->input->post('ledger_assign_id');
			$ledger_id = $this->input->post('ledger_id');
			$billing_select = $this->input->post('billing_select');
			$sc_id = $this->input->post('sc_id');
			$ledger_assign_type = $this->input->post('ledger_assign_type');
			$arr = explode(':', $billing_select);
			$data = array(
						'ledger_assign_name'=>$arr[0],
						'ledger_assign_key'=>$arr[1],
						'ledger_assign_type'=>$ledger_assign_type,
						'ledger_id'=>$ledger_id,
						'sc_id'=>$sc_id
						);
			if((int)$ledger_assign_id>0){
				$data['ledger_assign_modified_by'] = $this->session->userdata('sc_id');
				$data['ledger_assign_modified_ts'] = date('Y-m-d H:i:s');
			}else{		
				$data['ledger_assign_created_by'] = $this->session->userdata('sc_id');
				$data['ledger_assign_created_ts'] = date('Y-m-d H:i:s');			
			}
			if($this->mdl_ledgerassign->save($data,$ledger_assign_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));	
			}else{
				$error = array('type'=>'validation-error','message'=>$this->lang->line('this_record_could_not_be_saved').$this->db->_error_message());
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getledgerassignlist(){
		$this->load->library('ajaxpagination');
		$this->load->model(array('account/mdl_ledgerassign','servicecenters/mdl_servicecenters','account/ledger_model'));
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$assigns = $this->mdl_ledgerassign->getLedgerAssignList($page);
		
		$config['total_rows'] = $assigns['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
					'assigns'=>$assigns['list'],
					'navigation'=>$navigation
					);
		$this->load->view('ledgerassign/getledgerassignlist', $data);
	}
	function deleteledgerassign(){
		$this->form_validation->set_rules('ledger_assign_id','Ledger Assign ID','required');
		$this->form_validation->set_error_delimiters('<span>', '</span>');
		if($this->form_validation->run() == FALSE){
			$error = array('type'=>'validation-error','message'=>validation_errors());
		}else{
			$this->load->model(array('account/mdl_ledgerassign'));
			$ledger_assign_id = $this->input->post('ledger_assign_id');
			if($this->mdl_ledgerassign->delete(array('ledger_assign_id'=>$ledger_assign_id))){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_deleted'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_could_not_be_deleted'));
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function editledgerassign(){
		$this->form_validation->set_rules('ledger_assign_id','Ledger Assign ID','required');
		$this->form_validation->set_error_delimiters('<span>', '</span>');
		if($this->form_validation->run() == FALSE){
			$error = array('type'=>'validation-error','message'=>validation_errors());
		}else{
			$this->load->model(array('account/mdl_ledgerassign'));
			$ledger_assign_id = $this->input->post('ledger_assign_id');
			$assign_details = $this->mdl_ledgerassign->getLedgerAssignDetails($ledger_assign_id);
			echo json_encode($assign_details);
		}
	}
}