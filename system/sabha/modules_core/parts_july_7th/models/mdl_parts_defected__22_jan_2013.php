<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Parts_defected extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_parts_defect';
		$this->primary_key = 'sst_parts_defect.parts_defect_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = 'parts_defect_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getDefectedData($uid){
		$this->db->select('call_id,part_number,part_desc,part_quantity,part_serial_no,part_defect_id');
		$this->db->from($this->table_name);
		$this->db->where('call_id =',$uid);
		$data = $this->db->get();
		return $data->result();
	}
	function getDefectedId($uid){
		return $uid;
	}
}
?>