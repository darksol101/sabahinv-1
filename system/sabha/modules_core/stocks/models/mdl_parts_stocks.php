<?php defined('BASEPATH') or die ('Direct access script is not allowed');
class Mdl_Parts_stocks extends MY_Model{
	public function __construct(){
		parent::__construct();
		$this->table_name = 'sst_parts_stocks';
		$this->primary_key	=	'sst_parts_stocks.parts_stock_id';
		$this->select_fields = '
		SQL_CALC_FOUND_ROWS *';

		$this->order_by = 'parts_stock_d';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function updatePartsBalance($sc_id,$part_id,$company_id,$quantity){
		
		$this->db->select('parts_stock_id,sc_id,part_id,stock_quantity,company_id');
		$this->db->from($this->table_name);
		$this->db->where('sc_id =',$sc_id);
		$this->db->where('part_id =',$part_id);
		$this->db->where('company_id =',$company_id);
		$result = $this->db->get();
		$part_stocks = $result->row();
		$success = false;
		if($result->num_rows()==0){
			//insert 
			$data=array(
					  'company_id'=>$company_id,	
					  'sc_id'=>$sc_id,
					  'part_id'=>$part_id,
					  'stock_quantity'=>$quantity,
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data)){
				$success = true;
			}
		}else{
			//update
			$data=array(
						'company_id'=>$company_id,
					  'sc_id'=>$sc_id,
					  'part_id'=>$part_id,
					  'stock_quantity'=>$quantity,
					 'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data,$part_stocks->parts_stock_id)){
				$success = true;
			}
		}
		/*$params=array(
					  'sc_id'=>$sc_id,
					  'part_number'=>$part_number,
					  'stock_quantity'=>$quantity,
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
		
		$sql = 'INSERT INTO '.$this->table_name.' (sc_id,part_number,stock_quantity,parts_stock_created_by,parts_stock_created_ts) VALUES ("'.$sc_id.'","'.$part_number.'","'.$quantity.'","'.$this->session->userdata('user_id').'","'.date('Y-m-d H:i:s').'")
		ON DUPLICATE KEY 
		UPDATE  stock_quantity="'.$quantity.'",parts_stock_last_mod_by="'.$this->session->userdata('user_id').'",parts_stock_last_mod_ts="'.date('Y-m-d H:i:s').'"';
		$success = $this->db->query($sql);*/
		// 
		//$success=parent::save($params,$part_number);  
		return $success;
	}
	
	
	
	
	function getStocksList($page,$order_level){
		$this->load->model(array('company/mdl_company','parts/mdl_parts','partbin/mdl_partbin'));
		$sc_id = $this->input->post('sc_id');
		$searchtxt = $this->input->post('searchtxt');
		$company_id = $this->input->post('company');
		if($sc_id){
			$this->db->where('ps.sc_id =',$sc_id);
		}
		if($searchtxt){
			$this->db->like("concat(ps.part_id,pd.part_number,pd.part_desc)",$searchtxt);
			//$this->db->like('ps.part_id,pd.part_number',$searchtxt);
		}
		if($company_id){
			$this->db->where('ps.company_id',$company_id);
		}
		$this->db->select('pd.order_level,pd.order_level_max,ps.part_id,pd.part_number,ps.stock_quantity,sc.sc_name,ps.sc_id,pd.part_desc,ps.parts_in_transit,c.company_title,ps.company_id,ps.allocated_quantity,ps.parts_stock_created_ts');
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_parts->table_name.' AS pd','ps.part_id=pd.part_id');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=ps.sc_id');
		$this->db->join($this->mdl_company->table_name.' as c','ps.company_id = c.company_id','left');
		$this->db->order_by('ps.stock_quantity DESC');
		
		if($order_level){
			$this->db->where('pd.order_level > ps.stock_quantity');
		}

		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();


		//echo $this->db->last_query();
		$stocklist['list'] = $result->result();
		
