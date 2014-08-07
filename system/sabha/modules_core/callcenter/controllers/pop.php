<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Pop extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("pop",  $this->mdl_mcb_data->setting('default_language'));
	}
	function productserialnumber(){
		$this->load->view('callcenter/pop/addnewserialnumber');
	}
	function serialhistory(){
		$this->load->model(array('productmodel/mdl_product_serial_number'));
		$call_id = $this->input->get('call_id');
		$serial_numbers = $this->mdl_product_serial_number->getProductSerialNumbersByCall($call_id);
		$data = array(
					  'serial_numbers'=>$serial_numbers
		);
		$this->load->view('callcenter/pop/serialhistory',$data);
	}
	function warrantyform(){
		$this->redir->set_last_index();
		$data = array(
					  'call_id'=>$this->input->get('call_id')
		);
		$this->load->view('callcenter/pop/warrantyupload',$data);
	}
	function partlist(){
		$this->redir->set_last_index();
	}
	function reminder(){
		$this->redir->set_last_index();
		$this->load->model(array('reminders/mdl_reminders','users/mdl_users'));
		$call_id = $this->input->post('call_id');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$reminders=	$this->mdl_reminders->getRemindersByCall($page);
		$config['total_rows'] = $reminders['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$ajaxaction  = $this->input->post('ajaxaction');

		$data = array(
					  'call_id'=>$call_id,
					  "reminders"=>$reminders['list'],
		);
		$this->load->view('callcenter/pop/reminder',$data);
	}
	function pendingreasons(){
		$data = array();
		$this->load->view('callcenter/pop/pendingreason',$data);
	}
	function getcallreasonpendinglist(){
		$this->load->model(array('callcenter/mdl_calls_log','users/mdl_users'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$logs = $this->mdl_calls_log->getCallReasonPending($page);
		$config['total_rows'] = $logs['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$reason_pending_log = $logs['list'];

		$data = array(
					  'reason_pending_log'=>$reason_pending_log,
					  'navigation'=>$navigation,
					  'page'=>$page
		);
		$this->load->view('callcenter/pop/pendingreasonlist',$data);
	}
	function getservicecenterlist(){
		$data = json_decode($this->input->post('data'));
		$service_centers = $data->centers;
		$data = array(
					  'service_centers'=>$service_centers
		);
		$this->load->view('callcenter/pop/servicecenterlist',$data);
	}
	function parts(){
		$this->redir->set_last_index();
		$this->load->model(array('orders/mdl_order_parts','parts/mdl_parts','orders/mdl_orders','company/mdl_company'));
		$call_id = $this->input->post('call_id');
		$parts = $this->mdl_order_parts->getaddedparts($call_id);
		$order = $this->mdl_orders->getOrderDetailsByCall($call_id);
		
		$data = array(
					  'parts'=>$parts,
					  'order'=>$order
					  );
		$this->load->view('callcenter/pop/parts',$data);
	}
	function getcallpartlist(){
		$this->redir->set_last_index();
		$this->load->model(array('parts/mdl_parts','orders/mdl_order_parts','productmodel/mdl_productmodel','parts/mdl_model_parts','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$brands=$this->mdl_parts->getPartsInclude($page);
		$config['total_rows'] = $brands['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$data=array(
					"parts"=>$brands['list'],
					"ajaxaction"=>$ajaxaction,
					"page"=>$page,
					"navigation"=>$navigation
		);
		$this->load->view('callcenter/pop/partlist',$data);
	}
	
	
	function company(){
		$this->redir->set_last_index();
		$this->load->model(array('orders/mdl_order_parts','parts/mdl_parts','orders/mdl_orders','company/mdl_company'));
		$this->load->view('callcenter/pop/company');
	}
	function getcompanylist(){
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html','company/mdl_company'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$company=$this->mdl_company->getcompanylist($page);
		//print_r($company);
		$config['total_rows'] = $company['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$data=array(
					"lists"=>$company['list'],
					"ajaxaction"=>$ajaxaction,
					"page"=>$page,
					"navigation"=>$navigation
		);
		$this->load->view('callcenter/pop/companylist',$data);
	}
	
	
	
	function addparttocall(){
		$this->load->model(array('orders/mdl_order_parts','orders/mdl_orders'));
		$order_id = $this->input->post('order_id');
		if($order_id){
			$this->mdl_orders->save($order_id);
		}else{
			$this->mdl_orders->save($order_id);
		}
		die();
	}
	function removepartfromcall(){
		$this->load->model(array('orders/mdl_order_parts'));
		$call_id = $this->input->post('call_id');
		$part_number = $this->input->post('part_number');
		$order_part_id = $this->input->post('order_part_id');
		$this->mdl_order_parts->delete(array('part_number'=>$part_number,'order_part_id'=>$order_part_id));
	}
	function getavaliablepartslist(){
		$sc_id = $this->input->post('sc_id');
		$engineer_id = $this->input->post('engineer_id');
		$data=array(
					"sc_id"=>$sc_id,
					"engineer_id"=>$engineer_id
					);
		$this->load->view('callcenter/pop/search_tab_parts',$data);
	}
	
	function getpartslistpopup()
	{
		$sc_id = $this->input->post('sc_id');
		$engineer_id = $this->input->post('engineer_id');
		$this->redir->set_last_index();
		$this->load->model(array('parts/mdl_model_parts','stocks/mdl_parts_stocks','parts/mdl_parts','company/mdl_company','partallocation/mdl_partallocation'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$lists = $this->mdl_partallocation->getAvailableStocks($engineer_id,$sc_id,$page);
		
   		$config['total_rows'] = $lists['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
    	$data = array( "lists" => $lists,
					  "ajaxaction"=>$ajaxaction,
					  "navigation"=>$navigation,
					  "page"=>$page,
					  "config"=>$config);
		
		
		$this->load->view('callcenter/pop/used_parts_list',$data);
	}
		
	function removeusedparts(){
		
		$this->load->model(array('stocks/mdl_stocks','parts/mdl_parts_used','partallocation/mdl_partallocation','partallocation/mdl_partallocation_details','company/mdl_company','stocks/mdl_stocks'));


		$company_id= $this->mdl_company->getcompanyid($this->input->post('company'));
		//print_r($this->input->post('engineer_id'));
		$stock_data = array(
							'sc_id'=>$this->input->post('sc_id'),
							'part_number'=>$this->input->post('part_number'),
							'engineer_id' =>$this->input->post('eng'),
							'company_id'=>$company_id,
							'allocated_quantity'=>$this->input->post('quantity'),
							);
		
		if($this->mdl_parts_used->delete(array('parts_used_id'=>$this->input->post('parts_used_id')))){
			
			$this->mdl_partallocation->checkallocation($stock_data);
			$this->mdl_partallocation_details->revokefromcall($stock_data);
			$this->mdl_stocks->deleteCallPart($this->input->post('parts_used_id'));
			
			//$this->mdl_stocks->deleteCallPart($this->input->post('parts_used_id'));
			//$this->mdl_stocks->stockoutDelete($stock_data,'stockout_used',$this->input->post('parts_used_id'));
		}
	}
	function removeorderparts(){
		$this->load->model(array('orders/mdl_orders','orders/mdl_order_parts'));
		$order_part_id = $this->input->post('order_part_id');
		$order_id = $this->input->post('order_id');
		$this->db->select('order_id');
		$this->db->from($this->mdl_orders->table_name);
		$this->db->where('order_id =',$order_id);
		$this->db->where('order_status =',0);
		$result = $this->db->get();
		if($result->num_rows()>0){
			if($this->mdl_order_parts->delete(array('order_part_id'=>$order_part_id))){
				echo $this->lang->line('parts_deleted_successfully');
			}else{
				echo  $this->lang->line('parts_not_deleted');
			}
		}else{
			echo  $this->lang->line('parts_not_deleted');
		}
	}
	
	
	function removeorderpartscalls(){
		$this->load->model(array('orders/mdl_orders','orders/mdl_order_parts','orders/mdl_calls_orders'));
		
		$calls_orders_id = $this->input->post('order_part_id');
		
		$this->mdl_calls_orders->delete(array('calls_orders_id'=>$calls_orders_id));
		
		}
}
?>