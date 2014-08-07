<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Autocallassign extends MY_Model {

	public function __construct() {
		parent::__construct();
	}
	function getServiceCentersByAssign($city_id,$product_id){
		if(!empty($city_id)){
			$this->db->where('ca.city_id =',$city_id);
			$this->db->where('pa.product_id =',$product_id);
		}
		$this->db->select('ca.sc_id,s.sc_name');
		$this->db->from($this->mdl_cityassign->table_name.' AS ca');
		$this->db->join($this->mdl_productassign->table_name.' AS pa','pa.sc_id=ca.sc_id','inner');
		$this->db->join($this->mdl_servicecenters->table_name.' AS s','s.sc_id=pa.sc_id','inner');
		$this->db->group_by('ca.sc_id');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
}
?>