		if($sc_id){
			$this->db->where('ps.sc_id =',$sc_id);
		}
		if($searchtxt){
			$this->db->like("concat(ps.part_id,pd.part_number,pd.part_desc)",$searchtxt);
			//$this->db->like('ps.part_id,pd.part_number',$searchtxt);
		}
		if($company_id){
			$this->db->where('ps.company_id',$company_id);
		}
		$this->db->select('pd.order_level,pd.order_level_max, ps.part_id,pd.part_number,ps.stock_quantity,sc.sc_name,ps.sc_id,pd.part_desc,ps.parts_in_transit,c.company_title,ps.company_id,ps.allocated_quantity,ps.parts_stock_created_ts');
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_parts->table_name.' AS pd','ps.part_id=pd.part_id');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=ps.sc_id');
		$this->db->join($this->mdl_company->table_name.' as c','ps.company_id = c.company_id','left');
		$this->db->order_by('ps.stock_quantity DESC');
		if($order_level==true){
			$this->db->where('pd.order_level > ps.stock_quantity');
		}
		$result_total = $this->db->get();
		$stocklist['total'] = $result_total->num_rows();
	
		/*dump($this->db->last_query());
		die(); */
		return $stocklist;
	}
	
	function getStocksdownload(){
		$this->load->model(array('company/mdl_company','parts/mdl_parts'));
		$sc_id = $this->session->userdata('sc_id_stock');
		$searchtxt = $this->session->userdata('searchtxt_stock');
		$company_id = $this->session->userdata('company_id_stock');
		$order_level = $this->session->userdata('orderlevel');
		
		if($sc_id){
			$this->db->where('ps.sc_id =',$sc_id);
		}
		if($searchtxt){
			$this->db->like('ps.part_id,pd.part_number',$searchtxt);
		}
		if($company_id){
			$this->db->where('ps.company_id',$company_id);
		}

		if($order_level==true){
			$this->db->select('sc.sc_name,ps.part_id,pd.part_number,c.company_title,pd.part_desc,ps.stock_quantity,ps.parts_in_transit,ps.allocated_quantity,ps.sc_id,ps.company_id,pd.order_level,pd.order_level_max');
			$where ='pd.order_level > ps.stock_quantity';
		}else{
			$this->db->select('sc.sc_name,ps.part_id,pd.part_number,c.company_title,pd.part_desc,ps.stock_quantity,ps.parts_in_transit,ps.allocated_quantity,ps.sc_id,ps.company_id');
		}
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_parts->table_name.' AS pd','ps.part_id=pd.part_id');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=ps.sc_id');
		$this->db->join($this->mdl_company->table_name.' as c','ps.company_id = c.company_id');
		
		if(isset($where)){
			$this->db->where($where);
		}

		$this->db->order_by('ps.parts_stock_created_ts ASC');
		
		$result = $this->db->get();
		

		//dump($result->result()); die();
		
		//$result = $this->db->get();
		
		return $result;
	}
	


	
	
	
	function checkPartsStock($sc_id,$part_number,$company_id){
		$parts = new stdClass;
		$parts->stock_quantity = 0;
		$parts->parts_available = false;
		
		$this->db->select('ps.stock_quantity');
		$this->db->from($this->table_name.' AS ps');
		$this->db->where('ps.sc_id =',$sc_id);
		$this->db->where('ps.`part_id` =',$part_number);
		$this->db->where('ps.company_id =',$company_id);
		$result = $this->db->get();
		//echo $this->db->last_query();
		$arr = $result->row();
		if($result->num_rows()>0){
			
			$parts->stock_quantity = $arr->stock_quantity;
			$parts->parts_available = true;
		}
		return $parts;
	}
	
	function updatePartsInTransit($data){
	
		$this->db->select('parts_stock_id,sc_id,part_id,company_id,stock_quantity,parts_in_transit');
		$this->db->from($this->table_name);
		$this->db->where('sc_id =',$data['sc_id']);
		$this->db->where('part_id =',$data['part_id']);
		$this->db->where('company_id =',$data['company_id']);
		$result = $this->db->get();
		$part_stocks = $result->row();
	
		$success = false;
		if($result->num_rows()==0){
			//insert 
			$data=array(
					  'sc_id'=>$data['sc_id'],
					  'part_id'=>$data['part_id'],
					  'company_id'=>$data['company_id'],
					  'stock_quantity'=>$part_stocks->stock_quantity,
					  'parts_in_transit'=>$data['stock_quantity_out'],
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data)){
				$success = true;
			}
		}else{
			//update
			$data=array(
					  'sc_id'=>$data['sc_id'],
					  'part_id'=>$data['part_id'],
					  'company_id' =>$data['company_id'],
					  'stock_quantity'=>$part_stocks->stock_quantity,
					   'parts_in_transit'=>$part_stocks->parts_in_transit+$data['stock_quantity_out'],
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data,$part_stocks->parts_stock_id)){
				$success = true;
			}
		}
		/*$params=array(
					  'sc_id'=>$sc_id,
					  'part_number'=>$part_number,
					  'stock_quantity'=>$quantity,
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
		
		$sql = 'INSERT INTO '.$this->table_name.' (sc_id,part_number,stock_quantity,parts_stock_created_by,parts_stock_created_ts) VALUES ("'.$sc_id.'","'.$part_number.'","'.$quantity.'","'.$this->session->userdata('user_id').'","'.date('Y-m-d H:i:s').'")
		ON DUPLICATE KEY 
		UPDATE  stock_quantity="'.$quantity.'",parts_stock_last_mod_by="'.$this->session->userdata('user_id').'",parts_stock_last_mod_ts="'.date('Y-m-d H:i:s').'"';
		$success = $this->db->query($sql);*/
		// 
		//$success=parent::save($params,$part_number);  
		return $success;
	}


	function removeTransitPart($data){


				//print_r($data);
		$this->db->select('parts_stock_id,sc_id,part_id,company_id,stock_quantity,parts_in_transit');
		$this->db->from($this->table_name);
		$this->db->where('sc_id =',$data['sc_id']);
		$this->db->where('part_id =',$data['part_id']);
		$this->db->where('company_id =',$data['company_id']);
		$result = $this->db->get();
		$part_stocks = $result->row();
		
		$success = false;
		if($result->num_rows()==0){
			//insert 
			$data=array(
					  'sc_id'=>$data['sc_id'],
					  'part_id'=>$data['part_id'],
					  'company_id'=>$data['company_id'],
					  'stock_quantity'=>$part_stocks->stock_quantity,
					  'parts_in_transit'=>$data['stock_quantity_out'],
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data)){
				$success = true;
			}
		}else{
			//update
			$data=array(
					  'sc_id'=>$data['sc_id'],
					  'part_id'=>$data['part_id'],
					  'company_id'=>$data['company_id'],
					  'stock_quantity'=>$part_stocks->stock_quantity,
					   'parts_in_transit'=>$part_stocks->parts_in_transit-$data['stock_quantity_out'],
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data,$part_stocks->parts_stock_id)){
				$success = true;
			}
		}
		/*$params=array(
					  'sc_id'=>$sc_id,
					  'part_number'=>$part_number,
					  'stock_quantity'=>$quantity,
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
		
		$sql = 'INSERT INTO '.$this->table_name.' (sc_id,part_number,stock_quantity,parts_stock_created_by,parts_stock_created_ts) VALUES ("'.$sc_id.'","'.$part_number.'","'.$quantity.'","'.$this->session->userdata('user_id').'","'.date('Y-m-d H:i:s').'")
		ON DUPLICATE KEY 
		UPDATE  stock_quantity="'.$quantity.'",parts_stock_last_mod_by="'.$this->session->userdata('user_id').'",parts_stock_last_mod_ts="'.date('Y-m-d H:i:s').'"';
		$success = $this->db->query($sql);*/
		// 
		//$success=parent::save($params,$part_number);  
		return $success;
	}


