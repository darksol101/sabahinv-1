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
		$this->db->select('d.call_id,d.part_number,d.part_desc,d.part_quantity,d.part_serial_no,d.part_defect_id,c.company_title');
		$this->db->from($this->table_name.' AS d');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = d.company_id');
		$this->db->where('call_id =',$uid);
		$data = $this->db->get();
		return $data->result();
	}
	function getDefectedId($uid){
		return $uid;
	}
}
?>