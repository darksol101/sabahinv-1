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
		
		$order_status= $this->mdl_mcb_data->getStatusOptions ('badparts_order_status');
		
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
		
		
	function createchalan(){
		$this->redir->set_last_index();
	$this->load->model(array('badparts/mdl_badparts_order_parts','parts/mdl_parts'));
		$badparts_order_id = $this->input->post('badparts_order_id');
		$from_sc_id = $this->input->post('from_sc_id'); 
		$to_sc_id = $this->input->post('to_sc_id');
		
		$order_parts = $this->mdl_badparts_order_parts->orderparts($badparts_order_id);
		
		
		
		$data = array(
					'badparts_order_id'=> $badparts_order_id,
					'from_sc_id' =>$from_sc_id,
					'to_sc_id' => $to_sc_id,
					'order_parts'=>$order_parts
						);
		$this->load->view('badparts/orders/createchalan',$data);
		
		}
		
		
	function savetransit(){
		
		$this->load->model(array('badparts/mdl_badparts_transit_details','badparts/mdl_badparts_order_parts'));
		//print_r($this->input->post()); die();
		$from_sc_id = $this->input->post('from_sc_id');
		$chalan_number = $this->mdl_badparts_transit_details->getChalanNumber($from_sc_id,$this->input->post('badparts_order_id'));
		//print_r($chalan_number);
		//die();
		
		$transit_detail_id = $this->input->post('transit_detail_id');
		$data['badparts_chalan_number'] = $chalan_number->chalan_number;
		$data['badparts_order_id']= $this->input->post('badparts_order_id');
		$data['courior_date'] = $this->input->post('courior_date');
		$data['courior_number']=$this->input->post('courior_number');
		$data['box_number']=$this->input->post('num_box');
		$data['vehicle_number']=$this->input->post('vehicle_num');
		$data['transit_number']=$this->input->post('transit_num');
		
		if (empty($transit_detail_id)){
			$data['badparts_transit_detail_created_by']=$this->session->userdata('user_id');
			$data['badparts_transit_detail_created_ts']=date('Y-m-d H:i:s');
			$this->mdl_badparts_transit_details->save($data);
			$id = $this->db->insert_id();
			print_r($id);
		}else{
			$data['badparts_transit_detail_modified_by']= $this->session->userdata('user_id');
			$data['badparts_transit_detail_modified_ts']=date('Y-m-d H:i:s');
			$this->mdl_badparts_transit_details->save($data,$transit_detail_id);
			$id = $this->db->insert_id();
			print_r($id);
		}
	
		}	
		
	
	
	function checkstock(){
		$this->load->model('badparts/mdl_returnparts');
		$quantity = $this->mdl_returnparts->checkstock();
		print_r($quantity->part_quantity);
		die();
		
		}
		
	function savePartialParts(){
		
		$from_sc_id = $this->input->post('from_sc_id');
		$to_sc_id = $this->input->post('to_sc_id');
		$badparts_order_part_id = $this->input->post('badparts_order_part_id');
		$entered_quantity = $this->input->post('entered_quantity');
		$part_number = $this->input->post('part_number');
		$transit_id = $this->input->post('transit_id');
		
		$this->load->model(array('badparts/mdl_badparts_order_parts_details','badparts/mdl_returnparts'));
		
		$data = array(
					  'badparts_order_part_id'=>$badparts_order_part_id,
					  'part_quantity'=>$entered_quantity,
					  'badparts_transit_id'=> $transit_id
					   );
		
		$this->mdl_badparts_order_parts_details->save($data);
		$this->mdl_returnparts->addPartTransit($part_number,$entered_quantity,$from_sc_id);
		}

	function showchalan(){
		$this->redir->set_last_index();
		$this->load->model(array('badparts/mdl_badparts_transit_details'));
		$badparts_order_id = $this->input->post('order_id');
		$list = $this->mdl_badparts_transit_details->getChalanByOrder($badparts_order_id);
		$data = array(
					'list' =>$list,
					'badparts_order_id'=>$badparts_order_id
						);
		$this->load->view('badparts/orders/chalanlist',$data);
		
	}	
	
	
	function receivechalan(){
		$this->redir->set_last_index();
		$this->load->model(array('badparts/mdl_badparts_transit_details',
									'badparts/mdl_badparts_order_parts',
									'parts/mdl_parts',
									'badparts/mdl_badparts_order'
									));
		$transit_id= $this->input->post('badparts_transit_detail_id');
		$badparts_order_id = $this->input->post('badparts_order_id');
		$chalan_details = $this->mdl_badparts_transit_details->getChalanDetails($transit_id);
		$order_parts = $this->mdl_badparts_order_parts->orderparts($badparts_order_id);
		$data = array(
					'transit_details'=>$chalan_details,
					'orderparts'=>$order_parts
					);
		$this->load->view('badparts/orders/receivechalan', $data);
		
		
	}
		
	
	function receivePartialPart(){
		
		$from_sc_id= $this->input->post('from_sc_id');
		$to_sc_id = $this->input->post('to_sc_id');
		$part_number = $this->input->post('part_number');
		$badparts_order_part_detail_id =$this->input->post('badparts_order_part_detail_id');
		$dispatched_chalan_quantity =$this->input->post('dispatched_chalan_quantity');
		$dispatched_entered_quantity =$this->input->post('dispatched_entered_quantity');
		$badparts_order_part_id =$this->input->post('badparts_order_part_id');
		
		$this->load->model(array('badparts/mdl_returnparts',
								'badparts/mdl_badparts_order_parts_details'
							));
		
		
		
		$part_transit_update = $this->mdl_badparts_order_parts_details->getOrderPartDetailTransit($badparts_order_part_detail_id);
		
		
		
		
		
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
		
		// Remove Transit part from 'From Store'
				$this->mdl_returnparts->removePartTransit($from_sc_id,$part_number,$dispatched_entered_quantity);
		// Add part to 'To Store'
				$this->mdl_returnparts->addPart($to_sc_id,$part_number,$dispatched_entered_quantity);
				
		
				
				
				
				
							
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
						  'badparts_order_part_id'=>$badparts_order_part_id,
						  'order_part_status'=>1,
						  'differance'=>$diff,
						  'badparts_order_parts_details_last_mod_by'=>$this->session->userdata('user_id'),
						  'badparts_order_parts_details_last_mod_ts'=>date('y-m-d H:i:s')
						   );
			
			
			$this->mdl_badparts_order_parts_details->save($datas,$badparts_order_part_detail_id);
					
	}

	function partialordercard(){
		
		$this->redir->set_last_index();
		$this->load->model(array(
							'badparts/mdl_badparts_order',
							'badparts/mdl_badparts_order_parts',
							'badparts/mdl_badparts_transit_details',
							'parts/mdl_parts'
						));
		$badparts_order_id = $this->input->post('order_id');
		$transit_id = $this->input->post('transit_id');
		
		$order_details = $this->mdl_badparts_order->detailorder($badparts_order_id);
		$order_parts = $this->mdl_badparts_order_parts->orderparts($badparts_order_id);
		$transit_details = $this->mdl_badparts_transit_details->getChalanDetails($transit_id);
		$data = array(
						'detailorders'=>$order_details,
						'order_parts'=>$order_parts,
						'transit_details'=>$transit_details
						);
		$this->load->view('badparts/orders/orderchalan',$data);
	}
	
	
}
?>