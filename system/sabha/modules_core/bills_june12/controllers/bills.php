<?php defined('BASEPATH') or die('Direct access script in not allowed');
class Bills extends Admin_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language('bills',$this->mdl_mcb_data->get('default_language'));
	}
	function generateBill(){		
		$this->load->model(array('bills/mdl_bills'));
		$data['total_price'] = $this->input->post('total_price');
		$data['tax'] = $this->input->post('tax');
		$data['discount_type'] = $this->input->post('discount_type');
		$data['discount_value'] = $this->input->post('discount');
		$data['grand_total'] = $this->input->post('grand_total');
		$data['sales_id'] = $this->input->post('sales_id');	
		$bill_id = $this->input->post('bill_id');
		$discount_type = $data['discount_type'];
		$discount = $data['discount_value'];
	    $tax = $data['tax'];
		$total_price = $data['total_price'];
		
		if($discount_type == 1)
		{ $discount_amount = (($discount / 100)*$total_price) ; } 
		elseif ($discount_type == 2) 
		{ $discount_amount =  $discount; }
		else
		{$discount_amount = 0 ;}
		$data['tax_amount'] = (($tax/100)*($total_price-$discount_amount));
		$data['discount_amount'] = $discount_amount;
		$data['bill_created_by'] = $this->session->userdata('user_id');
		$data['bill_number']= $this->mdl_bills->getBillNumber();
		if($bill_id){
			
		$data['bill_last_printed'] = date('Y-m-d H:i:s') ;
		if ($this->mdl_bills->save($data,$bill_id)){
			
			echo 1;
			
		}else{ return 0;}
		
		}
		else {
			
		if ($this->mdl_bills->save($data)){
			
			echo 1;
		}else{ return 0;}
		}	
	}
	
	function index(){
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
		$statusOptions = $this->mdl_mcb_data->getStatusOptions('bill_status');
		array_unshift($statusOptions, $this->mdl_html->option( '', 'All Status'));
		$status_select = $this->mdl_html->genericlist($statusOptions,'bill_status',array('class'=>'text-input'),'value','text','');
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		$filter_bill_type = $this->input->get('tp');
		
		$bill_type = $this->mdl_mcb_data->getStatusOptions('bill_type');
		$bill_type = $this->mdl_html->genericlist($bill_type,'bill_type',array('class'=>'text-input'),'value','text',$filter_bill_type);
		
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'text-input'),'value','text',$this->session->userdata('sc_id'));
		$data = array(
					  'status_select'=>$status_select,
					  'servicecenters'=>$servicecenters,
					   'bill_type'=>$bill_type
					  );
		$this->load->view('billlist/index',$data);
		
	}
	
	function getBillsList(){
        //dump($this->input->post());
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data','utilities/mdl_html','servicecenters/mdl_servicecenters','bills/mdl_bills','sales/mdl_sales'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$bills= $this->mdl_bills->billList($page);
		$config['total_rows'] = $bills['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		
		$data = array(
					  'bills'=>$bills['list'],
					  'navigation'=>$navigation,
					  'page'=>$page
					 
					  );
		$this->load->view('bills/billlist/bills',$data);
	}


	function view($bill_id = 0){
		$this->load->model(array('sales/mdl_sales','bills/mdl_bills','bills/mdl_bill_details','servicecenters/mdl_servicecenters'));
		$bill = $this->mdl_bills->getBillByID($bill_id);
		if(count($bill)==0){
			$this->session->set_flashdata('custom_warning',$this->lang->line('bill_does_not_exists'));
			redirect('bills');
		}else{
			if($bill->sc_id!=$this->session->userdata('sc_id') && $this->session->userdata('is_admin')!=1){
				$this->session->set_flashdata('custom_warning',$this->lang->line('not_allowed_to_view'));
				redirect('bills');
			}
		}
		$bill_part_details= $this->mdl_bill_details->getBillDetailInfo($bill_id);
			
		$data = array(
					'bill'=>$bill,
					'bill_part_details'=>$bill_part_details
		);
		$this->load->view('bill/view',$data);
	}	
	function confirmPrint(){
		$this->redir->set_last_index();
		$this->load->model('bills/mdl_bills');
		$bill_id = $this->input->post('bill_id');
		$data['printed'] = '1';
		$this->mdl_bills->save($data,$bill_id);
	}

	function cancelBill(){
		$this->redir->set_last_index();
		$this->load->model('bills/mdl_bills');
		$bill_id= $this->input->post('bill_id');
		$data['bill_status'] = '2';
		$this->mdl_bills->save($data,$bill_id);		
	}
	function printbill(){
		$bill_id = $this->input->post('bill_id');
		$this->load->model(array('sales/mdl_sales','salesmaker/mdl_salesmaker','bills/mdl_bill_details','sales/mdl_sales_details','parts/mdl_parts','bills/mdl_bills','servicecenters/mdl_servicecenters'));
		$bill_details = $this->mdl_bills->getBillInfoByBillID($bill_id);
		

		$bill_part_details= $this->mdl_bill_details->getBillDetailInfo($bill_details->bill_id);
		
		
		$data = array(
						'sales_id'=>$bill_details->sales_id,
						'bill_details'=>$bill_details,
						'bill_part_details'=>$bill_part_details,
						);
	if($bill_details->bill_type == 1){
			$this->load->view('sales/sales/salescard_abb',$data);
		}else{
			$this->load->view('sales/sales/salescard_taxinvoice',$data);
		}	
	}
}
?>