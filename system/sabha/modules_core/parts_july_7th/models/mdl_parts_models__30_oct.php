<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Parts_models extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_prod_part_models';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' part_number ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	function getParts($page){
		$model_number = $this->input->post('model_number');
		$arr = explode(",",$model_number);
		$str = '';
		$arr1 = array();
		foreach($arr as $model){
			$arr1[] = '"'.$model.'"';
		}
		$model_number = implode(",",$arr1);
		$this->db->select('DISTINCT(part_number),model_number');
		$this->db->from($this->table_name);
		$this->db->where("model_number IN ($model_number)");
		$this->db->order_by('model_number ASC');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		
		$result = $this->db->get();
		$parts = $result->result();
		
		//echo $this->db->last_query();
		$this->db->select('DISTINCT(part_number),model_number');
		$this->db->from($this->table_name);
		$this->db->where("model_number IN ($model_number)");
		$this->db->order_by('model_number ASC');
		$result_total = $this->db->get();
		$list['list'] = $parts;
		$list['total'] = $result_total->num_rows();
		return	$list;
	}
}

?>