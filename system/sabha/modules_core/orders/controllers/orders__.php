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
	
	function addorder(){
		$this->editorder();

	}
	function editorder(){
		
		$this->redir->set_last_index();
		$this->load->model(array('orders/mdl_orders','orders/mdl_order_details','orders/mdl_order_parts','utilities/mdl_html','callcenter/mdl_callcenter','engineers/mdl_engineers','stocks/mdl_stocks','servicecenters/mdl_servicecenters','parts/mdl_parts'));
		$order_id= $this->uri->segment(3);
		$detailorders= $this->mdl_orders->detailorder($order_id);
		$orderparts= $this->mdl_order_parts->orderparts($order_id);
		
	
		/*echo '<pre>';
		print_r($detailorders);
		die();*/
		
		$this->session->set_userdata('order_status',$detailorders->order_status);
		if ($detailorders->order_status == 1){
			$this->session->set_userdata('on_transit','true');
			}
			else {
				$this->session->set_userdata('on_transit','false');
				}
			
		//print_r($this->session->userdata('order_status'));
		// Orders Options Dropdown, 
		
		
		$order_status= $this->mdl_mcb_data->getStatusOptions ('order_status');
		$default_order_center = $this->mdl_mcb_data->get('main_service_center');
	
			
		array_unshift($order_status, $this->mdl_html->option('','Select Order Status'));
		$order_status= $this->mdl_html->genericlist($order_status,'status',array('class'=>'validate[required] text-input'),'value','text',$detailorders->order_status);
		
		
	    $service_center= $this->mdl_servicecenters->getServiceCentersOptions($this->session->userdata('sc_id'));
		array_unshift($service_center, $this->mdl_html->option( '', 'Select Store'));
		
		
		$requested_sc_id = $this->mdl_html->genericlist($service_center,'requested_sc_id',array('class'=>'validate[required] text-input'),'value','text',$detailorders->requested_sc_id);
		
		if ($detailorders->requested_sc_id == ''){
			$requested_sc_id = $this->mdl_html->genericlist($service_center,'requested_sc_id',array('class'=>'validate[required] text-input'),'value','text',$default_order_center);
			}
		
		if ($detailorders->requesting_sc_id != 0) {
		$requesting_sc_id = $this->mdl_html->genericlist($service_center,'requesting_sc_id',array('class'=>'validate[required] text-input'),'value','text',$detailorders->requesting_sc_id);
		}
		else{
			$requesting_sc_id = $this->mdl_html->genericlist($service_center,'requesting_sc_id',array('class'=>'validate[required] text-input'),'value','text',$this->session->userdata('sc_id'));
			}
		
		
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
		$this->form_validation->set_rules('requested_sc_id', 'Store', 'required');
		
		
		if ($this->form_validation->run() == FALSE){
			$data=array(
						'result'=>$detailorders,
						'orderparts'=>$orderparts,
						'requested_sc_id'=>$requested_sc_id,
						'requesting_sc_id'=>$requesting_sc_id,
						'order_status'=>$order_status,
						'id'=>$order_id
						);
		$this->load->view('order/order',$data);	
		}else{
			$order_id = $this->mdl_orders->saveOrder();
			redirect('orders/editorder/'.$order_id);
		}
	}

	function getorderlist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('callcenter/mdl_callcenter','orders/mdl_orders','mcb_data/mdl_mcb_data','utilities/mdl_html','servicecenters/mdl_servicecenters','engineers/mdl_engineers'));
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
	function deleteparts()
	{
		$this->redir->set_last_index();
		$this->load->model('orders/mdl_order_parts');
		$order_part_id = (int)$this->input->post('order_part_id');
		if($this->mdl_order_parts->delete(array('order_part_id'=>$order_part_id))){
			$error = array('type'=>'delete','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	
	function getjsonparts(){
		$this->load->model(array('parts/mdl_parts'));
		$parts = $this->mdl_parts->getPartslist('');
		$json = array();
		echo json_encode($parts);
	}
	function get($params = NULL) {
		$this->load->model(array('orders/mdl_orders'));
		return $this->mdl_orders->get($params);
	}
	function getstockdeliverydetails(){
		$this->load->model(array('stocks/mdl_parts_stocks'));
		$sc_id = $this->input->post('sc_id');
		$part_number = $this->input->post('part_number');
		$part_quantity = $this->input->post('part_quantity');
		$data = array(
					  'sc_id'=>$sc_id,
					  'part_number'=>$part_number,
					  'part_quantity'=>$part_quantity
					  );
		$this->load->view('orders/order/deliverydetails',$data);
	}
	function getpartialdeliverydetails(){
		$order_part_id = $this->input->post('order_part_id');
		$this->load->model(array('stocks/mdl_parts_stocks','orders/mdl_order_parts','orders/mdl_order_parts_details','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		
		$order_parts = $this->mdl_order_parts->getOrderParts($order_part_id);
		$order_parts_details = $this->mdl_order_parts_details->getPartOderDetailsByOrderParts($order_part_id);
		$data = array(
					  'order_parts'=>$order_parts,
					  'order_parts_details'=>$order_parts_details
					  );
		$this->load->view('orders/order/partialdeliverydetails',$data);
	}
	function savedeliverypartdetails(){
		$this->load->model(array('stocks/mdl_stocks','orders/mdl_order_parts_details','orders/mdl_order_parts'));
		$order_part_details_id_arr = $this->input->post('order_part_details_id');
		$request_quantity_arr = $this->input->post('request_quantity');
		$p_order_part_id_arr = $this->input->post('p_order_part_id');
		$order_part_status_arr = $this->input->post('order_part_status');
		$part_number = $this->input->post('part_number');
		$sc_id = $this->input->post('requested_sc_id');
		$i=0;
		
		if($this->input->post('requested_sc_id') == $this->session->userdata('sc_id')){
			foreach($request_quantity_arr as $request_quantity){
				if($request_quantity>0){
					$data = array();	
					//for dispatch/transit
					$data['part_quantity'] = $request_quantity;
					$data['order_part_status'] = 1;
					$data['order_part_id'] = $this->input->post('order_part_id');
					$data['order_parts_details_created_by'] = $this->session->userdata('user_id');
					$data['order_parts_details_created_ts'] = date('Y-m-d H:i:s');
					$this->mdl_order_parts_details->save($data);
					$order_part_details_id = $this->db->insert_id();
					//stock out for requested Store
					$stockdata['part_number'] = $part_number;
					$stockdata['stock_quantity_in'] = 0;
					$stockdata['stock_quantity_out'] = $request_quantity;
					$stockdata['sc_id'] = $sc_id;
					$this->mdl_stocks->stockoutUpdate($stockdata,'stockout_partial_delivery',$order_part_details_id);	
				}
			$i++;	
			}
		}else{
			//for delivery
			if($this->input->post('requesting_sc_id') == $this->session->userdata('sc_id')){
				$delivered_checkbox = $this->input->post('delivered_checkbox');
				$i=0;
				foreach($order_part_details_id_arr as $order_part_details_id){
					if($delivered_checkbox[$i]==1){
						$data = array(
									'order_part_status'=>'2',
									'order_parts_details_last_mod_by'=>$this->session->userdata('user_id'),
									'order_parts_details_last_mod_ts'=>date('Y-m-d H:i:s')
									);
						//stock in for requesting Store
						$stockdata['part_number'] = $part_number;
						$stockdata['stock_quantity_in'] = 0;
						$stockdata['stock_quantity_out'] = $request_quantity;
						$stockdata['sc_id'] = $sc_id;
						$this->mdl_order_parts_details->save($data,$order_part_details_id);
						$this->mdl_stocks->stockinUpdate($stockdata,'stockin_partial_delivery',$order_part_details_id);	
					}
				$i++;
				}
			}
			//for delivery ends here
		
				/*if($part_request_qty_arr[$i]){
					$data['order_parts_details_created_by'] = $this->session->userdata('user_id');
					$data['order_parts_details_created_ts'] = date('Y-m-d H:i:s');
					$this->mdl_order_parts_details->save($data);
					if($order_part_status_arr[$i]==1){
						//stock out for requested Store
						$stockdata['part_number'] = $p_part_number_arr[$i];
						$stockdata['stock_quantity_in'] = 0;
						$stockdata['stock_quantity_out'] = $part_request_qty_arr[$i];
						$stockdata['sc_id'] = $sc_id;
						$this->mdl_stocks->stockoutUpdate($stockdata,'stockout',(int)$p_order_part_id_arr[$i]);
					}
				}*/
				/*if($order_part_details_id==0){
					$data['order_parts_details_created_by'] = $this->session->userdata('user_id');
					$data['order_parts_details_created_ts'] = date('Y-m-d H:i:s');
					$this->mdl_order_parts_details->save($data);
				}else{
					$data['order_parts_details_last_mod_by'] = $this->session->userdata('user_id');
					$data['order_parts_details_last_mod_ts'] = date('Y-m-d H:i:s');
					$this->mdl_order_parts_details->save($data,$order_part_details_id);
				}
			
			if($order_part_status_arr[$i]==1){
				//stock out for requested Store
				$stockdata['part_number'] = $p_part_number_arr[$i];
				$stockdata['stock_quantity_in'] = 0;
				$stockdata['stock_quantity_out'] = $part_request_qty_arr[$i];
				$stockdata['sc_id'] = $sc_id;
				$this->mdl_stocks->stockoutUpdate($stockdata,'stockout',$p_order_part_id_arr[$i]);
			}
			if($order_part_status_arr[$i]==2){
					//stock in for requesting Store
					//stock out for requested Store
				$stockdata['part_number'] = $p_part_number_arr[$i];
				$stockdata['stock_quantity_in'] = $part_request_qty_arr[$i];
				$stockdata['stock_quantity_out'] = 0;
				$stockdata['sc_id'] = $sc_id;
				$this->mdl_stocks->stockinUpdate($stockdata,'stockin',$p_order_part_id_arr[$i]);
			}
			$this->mdl_order_parts->save(array('order_part_status'=>$order_part_status_arr[$i]),$p_order_part_id_arr[$i]);*/
		
		}
	}
	
	function checkpart(){
		$part_quantity= $this->input->post('part_quantity');
		$part_number= $this->input->post('part_number');
		$this->load->model(array('parts/mdl_parts'));
		$partlist= $this->mdl_parts->getpart();
		if ( (strpos( $part_quantity, "." ) == false ) && is_numeric($part_quantity)==true) {
			foreach ($partlist as $part){
			     if ($part->part_number == $part_number){
			         echo 1;
			         die();
					}
				} 
			echo 3; die();
			
			}else {
				foreach ($partlist as $part){
			if ($part->part_number == $part_number){
			echo 2;
			die();
				}
			} 
			echo 4; die();}
			
					
	    }
}
?>