function getAvailableStocks($sc_id){
		$this->db->select('DISTINCT(ps.part_id,pp.part_number),ps.stock_quantity,pp.part_desc,c.company_title,');
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_parts->table_name.' AS pp','pp.part_id=ps.part_id','inner');
		$this->db->join($this->mdl_model_parts->table_name.' AS ppm','ppm.part_number=pd.part_number','inner');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = ps.company_id');
		$this->db->where('ps.sc_id =',$sc_id);
		$this->db->where('ps.stock_quantity >',0);
		$result = $this->db->get();
		$parts = $result->result();
		//echo $this->db->last_query();
		return $parts;
	}
	
		function checkRemainingPartsStock($sc_id,$part_number,$parts_used_id,$company_id){
		$parts = new stdClass;
		$parts->stock_quantity = 0;
		$parts->parts_available = false;
		$this->db->select('ps.stock_quantity');
		$this->db->from($this->table_name.' AS ps');
		$this->db->where('ps.sc_id =',$sc_id);
		$this->db->where('ps.part_id=',$part_number);
		$this->db->where('ps.company_id =',$company_id);
		$result = $this->db->get();
		$arr = $result->row();
		if($result->num_rows()>0){
			$parts->stock_quantity = $arr->stock_quantity;
			$parts->parts_available = true;
		}
		
		$this->db->select('pu.part_quantity');
		$this->db->from($this->mdl_parts_used->table_name.' as pu');
		$this->db->where('pu.parts_used_id =',$parts_used_id);
		$result = $this->db->get();
		$arr = $result->row();
		
		if($result->num_rows()>0){
			$parts->stock_quantity = $arr->part_quantity+$parts->stock_quantity;
			$parts->parts_available = true;
		}
		return $parts;
	}
	
	function getpartbyscid($sc_id){
		
		$this->db->select('p.part_number, al.stock_quantity, al.company_id, p.part_desc');
		$this->db->from($this->table_name.' AS al');
		$this->db->join($this->mdl_parts->table_name.' AS p','p.part_id = al.part_id');
		$this->db->where('al.sc_id =',$sc_id);
		$result = $this->db->get();
		return $result->result();
		
		
		}
		
		function getPartAssignmentOptions($sc_id)
			{
		
			$x = "select concat(pp.part_number, '  (', c.company_title , ')') as text, concat(pp.part_id, ':',p.company_id) as value from sst_parts_stocks as p inner join sst_company as c on p.company_id = c.company_id inner join sst_prod_parts as pp on pp.part_id=p.part_id where sc_id = ".$sc_id;
			
			$result = $this->db->query($x);
			
			$part_options = $result->result();
		 	

		 	return $part_options;
			}
			
			
	function getStocksListbyscid($sc_id){
		$this->load->model('company/mdl_company');
		$searchtxt = $this->input->post('searchtxt');
		$company_id = $this->input->post('company');
		if($sc_id){
			$this->db->where('ps.sc_id =',$sc_id);
		}
		if($searchtxt){
			$this->db->like('pd.part_number',$searchtxt);
		}
		if($company_id){
			$this->db->where('ps.company_id',$company_id);
		}
		$this->db->select('ps.part_id,pd.part_number,ps.stock_quantity,sc.sc_name,ps.sc_id,ps.parts_in_transit,c.company_title,ps.company_id');
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_parts->table_name.' as pd','pd.part_id=ps.part_id');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=ps.sc_id');
		$this->db->join($this->mdl_company->table_name.' as c','ps.company_id = c.company_id','left');
		$this->db->order_by('pd.part_number ASC');
		
		$result = $this->db->get();
		//echo $this->db->last_query(); 
		return $result->result();
	}
	
	function updateallocatedpart($data){
		$this->db->select('parts_stock_id,sc_id,part_id,company_id,stock_quantity,parts_in_transit,allocated_quantity');
		$this->db->from($this->table_name);
		$this->db->where('sc_id =',$data['sc_id']);
		$this->db->where('part_id =',$data['part_number']);
		;
		$this->db->where('company_id =',$data['company_id']);
		$result = $this->db->get();
		$part_stocks = $result->row();
		
		$data=array(
					  'sc_id'=>$data['sc_id'],
					  'part_id'=>$data['part_number'],
					  'company_id'=>$data['company_id'],
					  'stock_quantity'=>$part_stocks->stock_quantity - $data['allocated_quantity'],
					  'allocated_quantity'=>$part_stocks->allocated_quantity+$data['allocated_quantity'],
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data,$part_stocks->parts_stock_id)){
				$success = true;
			
		}
	
		return $success;
	}
	function removeallocatedpart($data){
		$this->db->select('parts_stock_id,sc_id,part_number,company_id,stock_quantity,parts_in_transit,allocated_quantity');
		$this->db->from($this->table_name);
		$this->db->where('sc_id =',$data['sc_id']);
		$this->db->where('part_number =',$data['part_number']);
		$this->db->where('company_id =',$data['company_id']);
		$result = $this->db->get();
		$part_stocks = $result->row();
			$data=array(
					  'sc_id'=>$data['sc_id'],
					  'part_number'=>$data['part_number'],
					  'company_id'=>$data['company_id'],
					  'stock_quantity'=>$part_stocks->stock_quantity + $data['quantity'],
					  'allocated_quantity'=>$part_stocks->allocated_quantity-$data['quantity'],
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data,$part_stocks->parts_stock_id)){
				$success = true;
			
		}
	
		return $success;
	}
	
	
	function getquantity($sc_id,$part_number,$company){
		
		$this->db->select('ps.stock_quantity');
		$this->db->from($this->table_name.' AS ps');
		$this->db->where('ps.part_id,pd.part_number =',$part_number);
		$this->db->where('ps.company_id =',$company);
		$this->db->where('ps.sc_id =',$sc_id);
		$result = $this->db->get();
		//print_r($result);
		//echo $this->db->last_query();
		$result = $result->row();
		return $result->stock_quantity ;
		
		}
		
		
		function updatecallused($data){
		$this->db->select('parts_stock_id,sc_id,part_number,company_id,stock_quantity,parts_in_transit,allocated_quantity');
		$this->db->from($this->table_name);
		$this->db->where('sc_id =',$data['sc_id']);
		$this->db->where('part_number =',$data['part_number']);
		$this->db->where('company_id =',$data['company_id']);
		$result = $this->db->get();
		$part_stocks = $result->row();
			$data=array(
					  'sc_id'=>$data['sc_id'],
					  'part_number'=>$data['part_number'],
					  'company_id'=>$data['company_id'],
					  'stock_quantity'=>$part_stocks->stock_quantity,
					  'allocated_quantity'=>$part_stocks->allocated_quantity-$data['stock_quantity_out'],
					  'parts_stock_created_by'=>$this->session->userdata('user_id'),
					  'parts_stock_created_ts'=>date('Y-m-d H:i:s')
					  );
			if($this->mdl_parts_stocks->save($data,$part_stocks->parts_stock_id)){
				$success = true;
			
		}
	
		return $success;
	}
	
	
	
	function getPartOptionsByScId($sc_id){
		
		$this->db->select('distinct(al.part_number) as text, al.part_number as value');
		$this->db->from($this->table_name.' AS al');
		$this->db->where('al.sc_id =',$sc_id);
		$result = $this->db->get();
		return $result->result();
		
		}
	function getCompanyByPart($sc_id,$part_number){
		$this->db->select('c.company_title as text, c.company_id as value');
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = ps.company_id','LEFT');
		$this->db->where('ps.part_id,pd.part_number =',$part_number);
		$this->db->where('ps.sc_id =',$sc_id);
		$result = $this->db->get();
		return $result->result();
		}
	
	function updatefrombad($data){
		
		$this->db->select('parts_stock_id,stock_quantity');
		$this->db->from($this->table_name);
		$this->db->where('sc_id =',$data['sc_id']);
		$this->db->where('part_number =',$data['part_number']);
		$this->db->where('company_id =',$data['company']);
		$result = $this->db->get();
		$result= $result->row();
		
		$data_stock['stock_quantity']= $result->stock_quantity - $data['quantity'];
		$this->save($data_stock,$result->parts_stock_id);
		
		}
	
	
	function getPartToAllocateByScId($page){
		
		$sc_id = $this->input->post('sc_id');
		$searchtxt = $this->input->post('searchtxt');
		
		if ($searchtxt){
			$this->db->like("concat(ps.part_id,pd.part_number ,pd.part_desc)",$searchtxt);
			}
		if ($sc_id){
			$this->db->where('ps.sc_id =',$sc_id);
			}
		$this->db->select('ps.part_id,pd.part_number,ps.company_id,pd.part_desc');
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_parts->table_name.' AS pd','ps.part_id,pd.part_number = ps.part_id,pd.part_number','INNER');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		//echo $this->db->last_query();
		$parts['list']= $result->result();
		
		if ($searchtxt){
			$this->db->like("concat(ps.part_id,pd.part_number,pd.part_desc)",$searchtxt);
			
			}
		if ($sc_id){
			$this->db->where('ps.sc_id =',$sc_id);
			}
		$this->db->select('ps.part_id,pd.part_number,ps.company_id,pd.part_desc');
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_parts->table_name.' AS pd','ps.part_id,pd.part_number = ps.part_id,pd.part_number','INNER');
		$result1 = $this->db->get();
		$parts['total']= $result1->num_rows();
		return $parts;
		
		}
		
	function checkPartsStockAvailable($sc_id,$part_number,$company_id){

		dump($part_number);
		die();
	    $parts = new stdClass;
		$parts->stock_quantity = 0;
		$parts->parts_available = false;
		$this->db->select('(ps.stock_quantity - ps.allocated_quantity) as stock_quantity');
		$this->db->from($this->table_name.' AS ps');
		$this->db->where('ps.sc_id =',$sc_id);
		$this->db->where('ps.part_id,pd.part_number =',$part_number);
		$this->db->where('ps.company_id =',$company_id);
		$result = $this->db->get();
		//echo $this->db->last_query();
		$arr = $result->row();
		if($result->num_rows()>0){
			$parts->stock_quantity = $arr->stock_quantity;
			$parts->parts_available = true;
		}
		return $parts;
	}
		
}
?>