<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Order_parts extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_order_parts';
		$this->table_name1 = 'sst_order_parts';
		$this->primary_key		= 'sst_order_parts.order_part_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'order_part_id';
		$this->logged=$this->createlogtable($this->table_name);
		$this->logged=$this->createlogtable($this->table_name1);
	}
	function save1($order_id){
		$part_number = $this->input->post('part_number');
		$part_quantity = $this->input->post('qty');
		$db_array = array(
					  'order_id'=>$order_id,
					  'part_number'=>$part_number,
					  'part_quantity'=>$part_quantity,
					  'order_part_created_by'=>$this->session->userdata('user_id'),
					  'order_part_created_ts'=>date("Y-m-d H:i:s")
					  );
		$sql = 'INSERT INTO '.$this->mdl_order_parts->table_name.' (part_number,part_quantity,order_id,order_part_created_by) VALUES ("'.$part_number.'","'.$part_quantity.'","'.$order_id.'","'.$this->session->userdata('user_id').'")
		ON DUPLICATE KEY 
		UPDATE  part_quantity="'.$part_quantity.'",order_part_last_mod_by="'.$this->session->userdata('user_id').'",order_part_last_mod_ts="'.date('Y-m-d H:i:s').'"';
		$success = $this->db->query($sql);
		$msg = array();
		if($this->db->insert_id()){
			$msg['order_part_id'] = $this->db->insert_id();
			$msg['order_id'] = $order_id;
			$msg['msg'] ='Saved';
			echo json_encode($msg);
		}
		;
		/*for log details --------------->>>>see table sst_call_parts_log
		** Used because we have composite primary key for this table.
		*/
		
		if($this->db->affected_rows()==0){
			//$row=$this->get_by_id($id) ;
			$this->db->where('part_number =',$part_number);
			$this->db->where('call_id =',$call_id);
			$query = $this->db->get($this->table_name);

			$row =  $query->row();
		
			$params=array();
			foreach($row as $key=>$value){
				$params[$key]=$value;
			}
			$params["log_details"]="Updated";
			$params["log_date"]=date("Y-m-d H:i:s");
			$params["log_user"]=$this->session->userdata("user_id");
			unset($params["id"]);
			$this->db->insert($this->logtable, $params);
		}else{
			
			$params=$db_array;
			unset($params["id"]);
			$params["log_details"]="Inserted";
			$params["log_date"]=date("Y-m-d H:i:s");
			$params["log_user"]=$this->session->userdata("user_id");
			$this->db->insert($this->logtable, $params);
		}
		return $success;
	}
	function getaddedparts($call_id){
		$this->db->select('op.order_part_id,o.order_id,o.order_number,op.part_number,o.order_status,op.part_quantity,pt.part_number,pt.part_desc,pt.part_customer_price,op.order_id,o.order_status,c.company_title');
		$this->db->join($this->mdl_parts->table_name.' AS pt','pt.part_number=op.part_number','LEFT');
		$this->db->join($this->mdl_orders->table_name.' AS o','o.order_id=op.order_id','LEFT');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = op.company_id','LEFT');
		$this->db->from($this->table_name.' AS op');
		$this->db->where('o.call_id =',$call_id);
		$this->db->order_by('o.order_id ASC, order_part_created_ts ASC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
	
	
	function getcallpart($call_id){
		
		$this->db->select('op.part_number,op.part_quantity,op.order_id,o.order_number,op.order_part_id,pt.part_desc,o.order_status,op.calls_orders_id');
		$this->db->from($this->table_name.' AS op');
		$this->db->join($this->mdl_orders->table_name.' AS o','op.order_id = o.order_id');
		$this->db->join($this->mdl_parts->table_name.' AS pt','pt.part_number=op.part_number','LEFT');
		$this->db->where('op.call_id =',$call_id);
		$result = $this->db->get();
		return $result->result();
		}
	
	
	
	function orderparts($order_id){
		$this->db->select('pd.part_id,p.part_number,pd.company_id,pd.part_quantity,pd.order_part_id, p.part_desc AS part_description,c.company_title,p.part_sc_price,cc.call_uid,pd.order_part_status');
		$this->db->from($this->table_name.' AS pd');
		$this->db->join($this->mdl_parts->table_name.' AS p','pd.part_id = p.part_id','LEFT');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = pd.company_id','LEFT');
		$this->db->join($this->mdl_callcenter->table_name.' AS cc','cc.call_id = pd.call_id','LEFT');
		$this->db->where('order_id =',$order_id);
		$result= $this->db->get();

		$parts = $result->result();
		
		return $parts;
		
	}
	function getOrderByDetailId($order_part_id){
		$this->db->select('od.order_id');
		$this->db->from($this->table_name.' as od');
		$this->db->where('od.order_part_id =',$order_part_id);
		
		$result = $this->db->get();
		$details = $result->row();
		//echo $this->db->last_query();
		return	$details;
	}
	function getOrderPartsDetails($order_id){
		$this->db->select('ptd.order_part_details_id, op.order_part_id, op.order_id,op.part_number,op.part_quantity, ptd.part_quantity AS ptd_part_quantity,op.order_part_status');
		$this->db->join($this->mdl_order_parts_details->table_name.' AS ptd','ptd.order_part_id=op.order_part_id','left');
		$this->db->from($this->table_name.' AS op');
		$this->db->where('op.order_id =',$order_id);
		$this->db->order_by('op.order_id ASC, op.order_part_created_ts ASC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
	function getOrderParts($order_part_id){
		$this->db->select('order_part_id,part_number,part_quantity,order_id,order_part_status');
		$this->db->from($this->table_name);
		$this->db->where('order_part_id =',$order_part_id);
		$result= $this->db->get();
		$parts = $result->row();
		return $parts;
	}
	
	function totalorderparts($order_id){
		$this->db->select('pd.part_id,p.part_number,pd.part_quantity,pd.order_part_id,part_desc AS part_description,c.company_title');
		$this->db->from($this->table_name.' AS pd');
		$this->db->join($this->mdl_parts->table_name.' AS p','pd.part_id = p.part_id','LEFT');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = pd.company_id','LEFT');
		$this->db->where('order_id =',$order_id);
		$result= $this->db->get();
		$total = $result->num_rows();
		
		return $total;
		
	}
	
	function savecallpart($records){
		
		foreach ($records as $record){
			$data['part_number']= $record->part_number;
			$data['part_quantity']=$record->part_quantity;
			$data['order_id'] = $this->session->userdata('orid');
			$data['order_part_created_by']= $this->session->userdata('user_id');
			$data['order_part_created_ts']= date('Y-m-d H:i:s');
			$data['calls_orders_id']= $record->calls_orders_id;
			$data['call_id']=$record->call_id;
			$data['engineer_id']=$record->engineer_id;
			$this->mdl_order_parts->save($data);
			}
		
		
		}
	
	function ordersparts($order_id){
		$this->load->model('company/mdl_company');
		$this->db->select('p.part_number,p.part_id,pd.part_quantity,pd.order_part_id,pd.company_id,c.company_title');
		$this->db->from($this->table_name.' AS pd');
		$this->db->join('sst_prod_parts AS p','p.part_id = pd.part_id','INNER');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = pd.company_id','INNER');
		$this->db->where('order_id =',$order_id);
		$result= $this->db->get();
		$parts = $result->result();
		
		return $parts;
		}
	
	
	function getPickingList($order_id){
		$this->db->select('p.part_id,p.part_number,p.part_desc,pbb.partbin_name,op.order_part_id,o.requested_sc_id,op.part_quantity');
		$this->db->from($this->table_name.' AS op');
		$this->db->join($this->mdl_orders->table_name.' AS o','o.order_id = op.order_id');
		$this->db->join($this->mdl_parts->table_name.' AS p','p.part_id = op.part_id');
		$this->db->	join($this->mdl_partbin_details->table_name.' AS pb','pb.part_id = op.part_id');
		$this->db->	join($this->mdl_partbin->table_name.' AS pbb','pbb.partbin_id = pb.partbin_id');
		$this->db->where('op.order_id =',$order_id);
		$result = $this->db->get();
		$result = $result->result();
		return $result;
		
		
		
		}
	
	
}
?>