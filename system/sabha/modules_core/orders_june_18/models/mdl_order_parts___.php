<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Order_parts extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_order_parts';
		$this->primary_key		=	'sst_order_parts.order_part_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'order_part_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function save($order_id){
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
		*/// 
		
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
		$this->db->select('op.order_part_id,o.order_id,o.order_number,op.part_number,o.order_status,op.part_quantity,pt.part_number,pt.part_desc,pt.part_customer_price,op.order_id,o.order_status');
		$this->db->join($this->mdl_parts->table_name.' AS pt','pt.part_number=op.part_number');
		$this->db->join($this->mdl_orders->table_name.' AS o','o.order_id=op.order_id');
		$this->db->from($this->table_name.' AS op');
		$this->db->where('o.call_id =',$call_id);
		$this->db->order_by('o.order_id ASC, order_part_created_ts ASC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
	
	function orderparts($order_id){
		$this->db->select('pd.part_number,pd.part_quantity,pd.order_part_id,part_desc AS part_description');
		$this->db->from($this->table_name.' AS pd');
		$this->db->join($this->mdl_parts->table_name.' AS p','pd.part_number = p.part_number');
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
		// 
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
}
?>