<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Call_parts extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_call_parts';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' part_number ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function save(){
		$call_id = $this->input->post('call_id');
		$part_number = $this->input->post('part_number');
		$part_qty = $this->input->post('qty');
		$db_array = array(
					  'call_id'=>$call_id,
					  'part_number'=>$part_number,
					  'part_qty'=>$part_qty,
					  'call_part_created_by'=>$this->session->userdata('user_id'),
					  'call_part_created_ts'=>date("Y-m-d H:i:s")
					  );
		$sql = 'INSERT INTO '.$this->mdl_call_parts->table_name.' (part_number,call_id,part_qty) VALUES ("'.$part_number.'","'.$call_id.'","'.$part_qty.'")
		ON DUPLICATE KEY 
		UPDATE  part_qty="'.$part_qty.'",call_part_last_mod_by="'.$this->session->userdata('user_id').'",call_part_last_mod_ts="'.date('Y-m-d H:i:s').'"';
		$this->db->query($sql);
		
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
	}
	function getaddedparts(){
		$call_id = $this->input->post('call_id');
		$this->db->select('cp.part_number,cp.part_qty,pt.part_number,pt.part_customer_price');
		$this->db->join($this->mdl_parts->table_name.' AS pt','pt.part_number=cp.part_number');
		$this->db->from($this->table_name.' AS cp');
		$this->db->where('call_id =',$call_id);
		$this->db->order_by('call_part_created_ts ASC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
}

?>