<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Parts_used extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_parts_used';
		$this->primary_key = 'sst_parts_used.parts_used_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' parts_used_stocks_id ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getPartUsedByCall($call_id){
		$this->db->select('pu.parts_used_id,pu.part_number,pu.part_quantity,pp.part_desc');
		$this->db->from($this->table_name.' AS pu');
		$this->db->join($this->mdl_parts->table_name.' AS pp','pp.part_number=pu.part_number');
		$this->db->where('pu.call_id =',$call_id);
		$result = $this->db->get();
		$used_parts = $result->result();
		return $used_parts;
	}
	function getPartsUsedById($parts_used_id){
		$this->db->select('pu.parts_used_id');
		$this->db->from($this->table_name.' AS pu');
		$this->db->join($this->mdl_parts->table_name.' AS pp','pp.part_number=pu.part_number');
		$this->db->where('pu.parts_used_id =',$parts_used_id);
		$result = $this->db->get();
		$used_parts = $result->result();
		return $used_parts;
	}
}

?>