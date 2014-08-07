<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Orders extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_orders';
		$this->primary_key		=	'sst_orders.order_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'order_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function save($db_array,$order_id){
		//print_r($db_array);
		//die();
		$requested_sc_id = $this->mdl_mcb_data->get('main_service_center');
		
		$call_id = $db_array[0]->call_id;
		$params = array(
						'select'=>'count(order_id) as cnt',
						'where'=>array('order_id ='=>$order_id,'order_status'=>'0')
						);
		$order = $this->get($params);
		if($order[0]->cnt==0){
			$data = array(
				  'call_id'=>'',
				  'order_dt'=>date("Y-m-d"),
				  'requesting_sc_id'=>$db_array[0]->sc_id,
				  'requested_sc_id'=>$requested_sc_id,
				  'order_status'=>0,
				  'engineer_id'=>'',
				  'order_created_by'=>$this->session->userdata('user_id'),
				  'order_created_ts'=>date('Y-m-d H:i:s')
				  );
			$params = array(
						'select'=>'max(order_id) as oid'
						);
			$arr = $this->get($params);
			
			$data['order_number']='0-'.($arr[0]->oid+1);
			parent::save($data);
			$order_id = $this->db->insert_id();
		}else{
			$data = array(
						 // 'engineer_id'=>$db_array[0]->engineer_id,
						  'order_last_mod_ts'=>date("Y-m-d H:i:s"),
						  'order_last_mod_by'=>$this->session->userdata('user_id')
						  );
			parent::save($data,$order_id);
		}
		if($order_id){
			foreach($db_array as $parts){
				 	$data_orders = array(
			 	 						'part_number'=>$parts->part_number,
			 	 						'part_quantity'=>$parts->part_quantity,
			 	 						'order_id'=>$order_id,
										'call_id'=>$parts->call_id,
										'engineer_id'=>$parts->engineer_id,
										'calls_orders_id'=>$parts->calls_orders_id
										
										//'company_id'=>$parts->company_id
			 	 						);
			 	 if($parts->order_part_id==0){
			 	 	$data_orders['order_part_created_by'] = $this->session->userdata('user_id');
			 	 	$data_orders['order_part_created_ts'] = date('Y-m-d H:i:s');
			 	 	$this->mdl_order_parts->save($data_orders);
			 	 }else{
			 	 	$data_orders['order_part_last_mod_by'] = $this->session->userdata('user_id');
			 	 	$data_orders['order_part_last_mod_ts'] = date('Y-m-d H:i:s');
			 	 	$this->mdl_order_parts->save($data_orders,$parts->order_part_id);
			 	 }
			}
		}
	}
	function save1($order_id){
		$requested_sc_id = 1;
		$call_id = $this->input->post('call_id');
		$params = array(
						'select'=>'count(order_id) as cnt',
						'where'=>array('order_id ='=>$order_id,'order_status'=>'1')
						);
		$order = $this->get($params);
		if($order[0]->cnt==0){
			$data = array(
				  'call_id'=>$call_id,
				  'order_dt'=>date("Y-m-d"),
				  'requesting_sc_id'=>$this->session->userdata('sc_id'),
				  'requested_sc_id'=>$requested_sc_id,
				  'order_status'=>0,
				  'order_created_by'=>$this->session->userdata('user_id'),
				  'order_created_ts'=>date('Y-m-d H:i:s')
				  );
			$params = array(
						'select'=>'max(order_id) as oid'
						);
			$arr = $this->get($params);
			
			$data['order_number']='0-'.($arr[0]->oid+1);
			 if(parent::save($data)){
				 $order_id = $this->db->insert_id();
			 	return $this->mdl_order_parts->save($order_id);
			 }
		}else{
			$data = array(
						  'order_last_mod_ts'=>date("Y-m-d H:i:s"),
						  'order_last_mod_by'=>$this->session->userdata('user_id')
						  );
			if(parent::save($data,$order_id)){
					return $this->mdl_order_parts->save($order_id);
			}
		}
	}
	function getOrderDetailsByCall($call_id)
	{
		$this->db->select('order_id,call_id,order_number,order_dt,requesting_sc_id,requested_sc_id');
		$this->db->from($this->table_name);
		$this->db->where('call_id =',$call_id);
		$this->db->where('order_status <=',1);
		$result = $this->db->get();
		$order = $result->row();
		if($result->num_rows()==0){
			$order = new stdClass();
			$order->order_id = 0;
			$order->call_id = 0;
			$order->order_number = 0;
			$order->requesting_sc_id = 0;
			$order->requested_sc_id = 0;
		}
		return $order;
	}
	function thisorder($id){
		
		$this->db->select('order_id,order_number,order_dt,call_id,requested_sc_id,order_status');
		$this->db->from($this->table_name);
		$this->db->where('order_id =',$id);
		$result= $this->db->get();
		$result= $result->row();
		return $result;
		
		}
	// 
	function orderlist($page){
		$searchtxt = $this->input->post('searchtxt');
		$datefrom = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$status= $this->input->post('order_status');
		$sc_id = $this->input->post('sc_id');
		
		
		
		if($sc_id){
			$this->db->where('('.'o.requested_sc_id = '.$sc_id.' OR o.requesting_sc_id = '.$sc_id.')');
			
			
			//$this->db->where('o.requesting_sc_id =',$sc_id);
			}
		
		if ($status){
			$this->db->where('o.order_status =',$status);
			}
			if ($status != '' && $status == 0 ){
			$this->db->where('o.order_status = 0');
			}
		if($searchtxt){
			$this->db->like('o.order_number',$searchtxt);
		}
		if($datefrom)
		{			
			$this->db->where('o.order_dt >=', date("Y-m-d",date_to_timestamp($datefrom)));	
		}
		
		if($todate)
		{			
			$this->db->where('o.order_dt <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if ($this->session->userdata('usergroup_id')!=1 && $this->session->userdata('usergroup_id')!= 2  && $this->session->userdata('usergroup_id')!=6 ){		
			
			$this->db->where('('.'o.requested_sc_id = '.$this->session->userdata('sc_id').' OR o.requesting_sc_id = '.$this->session->userdata('sc_id').')');
			//$this->db->where('o.requesting_sc_id = '.$this->session->userdata('sc_id'));
		}
		
		$this->db->select('o.order_id,o.order_number,o.order_dt,o.call_id,c.call_uid,o.order_status,o.order_dt,sc.sc_name,e.engineer_name');
		$this->db->from($this->table_name.' AS o');
		$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id=o.call_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc' ,'sc.sc_id=o.requested_sc_id','left');
		$this->db->join($this->mdl_engineers->table_name.' AS e', 'e.engineer_id = o.engineer_id','left');
		$this->db->order_by('o.order_status ASC,o.order_dt DESC,o.order_created_ts DESC');
		if(isset($page['limit'])){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
		}
	
		$result = $this->db->get();
			//echo $this->db->last_query(); 
		if ($status){
			$this->db->where('o.order_status =',$status);
			}
		if ($status != '' && $status == 0 ){
			$this->db->where('o.order_status = 0');
			}
		if($sc_id){
			$this->db->where('o.requesting_sc_id =',$sc_id);
			
			}
		if($searchtxt){
			$this->db->like('o.order_number',$searchtxt);
		}
		if($datefrom)
		{			
			$this->db->where('o.order_dt >=', date("Y-m-d",date_to_timestamp($datefrom)));	
		}
		
		if($todate)
		{			
			$this->db->where('o.order_dt <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		//if ($this->session->userdata('global_admin')!=1){		
		if ($this->session->userdata('usergroup_id')!=1 && $this->session->userdata('usergroup_id')!= 2  && $this->session->userdata('usergroup_id')!=6 ){	
			$this->db->where('o.requested_sc_id = '.$this->session->userdata('sc_id').' or o.requesting_sc_id = '.$this->session->userdata('sc_id'));
		}
		$this->db->select('o.order_id');
		$this->db->from($this->table_name.' AS o');
		$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id=o.call_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc' ,'sc.sc_id=o.requested_sc_id','left');
		$this->db->join($this->mdl_engineers->table_name.' AS e', 'e.engineer_id = o.engineer_id','left');
		$result_total = $this->db->get();
		
		$orders['list'] = $result->result();
		$orders['total'] = $result_total->num_rows();
		return $orders;
	}
	
	// 
	public function engineerid($call_id){
		$this->db->select('engineer_id');
		$this->db->from($this->mdl_callcenter->table_name);
		$this->db->where('call_id =',$call_id);
		$engineer_id=$this->db->get();
		return $engineer_id->row();
	}
	function updatestatus($db_array,$order_id){
		return parent::save($db_array,$order_id);
	}
	function checkPendingOrderByCall($call_id){
		$this->db->select('count(o.order_id) AS cnt');
		$this->db->from($this->table_name.' AS o');
		$this->db->join($this->mdl_order_parts->table_name.' AS op','op.order_id=o.order_id','inner');
		$this->db->where('o.call_id =',$call_id);
		$this->db->where('o.order_status <',1);
		$this->db->order_by('o.order_id ASC, order_part_created_ts DESC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			$total  = $arr->cnt;
		}else{
			$total=0;
		}
		return $total;
	}
	function detailorder($id){
		$this->db->select('order_id,order_number,order_dt,o.call_id,order_status,sc_name,engineer_name,o.order_created_ts,o.order_last_mod_ts,e.engineer_id,o.requested_sc_id,o.requesting_sc_id,o.order_remarks,o.order_type');
		$this->db->from($this->table_name.' As o');
		$this->db->where('o.order_id =',$id);
		$this->db->join($this->mdl_servicecenters->table_name.' AS s' ,'o.requested_sc_id = s.sc_id','left');
		$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id = o.call_id','left');
		$this->db->join($this->mdl_engineers->table_name.' AS e', 'e.engineer_id = c.engineer_id','left');
		$result=$this->db->get();
		$order = $result->row();
		if($result->num_rows()==0){
			$order = new stdClass();
			$order->order_id = 0;
			$order->call_id = 0;
			$order->order_status = 0;
			$order->order_number = 0;
			$order->requesting_sc_id = 0;
			$order->requested_sc_id = 0;
			$order->order_remarks = '';
			$order->requesting_sc_id = 0;
			$order->order_type = 0;
			$order->order_created_by = 0;
			$order->order_last_mod_ts = 0;
			$order->order_created_ts = '';
		}
		return $order;	
		}
		
		
		
	function printorder($id){
		$this->db->select('order_id,order_number,order_dt,o.call_id,order_status,sc_name,sc_address,engineer_name,o.order_created_ts,o.order_last_mod_ts,e.engineer_id,o.requested_sc_id,o.requesting_sc_id,o.order_remarks,o.order_type');
		$this->db->from($this->table_name.' As o');
		$this->db->where('o.order_id =',$id);
		$this->db->join($this->mdl_servicecenters->table_name.' AS s' ,'o.requesting_sc_id = s.sc_id','left');
		$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id = o.call_id','left');
		$this->db->join($this->mdl_engineers->table_name.' AS e', 'e.engineer_id = c.engineer_id','left');
		$result=$this->db->get();
		$order = $result->row();
		return $order;
		
		}
		
		
	function saveOrder(){
		
		$order_id = $this->input->post('order_id');
		$order_part_id_arr = array();
		$part_number_arr = array();
		$part_quantity_arr = array();
		$company_arr = array();
		$company_arr = $this->input->post('comp');
		$order_part_id_arr = $this->input->post('order_part_id');
		$part_number_arr = $this->input->post('pnum');
		$part_quantity_arr = $this->input->post('pqty');
		
		$params = array(
						'select'=>'max(order_id) as oid'
						);
		$arr = $this->get($params);
		$data = array(
					
					'requesting_sc_id'=>$this->input->post('requesting_sc_id'),
					'requested_sc_id'=>$this->input->post('requested_sc_id'),
					'order_remarks'=>$this->input->post('order_remarks'),
					'order_type'=>$this->input->post('order_type')
		);
		
	
				if(empty($part_number_arr)){
				
						$this->session->set_flashdata('custom_warning', $this->lang->line('parts_not_added'));
					redirect('orders/editorder/'.$order_id);
				}
				
		
		
		
		
			
	
		if ($this->session->userdata('usergroup_id') != 1 && $this->session->userdata('usergroup_id') != 2 && $this->session->userdata('usergroup_id') != 6 && $this->input->post('status') == 4  && $this->session->userdata('sts')==2){
			 
			redirect('orders/editorder/'.$order_id);
			}
	
		
		
		if ($this->session->userdata('usergroup_id') != 1 && $this->session->userdata('usergroup_id') != 2 && $this->session->userdata('usergroup_id') != 6 && $this->input->post('status') == 0 && $this->session->userdata('sc_id') == $this->input->post('requesting_sc_id') && $this->session->userdata('sts')==1){
			 
			redirect('orders/editorder/'.$order_id);
			}
	
		
		if($this->input->post('status')==2 || $this->input->post('status')==3){
				
				
				
			$j=0;
			$redirect = 0;
			foreach ($part_number_arr as $part){
				if(empty($company_arr[$j])){
				$redirect = 1;
						$this->session->set_flashdata('custom_warning', $this->lang->line('company_not_assigned'));
					redirect('orders/editorder/'.$order_id);
				}
				
				$j++;
			}
			
			
			if ($this->session->userdata('usergroup_id')==4){
				if ($this->input->post('status')==2 && $this->session->userdata('sc_id') != $this->input->post('requested_sc_id'))
				{redirect('orders/editorder/'.$order_id);}
				
				if ($this->input->post('status')==3 && $this->session->userdata('on_transit')== 'false' && $this->session->userdata('sc_id') != $this->input->post('requesting_sc_id'))
				{redirect('orders/editorder/'.$order_id);}
				
		}
	
		if ( $this->input->post('status')==3 && $this->session->userdata('sc_id') != $this->input->post('requesting_sc_id'))
			{redirect('orders/editorder/'.$order_id);}
		if ($this->session->userdata('on_transit')== 'true' && $this->input->post('status')==2 )
			{redirect('orders/editorder/'.$order_id);}
		if ($this->input->post('status') ==3 && $this->session->userdata('on_transit') != 'true'){redirect('orders/editorder/'.$order_id);}
			
			$this->load->model('stocks/mdl_parts_stocks');
			
			$data['order_status'] = $this->input->post('status');
		
		$j=0;
			$redirect = 0;
			foreach ($part_number_arr as $part){
				if($company_arr[$j]){
				$company_id = $this->mdl_company->getcompanyid($company_arr[$j]);
				}
				else
				{
					$company_id = '';
					}
				$check_stock = $this->mdl_parts_stocks->checkPartsStock($this->input->post('requested_sc_id'),$part,$company_id);	
				if($check_stock->stock_quantity<$part_quantity_arr[$j]){
					$redirect = 1;
						$this->session->set_flashdata('custom_warning', $this->lang->line('stock_not_unavailable').'<a onclick="seeDetails();" class="infobtn">   Click to see details</a>');
					redirect('orders/editorder/'.$order_id);
				}
				$j++;
			}
		}
		
		
	if($order_id==0){
			$data['order_dt'] = date('Y-m-d');
			$data['requesting_sc_id'] = $this->input->post('requesting_sc_id');
			$data['order_number']='0-'.($arr[0]->oid+1);
			$data['order_status'] = 0;
			$data["order_created_ts"]=date("Y-m-d H-i-s");
			$data["order_created_by"]=$this->session->userdata('user_id');
			parent::save($data);
			$order_id = $this->db->insert_id();
			$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
		}else{
			
			if ($this->session->userdata('usergroup_id')==4 && $this->input->post('status') != 2 && $this->input->post('status') != 3){
				if ($this->input->post('status') != 0 && $this->input->post('status') != 4 && $this->input->post('status') != 5 && $this->session->userdata('sc_id') != $this->input->post('requested_sc_id'))
				{redirect('orders/editorder/'.$order_id);}
				
				if ( $this->session->userdata('sc_id') != $this->input->post('requesting_sc_id') && $this->input->post('status') != 1)
				{redirect('orders/editorder/'.$order_id);}
				if ( $this->session->userdata('sc_id') == $this->input->post('requesting_sc_id') && $this->input->post('status') == 4 && $this->session->userdata('order_status')==1)
				{redirect('orders/editorder/'.$order_id);}
				
		}
			
			$data['order_status'] = $this->input->post('status');
			$data["order_last_mod_ts"]= date("Y-m-d H:i:s");
			$data["order_last_mod_by"]=$this->session->userdata('user_id');
			parent::save($data,$order_id);
			$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
		}
	
		$i=0;
		if(is_array($order_part_id_arr) && (int)$order_id>0){
			foreach($order_part_id_arr as $order_part_id){
				$part_number = $part_number_arr[$i];
				$part_quantity = $part_quantity_arr[$i];
				if($company_arr[$i]){
				$company_id = $this->mdl_company->getcompanyid($company_arr[$i]);
				}
				else
				{
					$company_id = '';
					}
					
				$order_data = array(
									'company_id'=>$company_id,
									'order_id'=> $order_id,
									'part_number'=>$part_number,
									'part_quantity'=>$part_quantity
									);
				if((int)$order_part_id==0){
					$order_data["order_part_created_ts"]=date("Y-m-d H:i:s");
					$order_data["order_part_created_by"]=$this->session->userdata('user_id');
			
					$this->mdl_order_details->save($order_data);
					$order_part_id = $this->db->insert_id();
				}else{
					$order_data["order_part_last_mod_ts"]= date("Y-m-d H:i:s");
					$order_data["order_part_last_mod_by"]= $this->session->userdata('user_id');
					$this->mdl_order_details->save($order_data,$order_part_id);
				}
				
				
				//update inventory
				if($this->input->post('requested_sc_id')==$this->input->post('requesting_sc_id')){
					redirect('orders/editorder/'.$order_id);
				}
				
				if($this->input->post('status')==2){
					$j=0;
			$redirect = 0;
			foreach ($part_number_arr as $part){
				if(empty($company_arr[$j])){
				$redirect = 1;
						$this->session->set_flashdata('custom_warning', $this->lang->line('company_not_assigned'));
					redirect('orders/editorder/'.$order_id);
				}
				
				$j++;
			}
					$this->load->model(array('stocks/mdl_stocks','stocks/mdl_parts_stocks'));
					$stockdata['stock_dt'] = date('Y-m-d');
					$stockdata['stock_tm'] = date('H:i:s');
					$stockdata['stock_created_by'] = $this->session->userdata('user_id');
					$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
					$stockdata['part_number'] = $part_number;
					$stockdata['company_id']= $company_id;
					
					//First for stock out for requested service ceter
					$stockdata['stock_quantity_in'] = 0;
					$stockdata['stock_quantity_out'] = $part_quantity;
					$stockdata['sc_id'] = $this->input->post('requested_sc_id');
					//$this->mdl_stocks->stockoutUpdate($stockdata,'stockout',$order_part_id);
					$this->mdl_parts_stocks->updatePartsInTransit($stockdata);
					// ADD Parts in Transit
					 
				//	$this->mdl_parts_stocks->updatePartsInTransit($stockdata);
					
					//Second for stock in for requesting Store
					//$stockdata['sc_id'] = $this->input->post('requesting_sc_id');
					//$stockdata['stock_quantity_in'] = $part_quantity;
					//$stockdata['stock_quantity_out'] = 0;
					//$this->mdl_stocks->stockinUpdate($stockdata,'stockin',$order_part_id);
					
					
				}
				if($this->input->post('status')==4 || $this->input->post('status')==1 ||$this->input->post('status')==5 ||$this->input->post('status')==0){ 
				
				    $this->load->model(array('stocks/mdl_stocks','stocks/mdl_parts_stocks'));
					$stockdata['stock_dt'] = date('Y-m-d');
					$stockdata['stock_tm'] = date('H:i:s');
					$stockdata['stock_created_by'] = $this->session->userdata('user_id');
					$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
					$stockdata['part_number'] = $part_number;
					$stockdata['company_id']= $company_id;
					//First for stock out for requested service ceter
					$stockdata['stock_quantity_in'] = 0;
					$stockdata['stock_quantity_out'] = $part_quantity;
					$stockdata['sc_id'] = $this->input->post('requested_sc_id');
					//$stockdata['company_id']= $company_id;
					//$this->mdl_stocks->stockoutUpdate($stockdata,'stockout',$order_part_id);
				
					// ADD Parts in Transit
					 if ($this->session->userdata('on_transit') == 'true'){
						 	$this->mdl_parts_stocks->removeTransitPart($stockdata);
					//$this->mdl_parts_stocks->updatePartsInTransit($stockdata);
					 }
					//Second for stock in for requesting Store 
					//$stockdata['sc_id'] = $this->input->post('requesting_sc_id');
					//$stockdata['stock_quantity_in'] = $part_quantity;
					//$stockdata['stock_quantity_out'] = 0;
					//$this->mdl_stocks->stockinUpdate($stockdata,'stockin',$order_part_id);

				}
				
				if($this->input->post('status')==3){
					$j=0;
			$redirect = 0;
			foreach ($part_number_arr as $part){
				if(empty($company_arr[$j])){
				$redirect = 1;
						$this->session->set_flashdata('custom_warning', $this->lang->line('company_not_assigned'));
					redirect('orders/editorder/'.$order_id);
				}
				
				$j++;
			}
					$this->load->model(array('stocks/mdl_stocks','stocks/mdl_parts_stocks'));
					$stockdata['stock_dt'] = date('Y-m-d');
					$stockdata['stock_tm'] = date('H:i:s');
					$stockdata['stock_created_by'] = $this->session->userdata('user_id');
					$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
					$stockdata['part_number'] = $part_number;
					$stockdata['company_id']= $company_id;
					
					//First for stock out for requested service ceter
					$stockdata['stock_quantity_in'] = 0;
					$stockdata['stock_quantity_out'] = $part_quantity;
					$stockdata['sc_id'] = $this->input->post('requested_sc_id');
					$this->mdl_stocks->stockoutUpdate($stockdata,'stockout',$order_part_id);
					
					// REMOVE Parts from Transit
					if ($this->session->userdata('on_transit') == 'true'){
					$this->mdl_parts_stocks->removeTransitPart($stockdata);
					}
					
					
					//Second for stock in for requesting Store
					$stockdata['sc_id'] = $this->input->post('requesting_sc_id');
					$stockdata['stock_quantity_in'] = $part_quantity;
					$stockdata['stock_quantity_out'] = 0;
					$this->mdl_stocks->stockinUpdate($stockdata,'stockin',$order_part_id);
				}
				$i++;
			}
		}
		return $order_id;
	}
	
	
	
}
?>