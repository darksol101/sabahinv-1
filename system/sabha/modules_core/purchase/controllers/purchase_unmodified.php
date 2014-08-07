<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Purchase extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("purchase",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index() {
		$this->redir->set_last_index();
		$this->load->model(array('vendors/mdl_vendors','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$statusOptions = $this->mdl_mcb_data->getStatusOptions('purchase_status');
		array_unshift($statusOptions, $this->mdl_html->option( '', 'All Status'));
		$status_select = $this->mdl_html->genericlist($statusOptions,'purchase_status',array('class'=>'text-input'),'value','text','');
		
		$vendorOptions = $this->mdl_vendors->getVendorOptions();
		array_unshift($vendorOptions, $this->mdl_html->option( '', 'All Vendor'));
		$vendor_select = $this->mdl_html->genericlist($vendorOptions,'vendor_id',array('class'=>'validate[required] text-input'),'value','text','');
		
		$data = array(
					  'status_select'=>$status_select,
					  'vendor_select'=>$vendor_select
					  );
		$this->load->view('purchases/index',$data);
	}
	function addrecord() {
		$this->redir->set_last_index();
		$purchase_id = $this->uri->segment(3);
		
		$error = array();
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');
		$this->form_validation->set_rules('purchase_number', 'Purchase number', 'required');
		$this->form_validation->set_rules('vendor_select', 'vendor select', 'required');
		//$this->form_validation->set_rules('purchase_notes', 'Additional notes', 'required');
		
		$this->load->model(array('purchase/mdl_purchase','vendors/mdl_vendors','servicecenters/mdl_servicecenters','purchase/mdl_purchase_details','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$purchase = $this->mdl_purchase->getPurchase($purchase_id);
		
		$vendorOptions = $this->mdl_vendors->getVendorOptions();
		array_unshift($vendorOptions, $this->mdl_html->option( '', 'Select Vendor'));
		$vendor_select = $this->mdl_html->genericlist($vendorOptions,'vendor_select',array('class'=>'validate[required] text-input'),'value','text',$purchase->vendor_id);
		$purchasedetails = $this->mdl_purchase_details->getPurchaseDetails($purchase_id);
		
		$sc_id = $this->session->userdata('sc_id');
		
		//get Stores
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($sc_id);
		}
		$scenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'validate[required] text-input'),'value','text',$purchase->sc_id);
		
		if($this->form_validation->run() == FALSE){
			$data = array(
					  'vendor_select'=>$vendor_select,
					  'purchasedetails'=>$purchasedetails,
					  'purchase'=>$purchase,
					  'scenters'=>$scenters
					   );
			$this->load->view('purchase/purchase/add',$data);
		}else{
			//save for purchase data
			$purchase_id = $this->input->post('purchase_id');
			$purchase_number = $this->input->post('purchase_number');
			$vendor_id = $this->input->post('vendor_select');
			$purchase_status = 0;
			$sc_id = $this->input->post('sc_id');
			if ($this->input->post('deliver_status'))
			{
				$purchase_status = 1;
			}
			$purchase_date = date("Y-m-d",date_to_timestamp($this->input->post('purchase_date')));
			$purchase_notes = $this->input->post('purchase_notes');
			
			$data['purchase_number'] = $purchase_number;
			$data['vendor_id'] = $vendor_id;
			$data['purchase_date'] = $purchase_date;
			$data['purchase_notes'] = $purchase_notes;
			$data['purchase_status'] = $purchase_status;
			$data['sc_id'] = $sc_id;
			
			//add
			if((int)$purchase_id==0){
				$data["purchase_created_ts"]=date("Y-m-d H:i:s");
				$data["purchase_created_by"]=$this->session->userdata('user_id');
				if($this->mdl_purchase->save($data)){
					$purchase_id = $this->db->insert_id();
					$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}else{
					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved'));
				}
			}else{
				$data["purchase_last_mod_ts"]=date("Y-m-d");
				$data["purchase_last_mod_by"]=$this->session->userdata('user_id');
				if($this->mdl_purchase->save($data,$purchase_id)){
					$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}else{
					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved'));
				}
			}
			//save details
			$part_number = $this->input->post('pnum');
			$part_quantity = $this->input->post('pqty');
			$part_rate = $this->input->post('prate');
			
			$purchase_details_arr = array();
			$stock_data = array();
			$purchase_details_arr = $this->input->post('purchase_details_id');
			$deliver_status = $this->input->post('deliver_status');
		
			if($purchase_id){
				$i=0;
				if(is_array($purchase_details_arr)){
					foreach($purchase_details_arr  as $purchase_details_id){
						$stockdata['part_number'] = $part_number[$i] ;
						$stockdata['stock_quantity_in'] = $part_quantity[$i];
						
						if((int)$purchase_details_id==0){
							$datadetails['purchase_id'] = $purchase_id;
							$datadetails['part_number'] = $part_number[$i];
							$datadetails['part_quantity'] = $part_quantity[$i];
							$datadetails['part_rate'] = $part_rate[$i];
							$datadetails["purchase_details_created_ts"]=date("Y-m-d H:i:s");
							$datadetails["purchase_details_created_by"]=$this->session->userdata('user_id');
							$this->mdl_purchase_details->save($datadetails);
						}
						else{
							$datadetails['part_number'] = $part_number[$i];
							$datadetails['part_quantity'] = $part_quantity[$i];
							$datadetails['part_rate'] = $part_rate[$i];
							$datadetails["purchase_details_last_mod_ts"]=date("Y-m-d");
							$datadetails["purchase_details_last_mod_by"]=$this->session->userdata('user_id');
							$this->mdl_purchase_details->save($datadetails,$purchase_details_id);
							if($deliver_status){
								$this->load->model('stocks/mdl_stocks');
								$stockdata['sc_id'] = $sc_id;
								$stockdata['stock_dt'] = date('Y-m-d');
								$stockdata['stock_tm'] = date('H:i:s');
								$stockdata['stock_created_by'] = $this->session->userdata('user_id');
								$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
								$this->mdl_stocks->stockinUpdate($stockdata, "purchase", $purchase_details_id);
							}
						}
						$i++;
					}
				}
				redirect('purchase/editrecord/'.$purchase_id);
			}else{
				redirect('purchase/addrecord/');
			}
		}
	}
	
	function editrecord(){
		$this->addrecord();
		
	}
	
	function deleteparts(){
		$this->redir->set_last_index();
		$this->load->model('purchase/mdl_purchase_details');
		$purchase_details_id = (int)$this->input->post('purchase_details_id');
		if($this->mdl_purchase_details->delete(array('purchase_details_id'=>$purchase_details_id))){
			//hide save message on page refresh
			$this->session->set_flashdata('success_delete', false);
			$error = array('type'=>'delete','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		//$this->load->view('dashboard/ajax_messages',$error);
	}
	function deletepurchase()
	{
		$this->redir->set_last_index();
		$this->load->model(array('purchase/mdl_purchase'));
		$purchase_id = $this->input->post('purchase_id');
		if($this->mdl_purchase->delete(array('purchase_id'=>$purchase_id,'purchase_status'=>'1'))){
			$error = array('type'=>'delete','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	
	function showpurchaselist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('purchase/mdl_purchase','servicecenters/mdl_servicecenters','vendors/mdl_vendors','mcb_data/mdl_mcb_data'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$purchase_id = (int)$this->input->post('purchase_id');
		$list = $this->mdl_purchase->getPurchaselist($page);

		$purchases = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$data=array("purchases"=>$purchases, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('purchases/purchaselist', $data);
	}

	
	function getjsonparts(){
		$this->load->model(array('parts/mdl_parts'));
		$parts = $this->mdl_parts->getPartslist('');
		$json = array();
		echo json_encode($parts);
	}
}
?>
