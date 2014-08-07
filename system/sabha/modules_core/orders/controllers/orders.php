<?php defined('BASEPATH') or die('Direct access script in not allowed');
class Orders extends Admin_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language('orders',$this->mdl_mcb_data->get('orders'));
	}
	function index()
	{
		
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
		$statusOptions = $this->mdl_mcb_data->getStatusOptions('order_status');
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
	
		
		$this->load->view('orders/orders/index',$data);
	}
	
	function addorder(){
		/*if($this->input->post('part_number')==0){

			dump($this->redir->set_last_index());
			echo "asdfasd"; die();
		}*/

		$this->editorder();

	}
	function editorder(){

	
		$this->redir->set_last_index();
		$this->load->model(array('orders/mdl_orders',
								 'orders/mdl_order_details',
								 'orders/mdl_order_parts',
								 'utilities/mdl_html',
								 'callcenter/mdl_callcenter',
								 'engineers/mdl_engineers',
								 'stocks/mdl_stocks',
								 'servicecenters/mdl_servicecenters',
								 'parts/mdl_parts',
								 'company/mdl_company',
								 'orders/mdl_transit_details',
								 'callcenter/mdl_callcenter',
								 'orders/mdl_order_parts_details'));
		

		//checking store id 




		$order_id= $this->uri->segment(3);
		$detailorders= $this->mdl_orders->detailorder($order_id);
		$orderparts= $this->mdl_order_parts->orderparts($order_id);
		$transit_details = $this->mdl_transit_details->gettransitdetails($order_id);
		$default_order_center = $this->mdl_mcb_data->get('main_service_center');
		
		/*echo '<pre>';
		print_r($default_order_center);
		die();*/
		$this->session->set_userdata('orid',$order_id);
		$this->session->set_userdata('order_status',$detailorders->order_status);

		if ($detailorders->order_status == 2){
			$this->session->set_userdata('on_transit','true');
			}
			else {
				$this->session->set_userdata('on_transit','false');
				}

		$this->session->set_userdata('sts',$detailorders->order_status);
		//print_r($this->session->userdata('order_status'));
		// Orders Options Dropdown, 
		$company_name = $this->mdl_company->getCompanyOptions();
		//array_unshift($company_name,$this->mdl_html->option('',''));
		$company_name=$this->mdl_html->genericlist($company_name,'company_name',array('class'=>'text-input '),'text','text','');
		
		$order_status= $this->mdl_mcb_data->getStatusOptions ('order_status');
		
			
		array_unshift($order_status, $this->mdl_html->option('','Select Order Status'));
		$order_status= $this->mdl_html->genericlist($order_status,'status',array('class'=>'validate[required] text-input','onchange'=>'transitdetail($(this).val())'),'value','text',$detailorders->order_status);
		
		$order_type = $this->mdl_mcb_data->getStatusOptions ('order_type');
		array_unshift($order_type, $this->mdl_html->option('','Select Order type'));
		$order_type= $this->mdl_html->genericlist($order_type,'order_type',array('class'=>'validate[required] text-input'),'value','text',$detailorders->order_type);
		
		
		
		
	    $service_center= $this->mdl_servicecenters->getServiceCentersOptions($this->session->userdata('sc_id'));
		array_unshift($service_center, $this->mdl_html->option( '', 'Select Store'));
		
		if($this->session->userdata('usergroup_id')== 1){
			
		  $service_center_requesting= $this->mdl_servicecenters->getServiceCentersOptions($this->session->userdata('sc_id'));}
		  else {
			  if ($detailorders->requested_sc_id == ''){
				 
			  $service_center_requesting= $this->mdl_servicecenters->	getServiceCentersOptionsById($this->session->userdata('sc_id'));
			  } 
			  else { 
			  $service_center_requesting= $this->mdl_servicecenters->	getServiceCentersOptionsById($detailorders->requesting_sc_id);
			
			  }
			  
			  }
		
		array_unshift($service_center_requesting, $this->mdl_html->option( '', 'Select Store'));
		
	//	getServiceCentersOptionsById
		
		

		$requested_sc_id = $this->mdl_html->genericlist($service_center,'requested_sc_id',array('class'=>'validate[required] text-input'),'value','text',$detailorders->requested_sc_id);
		
		if ($detailorders->requested_sc_id == ''){
			$requested_sc_id = $this->mdl_html->genericlist($service_center,'requested_sc_id',array('class'=>'validate[required] text-input'),'value','text',$default_order_center);
			}
		
		if (!empty($detailorders->requesting_sc_id)) {
			//print_r($detailorders->requested_sc_id);
		$requesting_sc_id = $this->mdl_html->genericlist($service_center_requesting,'requesting_sc_id',array('class'=>'validate[required] text-input'),'value','text',$detailorders->requesting_sc_id);
		}
		else{
			 
			$requesting_sc_id = $this->mdl_html->genericlist($service_center_requesting,'requesting_sc_id',array('class'=>'validate[required] text-input'),'value','text',$this->session->userdata('sc_id'));
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
						'id'=>$order_id,
						'company_name'=>$company_name,
						'order_type'=>$order_type,
						'transit_details'=>$transit_details
						);
		$this->load->view('order/order',$data);	
		}
		else{
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
		$this->load->model(array('stocks/mdl_parts_stocks','company/mdl_company'));
		$sc_id = $this->input->post('sc_id');
		$part_number = $this->input->post('part_number');
		$part_quantity = $this->input->post('part_quantity');
		$company_id = $this->input->post('company_id');
		//$company_id = $this->mdl_company->getcompanyid($this->input->post('company_id'));
		
		$data = array(
					  'sc_id'=>$sc_id,
					  'part_id'=>$part_number,
					  'part_quantity'=>$part_quantity,
					  'company_id'=>$company_id
					  );
		$this->load->view('orders/order/deliverydetails',$data);
	}
	function getpartialdeliverydetails(){
		$order_part_id = $this->input->post('order_part_id');
		$this->load->model(array('stocks/mdl_parts_stocks','orders/mdl_order_parts','orders/mdl_order_parts_details','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		
		$order_parts = $this->mdl_order_parts->getOrderParts($order_part_id);
		$order_parts_details = $this->mdl_order_parts_details->getPartOderDetailsByOrderParts($order_part_id);
		$dispatched_quantity = $this->mdl_order_parts_details->getDispatchedQuantity($order_part_id);
		$delivered_quantity = $this->mdl_order_parts_details->getDeliveredQuantity($order_part_id);
		$data = array(
					  'order_parts'=>$order_parts,
					  'order_parts_details'=>$order_parts_details,
					  'dispatched_quantity'=>$dispatched_quantity,
					  'delivered_quantity'=>$delivered_quantity
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
		$company_id = $this->input->post('company_id');
	
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
					$stockdata['part_id'] = $part_number;
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
						$stockdata['part_id'] = $part_number;
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
				/*
				if($order_part_details_id==0){
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
		$company = $this->input->post('company');
		$this->load->model(array('parts/mdl_parts'));
	

		$partlist= $this->mdl_parts->getpart();


		if ($part_number == '' || $part_quantity == ''){die();}
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
		function getorderpartbymodel(){
		$this->redir->set_last_index();
		
		$this->load->view('orders/order/parts');
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
		$brands=$this->mdl_parts->getPartsorder($page);
		$config['total_rows'] = $brands['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		//$model = $this->mdl_productmodel->getmodelsearch();
		//print_r($model);
		$data=array(
					"parts"=>$brands['list'],
					"ajaxaction"=>$ajaxaction,
					"page"=>$page,
					"navigation"=>$navigation
					//"model"=> $model
		);
		$this->load->view('orders/order/partlist',$data);
	}
	
		
	function transitdetail(){
		$this->load->model(array('orders/mdl_transit_details'));
		$order_id = $this->input->post('order_id');
		//print_r($order_id);
		$transit_details = $this->mdl_transit_details->gettransitdetails($order_id);
		
		$data = array(
					  'transit_details' => $transit_details
					  );
		
		$this->load->view('orders/order/transit',$data);
		
		}	
		
	function savetransit(){
		$this->load->model(array('orders/mdl_transit_details','servicecenters/mdl_servicecenters','orders/mdl_orders'));
		//print_r($this->input->post()); die();
		$requesting_sc_id = $this->input->post('requesting_sc_id');
		$chalan_number = $this->mdl_transit_details->getChalanNumber($requesting_sc_id,$this->input->post('order_id'));
		
		$transit_detail_id = $this->input->post('transit_detail_id');
		$data['chalan_number'] = $chalan_number->chalan_number;
		$data['order_id']= $this->input->post('order_id');
		$data['courior_date'] = $this->input->post('courior_date');
		$data['courior_number']=$this->input->post('courior_number');
		$data['box_number']=$this->input->post('num_box');
		$data['vehicle_number']=$this->input->post('vehicle_num');
		$data['transit_number']=$this->input->post('transit_num');
		
		if (empty($transit_detail_id)){
			$data['transit_detail_created_by']=$this->session->userdata('user_id');
			$data['transit_detail_created_ts']=date('Y-m-d H:i:s');
			$this->mdl_transit_details->save($data);
			$id = $this->db->insert_id();
			print_r($id);
		}else{
			$data['transit_detail_modified_by']= $this->session->userdata('user_id');
			$data['transit_detail_modified_ts']=date('Y-m-d H:i:s');
			$this->mdl_transit_details->save($data,$transit_detail_id);
		}
		
		}
		
	
		
		function callorder(){
			
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		
		
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'text-input'),'value','text','');
		$datas = array(
					  'servicecenters'=>$servicecenters
					   );
			$this->load->view('massorder/index',$datas);
			
			}
			
			
	
	
	function getcallorderlist(){
		
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','engineers/mdl_engineers','callcenter/mdl_callcenter','orders/mdl_calls_orders','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$callorders = $this->mdl_calls_orders->callorders($page);
		$config['total_rows'] = $callorders['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		//print_r($navigation);
		
		$data = array(
					  'callorders'=>$callorders['list'],
					  'navigation'=>$navigation,
					  'page'=>$page
					 
					  );
		$this->load->view('massorder/callorderlist',$data);
	}
			
	function savebulkorder(){
		
		$this->load->model(array('orders/mdl_orders','orders/mdl_calls_orders','orders/mdl_order_parts'));
		$order_id = '';
		$record = $this->mdl_calls_orders->getparts();
		
		$this->mdl_orders->save($record,$order_id);
		$this->mdl_calls_orders->deleteparts();
		}
		
		
	function getcallorderpart(){
				$this->redir->set_last_index();
				$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
				
				if($this->session->userdata('usergroup_id')==1){
						$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
						array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
					}else{
						$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
					}
				
				
				$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'text-input'),'value','text','');
				$datas = array(
						'servicecenters'=>$servicecenters
				);
				$this->load->view('order/popcallpart',$datas);
				
				
			}
			
			
			
			
	function addorderedpart(){
		$this->redir->set_last_index();
		$this->load->model(array('orders/mdl_orders','orders/mdl_calls_orders','orders/mdl_order_parts'));
		$records = $this->mdl_calls_orders->getparts();
		$this->mdl_order_parts->savecallpart($records);
		$this->mdl_calls_orders->deleteparts();
		
		}
		
	function orderpostlist(){
		
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		if($this->session->userdata('usergroup_id')==1){
				$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
				array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
			}else{
				$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
			}
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'text-input'),'value','text','');
		$datas = array(
				'servicecenters'=>$servicecenters
		);
		
		$this->load->view('orderpost/index',$datas);
	}
		
		
	function ordertransitlist (){
		$this->redir->set_last_index();
		$this->load->model(array('orders/mdl_orders','orders/mdl_order_parts'));
		$list = $this->mdl_orders->gettransitlist();
		
		$data = array(
					  'lists' => $list
					  );
		
		$this->load->view('orderpost/ordertransitlist',$data);
		}
		
	function partialdeliverychalan(){
		$order_id = $this->input->post('order_id');
		$requesting_sc_id = $this->input->post('requesting_sc_id');
		$requested_sc_id = $this->input->post('requested_sc_id');
		
		//print_r($order_id);
		$this->load->model(array('stocks/mdl_parts_stocks','orders/mdl_order_parts','orders/mdl_order_parts_details','mcb_data/mdl_mcb_data','utilities/mdl_html','parts/mdl_parts','company/mdl_company','callcenter/mdl_callcenter'));
		$orderparts= $this->mdl_order_parts->orderparts($order_id);
		
		$datas= array(
					  'requesting_sc_id'=>$requesting_sc_id,
					  'requested_sc_id'=>$requested_sc_id,
					  'order_id' => $order_id,
					  'orderparts'=>$orderparts
					  
					  );
		$this->load->view('order/createchalan',$datas);
		
		}
		
		
	function checkstock(){
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','parts/mdl_parts'));
		$sc_id=$this->input->post('requested_sc_id');
		$part_number = $this->input->post('part_number');

		$part_number = $this->mdl_parts->getPartsId($part_number);

		$company_id = $this->input->post('company');
		
		$part_quantity = $this->mdl_parts_stocks->checkPartsStock($sc_id,$part_number,$company_id);

		
		echo $part_quantity->stock_quantity;
		
		
	}

	function savepartialparts(){
			$this->redir->set_last_index();
			$order_part_id = $this->input->post('order_part_id');
			$quantity = $this->input->post('quantity');
			$this->load->model(array('orders/mdl_order_parts_details','stocks/mdl_stocks','stocks/mdl_parts_stocks','parts/mdl_parts'));
			
			$stockdata['stock_dt'] = date('Y-m-d');
			$stockdata['stock_tm'] = date('H:i:s');
			$stockdata['stock_created_by'] = $this->session->userdata('user_id');
			$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
			$stockdata['part_id'] =$this->mdl_parts->getPartsId($this->input->post('part_number'));
			$stockdata['company_id']= $this->input->post('company');
			
			//First for stock out for requested service ceter
			$stockdata['stock_quantity_in'] = 0;
			$stockdata['stock_quantity_out'] = $quantity;
			$stockdata['sc_id'] = $this->input->post('requested_sc_id');
			//$this->mdl_stocks->stockoutUpdate($stockdata,'stockout',$order_part_id);
			
			$this->mdl_parts_stocks->updatePartsInTransit($stockdata);
			
			$datas = array(
						   'transit_id'=>$this->input->post('transit_id'),
						  'order_part_id'=>$order_part_id,
						  'order_part_status'=>0,
						  'part_quantity'=>$quantity,
						  'order_parts_details_created_by'=>$this->session->userdata('user_id'),
						  'order_parts_details_created_ts'=>date('y-m-d H:i:s')
						   );

			$this->mdl_order_parts_details->save($datas);
			
			}
			
		
		function showchalans (){
			$this->redir->set_last_index();
			$this->load->model(array('orders/mdl_transit_details'));
			$chalans = $this->mdl_transit_details->getTransitByOrder($this->input->post('order_id'));
			$datas = array(
						   'chalans'=>$chalans
						   );
			$this->load->view('order/chalans',$datas);
			}
			
			
		function receivepartialparts(){
			$this->redir->set_last_index();
			$this->load->models(array('orders/mdl_orders','orders/mdl_order_parts','parts/mdl_parts','orders/mdl_order_parts_details','orders/mdl_transit_details'));
			
			$transit_id = $this->input->post('transit_detail_id');

			$transit_details = $this->mdl_transit_details->getOrderId($transit_id);
			

			$datas = array(
						   'transit_details'=>$transit_details
						   );
	
			$this->load->view('order/receivechalan',$datas);
			//print_r($this->input->post('transit_detail_id'));
			
		}


			
		function enterpartialdelivery(){


			$this->redir->set_last_index();
			
			
			$this->load->model(array('orders/mdl_order_parts_details','stocks/mdl_stocks','parts/mdl_parts','stocks/mdl_parts_stocks'));
			$order_part_detail_id = $this->input->post('order_part_detail_id');

			$part_number= $this->input->post('part_number');

			$part_number = $this->mdl_parts->getPartsId($part_number);

		

			$dispatched_entered_quantity= $this->input->post('dispatched_entered_quantity');
			$requested_sc_id= $this->input->post('requested_sc_id');
			$requesting_sc_id= $this->input->post('requesting_sc_id');
			$dispatched_chalan_quantity = $this->input->post('dispatched_chalan_quantity');
			$order_part_id =  $this->input->post('order_part_id');
			$company = $this->input->post('company');

			//$num = $this->mdl_stocks->gethighest();
			//$num = $num+1;
			
			$part_transit_update = $this->mdl_order_parts_details->getOrderPartDetailTransit($order_part_detail_id);
			
			
			
			if ($part_transit_update->order_part_status ==1){
			if ($part_transit_update->differance < $dispatched_entered_quantity){
				echo '1';
			 die();
				
				}
				}
			if ($part_transit_update->order_part_status ==0 && $part_transit_update->part_quantity < $dispatched_entered_quantity ){
				echo '2';
			 die();
				
				}
			
					$stockdata['stock_dt'] = date('Y-m-d');
					$stockdata['stock_tm'] = date('H:i:s');
					$stockdata['stock_created_by'] = $this->session->userdata('user_id');
					$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
					$stockdata['part_id'] = $part_number;
					$stockdata['company_id']= $company;
					
					//First for stock out for requested service ceter
					$stockdata['stock_quantity_in'] = 0;
					$stockdata['stock_quantity_out'] = $dispatched_entered_quantity;
					$stockdata['sc_id'] = $this->input->post('requested_sc_id');
					$this->mdl_stocks->stockoutUpdate1($stockdata,'stockout_partial',$order_part_id);
					
					// REMOVE Parts from Transit
					$this->mdl_parts_stocks->removeTransitPart($stockdata);
					
					
					//Second for stock in for requesting Store
					$stockdata['sc_id'] = $this->input->post('requesting_sc_id');
					$stockdata['stock_quantity_in'] = $dispatched_entered_quantity;
					$stockdata['stock_quantity_out'] = 0;
					$this->mdl_stocks->stockinUpdate1($stockdata,'stockin_partial',$order_part_id);
				
				
			if ($part_transit_update->order_part_status ==0)
				{
				if ($dispatched_chalan_quantity > $dispatched_entered_quantity)
					{
					$diff = $dispatched_chalan_quantity - $dispatched_entered_quantity;
					$final_quantity = $dispatched_entered_quantity;
					}
				else 
					{
						$diff =0;
						$final_quantity = $dispatched_entered_quantity;
					}
				}
			
			
			
			
		
			
			if ($part_transit_update->order_part_status ==1){
				
			$final_quantity = $part_transit_update->part_quantity + $dispatched_entered_quantity;
			
			if ($part_transit_update->differance > $dispatched_entered_quantity){
				
				$diff = $part_transit_update->differance - $dispatched_entered_quantity;
				
				}else{$diff = $part_transit_update->differance - $dispatched_entered_quantity;}
			}
			
			$datas = array(
						  'part_quantity'=>$final_quantity,
						  'order_part_id'=>$order_part_id,
						  'order_part_status'=>1,
						  'differance'=>$diff,
						  'order_parts_details_last_mod_by'=>$this->session->userdata('user_id'),
						  'order_parts_details_last_mod_ts'=>date('y-m-d H:i:s')
						   );
			
			
			$this->mdl_order_parts_details->save($datas,$order_part_detail_id);
			
			
	}
			
			
	function ordercard(){
		$this->load->model(array('orders/mdl_orders','orders/mdl_order_details','orders/mdl_order_parts','utilities/mdl_html','callcenter/mdl_callcenter','engineers/mdl_engineers','stocks/mdl_stocks','servicecenters/mdl_servicecenters','parts/mdl_parts','company/mdl_company','orders/mdl_transit_details','orders/mdl_transit_details'));
		
		
		$order_id= $this->session->userdata('orid');
		$total=$this->mdl_order_parts->totalorderparts($order_id); 
		$detailorders= $this->mdl_orders->printorder($order_id);
		$orderparts= $this->mdl_order_parts->orderparts($order_id);
		$transit_details = $this->mdl_transit_details->gettransitdetails($order_id);
		$transit_details_datewise= $this->mdl_transit_details->getDetailsDateWise();
		$data = array(
					  'detailorders'=>$detailorders,
					  'orderparts'=>$orderparts,
					  'transit_details'=>$transit_details,
					  'order_id'=>$order_id,
					  'total'=>$total,
					  'transit_details_datewise'=>$transit_details_datewise
					  
					  );
		$this->load->view('order/ordercard',$data);
		
		}
		
		function partialordercard(){
		$this->load->model(array('orders/mdl_orders','orders/mdl_order_details','orders/mdl_order_parts','utilities/mdl_html','callcenter/mdl_callcenter','engineers/mdl_engineers','stocks/mdl_stocks','servicecenters/mdl_servicecenters','parts/mdl_parts','company/mdl_company','orders/mdl_transit_details','orders/mdl_transit_details'));
		$order_id= $this->input->post('order_id');
		$transit_id= $this->input->post('transit_id');
		//print_r($transit_id);
		$orderparts= $this->mdl_order_parts->orderparts($order_id);
		$total=$this->mdl_order_parts->totalorderparts($order_id); 
		$detailorders= $this->mdl_orders->printorder($order_id);
		$transit_details = $this->mdl_transit_details->gettransitdetailsprint($transit_id);
		
		$data = array(
					  'detailorders'=>$detailorders,
					  'orderparts'=>$orderparts,
					  'transit_details'=>$transit_details,
					  'order_id'=>$order_id,
					  'total'=>$total,
					  //'transit_details_datewise'=>$transit_details_datewise
					  'transit_id'=>$transit_id
					  
					  );

		$this->load->view('order/ordercard',$data);
		
		}
		
		
	function showPickingList(){
		$this->redir->set_last_index();
		$this->load->model(array('partbin/mdl_partbin','company/mdl_company','orders/mdl_order_parts','stocks/mdl_parts_stocks','parts/mdl_parts','partbin/mdl_partbin_details','orders/mdl_orders','servicecenters/mdl_servicecenters'));
		$order_id =  $this->input->post('order_id');
		
		$order_details = $this->mdl_orders->thisorderpicking($order_id);

		$lists = $this->mdl_order_parts->getPickingList($order_id);
		
		
		$datas=array(
					 'lists' =>$lists,
					 'order_details'=>$order_details
					 );

		$this->load->view('order/pickinglist',$datas);
		
		
		}
		
		
	
		
}
?>