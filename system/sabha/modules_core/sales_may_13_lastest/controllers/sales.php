<?php defined('BASEPATH') or die('Direct access script in not allowed');
class Sales extends Admin_Controller{
	function __construct(){
		$this->call_id = 0;
		$this->sc_id = 0;
		$this->model_id = 0;
		$this->warrentysale_status = false;
		$this->call_ids = 0;
		$this->generate_bill = false;
		parent::__construct();
		$this->load->language('sales',$this->mdl_mcb_data->get('sales'));
	}


	 function index(){
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
		$statusOptions = $this->mdl_mcb_data->getStatusOptions('sales_status');
		array_unshift($statusOptions, $this->mdl_html->option( '', 'All Status'));
		$status_select = $this->mdl_html->genericlist($statusOptions,'sales_status',array('class'=>'text-input'),'value','text','');
		
		
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		
		
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'text-input'),'value','text','');
		
		$data = array(
					  
					  'status_select'=>$status_select,
					  'servicecenters'=>$servicecenters
					  );
		$this->load->view('sales/saleslist/index',$data);
		
	}
	function getjsonparts(){
		$this->load->model(array('parts/mdl_model_parts','parts/mdl_parts','sales/mdl_sales','stocks/mdl_parts_stocks'));
		$parts = $this->mdl_sales->getPartslist('');
		$json = array();
		echo json_encode($parts);
	}
	
	
	
	function getSalesList(){

		$this->redir->set_last_index();
		$this->load->model(array('bills/mdl_bills','mcb_data/mdl_mcb_data','utilities/mdl_html','servicecenters/mdl_servicecenters','sales/mdl_sales'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');

		$sales= $this->mdl_sales->saleslist($page);
		$config['total_rows'] = $sales['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		
		$data = array(
					  'sales'=>$sales['list'],
					  'navigation'=>$navigation,
					  'page'=>$page
					  );

		$this->load->view('sales/saleslist/saleslist',$data);
	}
	
	
	function sale($sales_id = 0){
		$this->redir->set_last_index();
		$warrenty = 0;
		$this->load->model(array('utilities/mdl_html',
								 'stocks/mdl_stocks',
								 'sales/mdl_sales',
								 'sales/mdl_sales_details',
								 'servicecenters/mdl_servicecenters',
								 'parts/mdl_parts',
								 'stocks/mdl_parts_stocks',
								 'company/mdl_company',
								 'bills/mdl_bills'
								 ));
		
		
		$salesdetails= $this->mdl_sales->salesdetails($sales_id);

		$sales_part= $this->mdl_sales_details->salesparts($sales_id);

    	//dump($sales_part); die();
		
		$bill_details = $this->mdl_bills->getBillBySales($sales_id);

		if($this->call_id>0){
			$this->load->model(array(
									'callcenter/mdl_callcenter',
									'parts/mdl_parts',
									'parts/mdl_parts_used',
									'customers/mdl_customers',
									'productmodel/mdl_productmodel'
								)
							);
			$sales_part = $this->mdl_parts_used->getPartUsedByCall($this->call_id);		
			$call_details = $this->mdl_callcenter->getCallDetailsByCallID($this->call_id);
			
			$customer_details = $this->mdl_customers->getCustomerDetailByCall($this->call_id);
			$salesdetails->customer_name = $customer_details->cust_first_name.(($customer_details->cust_last_name)?' '.$customer_details->cust_last_name:'');
			$salesdetails->customer_address = $customer_details->cust_address;
			$salesdetails->sc_id = $this->sc_id;
			$salesdetails->call_serial_no = $call_details->call_serial_no;
			$salesdetails->call_uid = $call_details->call_uid;
			$salesdetails->call_id = $this->call_id;
			$salesdetails->model_number = $call_details->model_number;
		}
		//print_r($sales_part);die();
		if ($this->warrentysale_status == true){
			$this->load->model(array(
								'parts/mdl_parts_used',
								'callcenter/mdl_callcenter',
								'warrentybill/mdl_warrentybill'
								));
		$sales_part = $this->mdl_warrentybill->getpartListsAll($this->call_ids);
		$salesdetails->customer_name = 'Warrenty Bill';
		$salesdetails->customer_address = '';
		$salesdetails->sc_id = $this->sc_id;
		$salesdetails->call_serial_no = '';
		$salesdetails->call_uid = '';
		$salesdetails->call_id = '';
		$salesdetails->model_number = '';
		$salesdetails->bill_type = 2;
		$salesdetails->warranty_sale = 1;
		
		}
		
		$sales_status= $this->mdl_mcb_data->getStatusOptions ('sales_status');
		array_unshift($sales_status, $this->mdl_html->option('','Select Sales Status'));
		$sales_status= $this->mdl_html->genericlist($sales_status,'sales_status',array('class'=>'validate[required] text-input'),'value','text','');
		
		$company_name = $this->mdl_company->getCompanyOptions();
		//array_unshift($company_name,$this->mdl_html->option('',''));
		$company_name=$this->mdl_html->genericlist($company_name,'company_name',array('class'=>'text-input '),'text','text','');
		
		
		$discount = $this->mdl_mcb_data->getStatusOptions('discount_type');
		array_unshift($discount, $this->mdl_html->option('','Discount Type'));
		$discount_type= $this->mdl_html->genericlist($discount,'discount_type',array('class'=>' text-input','onchange'=>'calculatetotal(); checkDiscount();'),'value','text',$salesdetails->discount_type);
		
		if($this->session->userdata('usergroup_id')==1){
	    $service_center= $this->mdl_servicecenters->getServiceCentersOptions();
	   	array_unshift($service_center, $this->mdl_html->option( '', 'Select Service Center'));
		}
		else
		{
			$service_center=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		} 
	
		$servicecenter = $this->mdl_html->genericlist($service_center,'service_center',array('class'=>'validate[required] text-input','onchange'=>'resetScValue();'),'value','text',$salesdetails->sc_id);
		
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        if($this->warrentysale_status==true){
        	$this->form_validation->set_rules('ledger_id', 'Account', 'required');
        }
		$this->form_validation->set_rules('service_center', 'Service center', 'required');
		
		
		if ($this->form_validation->run() == FALSE){
			$data=array(
						'salesdetails'=>$salesdetails,
						'sales_parts'=>$sales_part,
						'service_center'=>$servicecenter,
						'sales_status'=>$sales_status,
						'id'=>$sales_id,
						'discount_type'=>$discount_type,
						'company_name'=>$company_name,
						'bill_details'=>$bill_details
						);
		$this->load->view('sales/sales/index',$data);	
		}
		else{
			
			$sales_id = $this->mdl_sales->saveSales($this->generate_bill);
			redirect('sales/sale/'.$sales_id);
		}
	}

function deletesalesparts1()
	{$this->redir->set_last_index();
		$this->load->model('sales/mdl_sales_details');
		$sales_detail_id = (int)$this->input->post('sales_detail_id');
		if($this->mdl_sales_details->delete(array('sales_details_id'=>$sales_detail_id ))){
			//$error = array('type'=>'delete','message'=>$this->lang->line('this_record_has_been_deleted'));
			echo 1;
		}else{
			echo 0;	
			//$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		
		//$this->load->view('dashboard/ajax_messages',$error);
	}
	
	function deletesalesparts()
	{
		$this->redir->set_last_index();
		$sales_id = $this->input->post('sales_id');
		$sales_detail_id = (int)$this->input->post('sales_detail_id');
		$query = "call sp_delete_part_update($sales_id, $sales_detail_id)";
		$this->db->query($query);
		echo 1;
	}
function returnparts(){
		
		$this->redir->set_last_index();
		$this->load->model('sales/mdl_sales_details');
		$sales_detail_id = (int)$this->input->post('sales_detail_id ');
		$part_number = $this->input->post('part_number');
		$part_quantity = $this->input->post('part_quantity');
		$data['sales_detail_status'] = '1';
		$sc_id = $this->input->post('sc_id');
		if($this->mdl_sales_details->save($data,$sales_detail_id )){ 
				$this->load->model('stocks/mdl_stocks');
				$stockdata['sc_id'] = $sc_id;
				$stockdata['part_number'] = $part_number;
				$stockdata['part_quantity'] = $part_quantity;
				$stockdata['stock_dt'] = date('Y-m-d');
				$stockdata['stock_tm'] = date('H:i:s');
				$stockdata['stock_created_by'] = $this->session->userdata('user_id');
				$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
				$this->mdl_stocks->stockoinUpdate($stockdata, "sales Return", $sales_details_id);
							
		}else{
			//$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
		
	}
	
function printbill(){

		$this->load->model(array('sales/mdl_sales','salesmaker/mdl_salesmaker','bills/mdl_bill_details','sales/mdl_sales_details','parts/mdl_parts','bills/mdl_bills','servicecenters/mdl_servicecenters'));
		$sales_id = $this->input->post('sales_id');
		$maker_details = $this->mdl_sales->getMakerDetails($sales_id);
		$bill_details = $this->mdl_bills->getBillInfoBySalesID($sales_id);
		$bill_part_details= $this->mdl_bill_details->getBillDetailInfo($bill_details->bill_id);

		
		
		if(sizeof($maker_details) > 0){
		
		for ($i=0; $i < count($bill_part_details); $i++) { 
			
				if($maker_details[$i]->part_id == $bill_part_details[$i]->part_id){
							$bill_part_details[$i]->maker_id=$maker_details[$i]->maker_id;
							$bill_part_details[$i]->sale_deduction_type=$maker_details[$i]->sale_deduction_type;
							$bill_part_details[$i]->sale_deduction_value=$maker_details[$i]->sale_deduction_value;
							$bill_part_details[$i]->sale_name=$maker_details[$i]->sale_name;
				}

			}	
		}
	
		dump($bill_part_details); die();

		$data = array(
						'sales_id'=>$sales_id,
						'bill_details'=>$bill_details,
						'bill_part_details'=>$bill_part_details,
						'maker_details'=>$maker_details
						);
	

	//print_r($bill_details);
	if($bill_details->bill_type == 1){
		$this->load->view('sales/sales/salescard_abb',$data);
	}
	else{
		$this->load->view('sales/sales/salescard_taxinvoice',$data);
	}					
	
}
function create_bill(){
	$sales_id = $this->input->post('sale_id');
	$this->generate_bill = true;
	$this->sale();
}

function generatebill(){
	$this->redir->set_last_index();
	$sales_id = $this->input->post('sales_id');
	
	$this->form_validation->set_rules('sales_id','Sales ID','required|integer');	
	if($this->form_validation->run()== FALSE){
		$this->session->set_flashdata('custom_warning','Invalid sales id');
		redirect('sales/sale/'.$sales_id);			
	}else{
		$this->load->model('bills/mdl_bills');
		//$result = $this->db->query("call sp_create_bill($sales_id)");
		//$result->free_result();
		//$details = $this->mdl_bills->getBillBySales($sales_id);
		$this->session->set_flashdata('success_save','Bill Generated');
		
		redirect('sales/sale/'.$sales_id);			
	}
}

	function callssale(){
		$this->call_id = $this->uri->segment('3');
		$this->model_id = $this->uri->segment('4');
		$this->sc_id = $this->uri->segment('5');
		$this->sale();	
	}
	
function modsale(){
	
		$this->redir->set_last_index();
		$this->load->model(array('utilities/mdl_html',
								 'stocks/mdl_stocks',
								 'sales/mdl_sales',
								 'sales/mdl_sales_details',
								 'servicecenters/mdl_servicecenters',
								 'parts/mdl_parts',
								 'stocks/mdl_parts_stocks',
								 'company/mdl_company',
								 'bills/mdl_bills',
								 'account/ledger_model',
								 'account/mdl_ledgerassign'
								 ));
		
		$sales_id= $this->uri->segment(3);
		$salesdetails= $this->mdl_sales->salesdetails($sales_id);
		$sales_part= $this->mdl_sales_details->salesparts($sales_id);
		$bill_details = $this->mdl_bills->getBillBySales($sales_id);
		//print_r($sales_part);die();
		
		
		$sales_status= $this->mdl_mcb_data->getStatusOptions ('sales_status');
		array_unshift($sales_status, $this->mdl_html->option('','Select Sales Status'));
		$sales_status= $this->mdl_html->genericlist($sales_status,'sales_status',array('class'=>'validate[required] text-input'),'value','text','');
		
		$company_name = $this->mdl_company->getCompanyOptions();
		//array_unshift($company_name,$this->mdl_html->option('',''));
		$company_name=$this->mdl_html->genericlist($company_name,'company_name',array('class'=>'text-input '),'text','text','');
		
		
		$discount = $this->mdl_mcb_data->getStatusOptions('discount_type');
		array_unshift($discount, $this->mdl_html->option('','Discount Type'));
		$discount_type= $this->mdl_html->genericlist($discount,'discount_type',array('class'=>' text-input','onchange'=>'calculatetotal();'),'value','text',$salesdetails->discount_type);
		
		if($this->session->userdata('usergroup_id') ==1){
	    $service_center= $this->mdl_servicecenters->getServiceCentersOptions();
	   	array_unshift($service_center, $this->mdl_html->option( '', 'Select Service Center'));
		}
		else
		{
			$service_center=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		} 
	
		$servicecenter = $this->mdl_html->genericlist($service_center,'service_center',array('class'=>'validate[required] text-input','onchange'=>'getPartyLedger();'),'value','text',$salesdetails->sc_id);
		
		if($salesdetails->sales_type==1){
			$ledgerOptions = $this->mdl_ledgerassign->getBillingHeadOptions($salesdetails->sc_id,'LDGR3');
		}else{
			$ledgerOptions = $this->ledger_model->getPartyLedger($salesdetails->sc_id);			
		}
		array_unshift($ledgerOptions, $this->mdl_html->option('','Select Account'));
		$ledger= $this->mdl_html->genericlist($ledgerOptions,'ledger_id',array('class'=>' text-input validate[required]','onchange'=> 'setAccount();'),'value','text',$salesdetails->ledger_id);
	//	echo '<pre>';
		//print_r($party_ledger);
		
		$transaction_modeOptions = $this->mdl_mcb_data->getStatusOptions('transaction_mode');
		array_unshift($transaction_modeOptions, $this->mdl_html->option( '', 'Select Sales Type'));
		$transaction_mode_select = $this->mdl_html->genericlist($transaction_modeOptions,'sales_type',array('class'=>'text-input required','onchange'=>'getSalesType()'),'value','text',$salesdetails->sales_type);
		
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
		$this->form_validation->set_rules('service_center', 'Service center', 'required');
		
		
		if ($this->form_validation->run() == FALSE){
			$data=array(
						'salesdetails'=>$salesdetails,
						'sales_parts'=>$sales_part,
						'service_center'=>$servicecenter,
						'sales_status'=>$sales_status,
						'id'=>$sales_id,
						'discount_type'=>$discount_type,
						'company_name'=>$company_name,
						'bill_details'=>$bill_details,
						'ledger'=>$ledger,
						'transaction_mode_select'=>$transaction_mode_select
						);
		$this->load->view('sales/diff_mod_sale/index',$data);	
		}else{
			$sales_id = $this->mdl_sales->saveSalesmod();
		
			redirect('sales/modsale/'.$sales_id);
		}
	}
	function getledgersbytype(){
		$this->load->model(array('utilities/mdl_html','account/mdl_ledgerassign','account/ledger_model'));
		$sales_type = $this->input->post('sales_type');
		$warranty_claim = $this->input->post('warranty_claim');
		$sc_id = $this->input->post('sc_id');
		if($sales_type==1){
			$ledgerOptions = $this->mdl_ledgerassign->getBillingHeadOptions($sc_id,'LDGR3');
		}else{
			if($warranty_claim==1){
				$ledgerOptions = $this->mdl_ledgerassign->getBillingHeadOptions($sc_id,'LDGR7');
			}else{
				$ledgerOptions = $this->ledger_model->getPartyLedger($sc_id);
				array_unshift($ledgerOptions, $this->mdl_html->option('','Select Account'));
			}
		}
		if(count($ledgerOptions)==0){
            array_unshift($ledgerOptions, $this->mdl_html->option('','Select Account'));
        }
		//array_unshift($ledgerOptions, $this->mdl_html->option('','Select Account'));
		$ledger_select = $this->mdl_html->genericlist($ledgerOptions,'ledger_id',array('class'=>'text-input validate[required]','onchange'=>'setAccount()'),'value','text','');
		echo $ledger_select;
	}
	function warrentysale(){
		$this->form_validation->set_rules('call_bill','Calls','required');
		if($this->form_validation->run()==FALSE){
		redirect('warrentybill');
		}else{
			$call_id = $this->input->post('call_bill');
			$call_ids = implode (",", $call_id);
			$sc_id = $this->input->post('service_center');
			$this->sc_id = $sc_id;
			$this->call_ids = $call_ids;
			$this->warrentysale_status = true;
			$this->sale();
			}			
	}
	
	function getPartNum(){
		$this->redir->set_last_index();
		$this->load->model(array('parts/mdl_parts'));
		$part_num = $this->input->post('part_num');
		$part_rate = $this->mdl_parts->getCusPrice($part_num);
		print_r($part_rate) ; 
	}
	
	
	function getValues(){
		$this->redir->set_last_index();
		$this->load->model('sales/mdl_sales');
		$sales_id = $this->input->post('sales_id');
		//print_r($sales_id);
 		$values = $this->mdl_sales->getValues($sales_id);		
		echo json_encode($values);
	}

	public function checkOffer()
	{
		$partId = $this->input->post('part_id');
		$this->load->model(array('parts/mdl_parts','salesmaker/mdl_salesmaker','salesmaker/mdl_salesmakerlist'));
		$partlist = $this->mdl_parts->getPartWithDis($partId);
		$json = array();
		echo json_encode($partlist);		
		
	}
	
}
?>

   