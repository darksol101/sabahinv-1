<?php defined('BASEPATH') or die('Direct access script in not allowed');
class Orders extends Admin_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language('orders',$this->mdl_mcb_data->get('orders'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('orders/orders/index',$data);
	}
	
	function editorder(){
		$this->load->model(array('orders/mdl_orders','orders/mdl_order_parts','utilities/mdl_html','callcenter/mdl_callcenter','engineers/mdl_engineers','stocks/mdl_stocks','mcb_data/mdl_mcb_data'));
		$order_id= $this->uri->segment(3);
		$orders= $this->mdl_orders->thisorder($order_id);
		$engineer_id= $this->mdl_orders->engineerid($orders->call_id);
		$engineer_id=$engineer_id->engineer_id;
		$orderparts= $this->mdl_order_parts->orderparts($order_id);
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
		$this->form_validation->set_rules('order_id', $this->lang->line('order_id'), 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$data=array(
						'result'=>$orders,
						'orderparts'=>$orderparts
						);
		
			$this->load->view('order/order',$data);
		}else{
			//for close of order
			if($this->input->post('status')==2){
				$data['order_status']= $this->input->post('status');
			}
			 $data["order_last_mod_ts"]=date("Y-m-d");
		     $data["order_last_mod_by"]=$this->session->userdata('user_id');
			
		  if($this->mdl_orders->updatestatus($data,$order_id)){
				redirect('orders');
		  }else{
			  redirect('orders/editorder/'.$order_id);
		  }
		}
	}
	function getorderlist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('callcenter/mdl_callcenter','orders/mdl_orders','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$orders= $this->mdl_orders->orderlist($page);

		$config['total_rows'] = $orders['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
					  'orders'=>$orders['list'],
					  'navigation'=>$navigation,
					  'page'=>$page
					  );
		$this->load->view('orders/orders/orderlist',$data);
	}	
}
?>