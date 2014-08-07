<?php defined('BASEPATH') or die('Direct access script in not allowed');
class Salesreturn extends Admin_Controller{
	function __construct(){
		$this->bill_id = 0;
		$this->sales_return_id = 0;
		parent::__construct();
		$this->load->language('sales_return',$this->mdl_mcb_data->get('sales'));
	}
	function index(){
		$data = array();
		$this->load->view('return/index',$data);
	}
	function getsalesreturnlist(){
		$this->load->model(array('sales/mdl_sales_return','servicecenters/mdl_servicecenters'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = site_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$salesreturns = $this->mdl_sales_return->getSalesReturnList($page);
		$config['total_rows'] = $salesreturns['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
					 'salesreturns'=>$salesreturns['list'],
					 'navigation'=>$navigation
					);
		$this->load->view('return/salesreturnlist',$data);
	}
	function add(){
		$this->load->model(array('utilities/mdl_html',
								 'stocks/mdl_stocks',
								 'sales/mdl_sales_return',
								 'sales/mdl_sales_return_details',
								 'servicecenters/mdl_servicecenters',
								 'parts/mdl_parts',
								 'stocks/mdl_parts_stocks',
								 'company/mdl_company',
								 'bills/mdl_bills',
								 'account/ledger_model',
								 'account/mdl_ledgerassign'
								 ));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('sc_id','Store','integer|required');
		$this->form_validation->set_rules('bill_id','Bill','required');
		$this->form_validation->set_rules('pnum','Item number','required');
		
		if($this->form_validation->run()==FALSE){
			$sales_return_details = $this->mdl_sales_return->getSalesReturnDetails($this->sales_return_id);
			$sales_parts_details = $this->mdl_sales_return_details->salesReturnPartsDetails($sales_return_details->sales_return_id);
			if($this->sales_return_id>0 && $sales_return_details->sales_return_id==0){
				redirect('sales/salesreturn/add');	
			}
			if($sales_return_details->sales_return_status > 0){
				redirect(site_url('sales/salesreturn/view/'.$sales_return_details->sales_return_id));	
			}
			$billtypeOptions = $this->mdl_mcb_data->getStatusOptions('bill_type');
			$bill_type_select = $this->mdl_html->genericlist($billtypeOptions,'bill_type',array('class'=>'text-input','tabindex'=>1),'value','text',$sales_return_details->bill_type);
		
			$stocktypeOptions = $this->mdl_company->getCompanyOptions();
			$stocktype_select=$this->mdl_html->genericlist($stocktypeOptions,'stock_type',array('class'=>'text-input '),'text','text','');			
			
			$data = array(
						'sales_return_details'=>$sales_return_details,
						'sales_parts_details'=>$sales_parts_details,
						'bill_type_select'=>$bill_type_select,
						'stocktype_select'=>$stocktype_select
			);
			
	        $this->load->view('return/add',$data);
		}else{
			//save the sales return details
			$bill_id = $this->input->post('bill_id');
			$bill_type = $this->input->post('a_bill_type');
			$sc_id = $this->input->post('sc_id');
			$bill_number = $this->input->post('bill_number');
			//$sales_number = $this->input->post('sales_number');
			$call_serial_no = $this->input->post('call_serial_number');
			$call_serial_no = $this->input->post('call_serial_number');
			$sales_return_date = date("Y-m-d",date_to_timestamp($this->input->post('sales_return_date')));
			$sales_return_type = $this->input->post('sales_return_type');
			$customer_name = $this->input->post('customer_name');
			$customer_address = $this->input->post('customer_address');
			$customer_vat = $this->input->post('customer_vat');		
			$sales_return_discount_type = $this->input->post('discount_type');	
			$discount_amount = $this->input->post('discount_amount');
			$sales_return_remarks = $this->input->post('sales_return_remarks');
			
			$pnum = $this->input->post('pnum');	
			$prate = $this->input->post('prate');	
			$pqty = $this->input->post('p_return_pqty');
			
			/*
			 * Check for valid sc code
			 * */
			$sc_code = $this->mdl_servicecenters->getScCode($sc_id);
			if($sc_code == ''){
				$this->session->set_flashdata('custom_warning','Invalid Store code');
				redirect($this->uri->uri_string());		
				return false;
			}
			
			$i=0;
			
			$sales_return_total_price = 0.00;
			$sales_return_rounded_grand_total_price = 0.00;
			$sales_return_discount_amount = $discount_amount;
			$sales_return_discounted_price = 0.00;
			$sales_return_tax = 0.00;
			$sales_return_tax_price = 0.00;
			$sales_return_service_charge_price;
			
			foreach($pnum as $pn){
				$sales_return_total_price+=$pqty[$i]*$prate[$i];
				$i++;
			}
			if($sales_return_discount_type==1){
				$sales_return_discounted_price = $sales_return_total_price*$discount_amount/100;
			}else{
				$sales_return_discounted_price = $discount_amount;
			}
			if($bill_type==1){
				$sales_return_tax_price = ($sales_return_total_price/1.13)*.13;
				$sales_return_rounded_grand_total_price = $sales_return_total_price - $sales_return_discounted_price;
				$sales_return_rounded_grand_total_price = round($sales_return_rounded_grand_total_price,0,PHP_ROUND_HALF_UP);
			}else{
				$sub_total_price = $sales_return_total_price - $sales_return_discounted_price;
				$sales_return_tax_price = ($sub_total_price*.13);
				$sales_return_rounded_grand_total_price = $sub_total_price + $sales_return_tax_price;
				$sales_return_rounded_grand_total_price = round($sales_return_rounded_grand_total_price,0,PHP_ROUND_HALF_UP);
			}
			
			$sc_id = $this->input->post('sc_id');
			$data = array(
					'bill_id'=>$bill_id,
					'sc_id'=>$sc_id,
					'bill_type'=>$bill_type,
					'sales_return_remarks'=>$sales_return_remarks,
					'customer_name'=>$customer_name,
					'customer_address'=>$customer_address,
					'customer_vat'=>$customer_vat,					
					'sales_return_date'=>$sales_return_date,
					'sales_return_total_price'=>$sales_return_total_price,
					'sales_return_rounded_grand_total_price'=>$sales_return_rounded_grand_total_price,
					'sales_return_grand_total'=>$sales_return_total_price,
					'sales_return_discount_type'=>$sales_return_discount_type,
					'sales_return_discount_amount'=>$sales_return_discount_amount,
					'sales_return_discounted_price'=>$sales_return_discounted_price,
					'sales_return_tax'=>$sales_return_tax,
					'sales_return_tax_price'=>$sales_return_tax_price,
					'sc_code'=>$sc_code
					);

			if($this->sales_return_id>0){
				$data['sales_return_last_modified_by'] = $this->session->userdata('user_id');
				$data['sales_return_last_modified_ts'] =date('Y-m-d H:i:s');
			}else{
				$data['bill_number'] = $bill_number;				
				$data['call_serial_no'] = $call_serial_no;		
				$data['sales_return_created_by'] = $this->session->userdata('user_id');
				$data['sales_return_created_ts'] = date('Y-m-d H:i:s');
			}	
			$success = $this->mdl_sales_return->saveReturn($data,$this->sales_return_id);
			if($success){
				redirect('sales/salesreturn/edit/'.$success);				
			}else{
				redirect('sales/salesreturn/add');
			}
		}
	}
	function generate(){
		$this->load->model(array('sales/mdl_sales_return','stocks/mdl_stocks'));		
		$sales_return_id = $this->input->post('g_sales_return_id');
		/*check if already generated*/
		$this->db->select('COUNT(sales_return_id) AS total');
		$this->db->from($this->mdl_sales_return->table_name);
		$this->db->where('sales_return_id',$sales_return_id);
		$this->db->where('sales_return_status',0);
		$result = $this->db->get();
		$row = $result->row();
		if($row->total==0){
			redirect('sales/salesreturn/view/'.$sales_return_id);
		}
		$this->form_validation->set_rules('g_sales_return_id','Sales Return Id','required');
		$this->form_validation->set_rules('generate','Generate task','required');		
		if($this->form_validation->run()==FALSE){	
			redirect('sales/salesreturn/'.$sales_return_id);
		}else{
			$data = array(
				'sales_return_id'=>$sales_return_id,
				'sales_return_status'=>1,
				'sales_return_last_modified_by'=>$this->session->userdata('user_id'),
				'sales_return_last_modified_ts'=>date('Y-m-d H:i:s'),
				'sales_return_generated_ts'=>date('Y-m-d H:i:s')
			);
			if($data['sales_return_status']==1){
				$this->db->set('sales_return_number',"(SELECT (LPAD((COUNT(*)+1),5,'0')) FROM (SELECT *FROM sst_sales_return) AS sr1 WHERE sc_id = (SELECT sc_id  FROM (SELECT *FROM sst_sales_return) AS sr2 WHERE sr2.sales_return_id = ".$sales_return_id.") AND sales_return_status=1)",false);
			}
			$this->db->where($this->mdl_sales_return->primary_key, $sales_return_id);
			if($this->db->update($this->mdl_sales_return->table_name,$data)){
				/*stock update*/				
				$sales_return_details_id = $this->input->post('sales_return_details_id');
				$part_ids = $this->input->post('part_id');
				$part_quantity = $this->input->post('bad_part_quantity');
				$company = $this->input->post('company_id');
				$sc_id = $this->input->post('service_center_id');				
				$i=0;
				foreach ($part_ids as $part_id){
					$stockdata['part_id'] = $part_id;
					$stockdata['stock_quantity_in'] = $part_quantity[$i];
					$stockdata['company_id'] =  $company[$i];
					$stockdata['sc_id'] = $sc_id;
					$stockdata['stock_dt'] = date('Y-m-d');
					$stockdata['stock_tm'] = date('H:i:s');
					$stockdata['stock_created_by'] = $this->session->userdata('user_id');
					$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
					$this->mdl_stocks->stockinUpdate($stockdata, "salesreturn", $sales_return_details_id[$i]);
					$i++;
				}
				redirect('sales/salesreturn/view/'.$sales_return_id);				
			}else{
				redirect('sales/salesreturn/edit/'.$sales_return_id);
			}
		}
		
	}
	function getbillpartdetails(){
		$bill_id = $this->input->post('bill_id');
		$this->load->model(array('bills/mdl_bill_details','company/mdl_company'));
		$bill_parts = $this->mdl_bill_details->getBillPartDetailInfo($bill_id);
		$data = array('bill_parts'=>$bill_parts);
		$this->load->view('return/bill_part_list',$data);		
	}
	function edit($sales_return_id = 0){
		$this->sales_return_id = $sales_return_id;
		$this->add();		
	}
	function view($sales_return_id = 0){
		$this->load->model(array('utilities/mdl_html',
								 'stocks/mdl_stocks',
								 'sales/mdl_sales_return',
								 'sales/mdl_sales_return_details',
								 'servicecenters/mdl_servicecenters',
								 'parts/mdl_parts',
								 'stocks/mdl_parts_stocks',
								 'company/mdl_company',
								 'bills/mdl_bills',
								 'account/ledger_model',
								 'account/mdl_ledgerassign'
								 ));
		$this->sales_return_id = $sales_return_id;
		$sales_return_details = $this->mdl_sales_return->getSalesReturnViewDetails($this->sales_return_id);
		if($sales_return_details->sales_return_status == 0){
			redirect(site_url('sales/salesreturn/edit/'.$sales_return_details->sales_return_id));	
		}
		$sales_parts_details = $this->mdl_sales_return_details->salesReturnPartsDetails($sales_return_details->sales_return_id);
		
		$data = array(
					'sales_return_details'=>$sales_return_details,
					'sales_parts_details'=>$sales_parts_details
					);
		$this->load->view('return/view',$data);
	}
	function getjsonbills(){
		$sc_id = $this->session->userdata('sc_id');
		$bill_type = $this->input->get('bill_type');
		$bill_type = '0';
		$this->load->model(array('bills/mdl_bills','bills/mdl_bill_details', 'sales/mdl_sales', 'sales/mdl_sales_return','servicecenters/mdl_servicecenters'));
		$parts = $this->mdl_bills->getbills($sc_id,$bill_type);
		$json = array();
		echo json_encode($parts);
	}

	function getledgersbytype(){
		$this->load->model(array('utilities/mdl_html','account/mdl_ledgerassign','account/ledger_model'));
		$sales_return_type = $this->input->post('sales_return_type');
		$ledger_id = $this->input->post('ledger_id');		
		$sc_id = $this->input->post('sc_id');
		if($sales_return_type==1){
			$ledgerOptions = $this->mdl_ledgerassign->getBillingHeadOptions($sc_id,'LDGR3');
		}else{
			$ledgerOptions = $this->ledger_model->getLedgerByID($ledger_id);
			array_unshift($ledgerOptions, $this->mdl_html->option('','Select Account'));
		}
		$ledger_select = $this->mdl_html->genericlist($ledgerOptions,'ledger_id',array('class'=>'text-input validate[required]','onchange'=>'setAccount()'),'value','text',$ledger_id);
		echo $ledger_select;
	}
	function deletesalesreturnparts()
	{
		$this->redir->set_last_index();
		$this->load->model('sales/mdl_sales_return_details');
		$sales_return_details_id = (int)$this->input->post('sales_return_details_id');
		if($this->mdl_sales_return_details->delete(array('sales_return_details_id'=>$sales_return_details_id ),false)){
			$error = array('error'=>false,'message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('error'=>true,'message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		print_r(json_encode($error));
	}	
	function creditnote(){
		$sales_return_id = $this->input->post('sales_return_id');
		$this->load->model(array('servicecenters/mdl_servicecenters','sales/mdl_sales_return','sales/mdl_sales_return_details','company/mdl_company'));
		
		$sales_return_details = $this->mdl_sales_return->getSalesReturnPrintDetails($sales_return_id);
		$sales_parts_details = $this->mdl_sales_return_details->salesReturnPartsDetails($sales_return_details->sales_return_id);
			
		$data = array(
				'sales_return_details'=>$sales_return_details,
				'sales_parts_details'=>$sales_parts_details
				);
		$this->load->view('return/creditnote',$data);
	}
}
?>
