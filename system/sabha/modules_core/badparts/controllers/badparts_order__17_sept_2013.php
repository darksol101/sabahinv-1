<?php defined('BASEPATH') or die('Direct access script in not allowed');
class Badparts_order extends Admin_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language('badparts',$this->mdl_mcb_data->setting('default_language'));
		
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
		$statusOptions = $this->mdl_mcb_data->getStatusOptions('badparts_order_status');
		array_unshift($statusOptions, $this->mdl_html->option( '', 'All Status'));
		$status_select = $this->mdl_html->genericlist($statusOptions,'order_status',array('class'=>'text-input'),'value','text','');
		
		
		
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
		
		$this->load->view('badparts/order_list/index',$data);
	}
	
	
	function addbadpartorder(){
		$this->redir->set_last_index();
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters','badparts/mdl_badparts_order','parts/mdl_parts','badparts/mdl_badparts_order_parts','mcb_data/mdl_mcb_data'));
		$badpart_orderid= $this->uri->segment(4);
		$detailorders= $this->mdl_badparts_order->detailorder($badpart_orderid);
		$orderparts= $this->mdl_badparts_order_parts->orderparts($badpart_orderid);
	    $default_order_center = $this->mdl_mcb_data->get('main_service_center');
		
		$order_status= $this->mdl_mcb_data->getStatusOptions ('order_status');
		
		array_unshift($order_status, $this->mdl_html->option('','Select Order Status'));
		$order_status= $this->mdl_html->genericlist($order_status,'status',array('class'=>'validate[required] text-input','onchange'=>'transitdetail($(this).val())'),'value','text',$detailorders->badparts_order_status);
		
		//$order_type = $this->mdl_mcb_data->getStatusOptions ('order_type');
		//array_unshift($order_type, $this->mdl_html->option('','Select Order type'));
		//$order_type= $this->mdl_html->genericlist($order_type,'order_type',array('class'=>'validate[required] text-input'),'value','text',$detailorders->order_type);
		
		
		
		
	    $service_center= $this->mdl_servicecenters->getServiceCentersOptions($this->session->userdata('sc_id'));
		array_unshift($service_center, $this->mdl_html->option( '', 'Select Store'));
		
		if($this->session->userdata('usergroup_id')== 1){
			
		  $service_center_from= $this->mdl_servicecenters->getServiceCentersOptions($this->session->userdata('sc_id'));}
		  else {
			  if ($detailorders->badparts_from_sc_id == ''){
				 
			  $service_center_from= $this->mdl_servicecenters->	getServiceCentersOptionsById($this->session->userdata('sc_id'));
			  } 
			  else { 
			  $service_center_from= $this->mdl_servicecenters->	getServiceCentersOptionsById($detailorders->badparts_from_sc_id);
			
			  }
			  
			  }
		
		array_unshift($service_center_from, $this->mdl_html->option( '', 'Select Store'));
		
	//	getServiceCentersOptionsById
		
		

		$badparts_to_sc_id = $this->mdl_html->genericlist($service_center,'badparts_to_sc_id',array('class'=>'validate[required] text-input'),'value','text',$detailorders->badparts_to_sc_id);
		
		if ($detailorders->badparts_to_sc_id == ''){
			$badparts_to_sc_id = $this->mdl_html->genericlist($service_center,'badparts_to_sc_id',array('class'=>'validate[required] text-input'),'value','text',$default_order_center);
			}
		
		if (!empty($detailorders->badparts_from_sc_id)) {
			//print_r($detailorders->requested_sc_id);
		$badparts_from_sc_id = $this->mdl_html->genericlist($service_center_from,'badparts_from_sc_id',array('class'=>'validate[required] text-input'),'value','text',$detailorders->badparts_from_sc_id);
		}
		else{
			 
			$badparts_from_sc_id = $this->mdl_html->genericlist($service_center_from,'badparts_from_sc_id',array('class'=>'validate[required] text-input'),'value','text',$this->session->userdata('sc_id'));
			}
		
		
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
		$this->form_validation->set_rules('badparts_from_sc_id', 'Store', 'required');
		
		
		if ($this->form_validation->run() == FALSE){
			$data=array(
						'badpart_orderid'=>$badpart_orderid,
						'detailorders'=>$detailorders,
						'orderparts'=>$orderparts,
						'badparts_to_sc_id'=>$badparts_to_sc_id,
						'badparts_from_sc_id'=>$badparts_from_sc_id,
						'order_status'=>$order_status,
						'badpart_orderid'=>$badpart_orderid,
						);
		$this->load->view('badparts/orders/tab_badpart_order',$data);	
		}else{
			$order_id = $this->mdl_badparts_order->saveOrder();
			redirect('badparts/badparts_order/addbadpartorder/'.$order_id);
		}
	
		
		}
		
		
		
		function getorderlist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data','utilities/mdl_html','servicecenters/mdl_servicecenters','badparts/mdl_badparts_order'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$orders= $this->mdl_badparts_order->orderlist($page);
		
		$config['total_rows'] = $orders['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		
		$data = array(
					  'orders'=>$orders['list'],
					  'navigation'=>$navigation,
					  'page'=>$page
					 
					  );
		$this->load->view('badparts/order_list/orderlist',$data);
	}
		
		
		function badpartlist(){
			$this->redir->set_last_index();
			$this->load->model(array('badparts/mdl_returnparts','parts/mdl_parts'));
			$from_sc_id = $this->input->post('from_sc_id');
			
			$badpart_list = $this->mdl_returnparts->getBadPartsList($from_sc_id);
			
			$data= array (
						  'badpart_list'=>$badpart_list
						  );
			
			$this->load->view('badparts/orders/badpartlist',$data);
			
			}
			

	function deleteparts(){
		$this->load->model(array('badparts/mdl_badparts_order_parts'));
		$id = $this->input->post('order_part_id');
		$this->mdl_badparts_order_parts->delete(array('badparts_order_part_id'=>$id));
		}
		
}
?>