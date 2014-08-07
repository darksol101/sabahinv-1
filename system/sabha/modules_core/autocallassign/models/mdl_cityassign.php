<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Cityassign extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_auto_ass_city';
	}
	function getCityAssignsBySc($sc_id){
		$this->db->select('ca.city_id');
		$this->db->from($this->mdl_zones->table_name.' AS z');
		$this->db->join($this->mdl_districts->table_name.' AS d','d.zone_id=z.zone_id','left');
		$this->db->join($this->mdl_cities->table_name.' AS c','c.district_id=d.district_id','left');
		$this->db->join($this->table_name.' ca','ca.city_id=c.city_id','left');
		$this->db->where('ca.sc_id =',$sc_id);
		$this->db->group_by('c.city_id');
		$result = $this->db->get();
		//echo $this->db->last_query();
		//print_r($result->result());
		return $result->result();
	}
	function getZoneAssignsBySc($sc_id){
		$this->db->select('z.zone_id,z.zone_name,ca.city_id');
		$this->db->from($this->mdl_zones->table_name.' AS z');
		$this->db->join($this->mdl_districts->table_name.' AS d','d.zone_id=z.zone_id','left');
		$this->db->join($this->mdl_cities->table_name.' AS c','c.district_id=d.district_id','left');
		$this->db->join($this->table_name.' ca','ca.city_id=c.city_id','left');
		$this->db->where('ca.sc_id =',$sc_id);
		$this->db->group_by('z.zone_id');
		$result = $this->db->get();
		return $result->result();
	}
	function getDistrictAssignsBySc($sc_id){
		$this->db->select('d.district_id,d.district_name');
		$this->db->from($this->mdl_zones->table_name.' AS z');
		$this->db->join($this->mdl_districts->table_name.' AS d','d.zone_id=z.zone_id','left');
		$this->db->join($this->mdl_cities->table_name.' AS c','c.district_id=d.district_id','left');
		$this->db->join($this->table_name.' ca','ca.city_id=c.city_id','left');
		$this->db->where('ca.sc_id =',$sc_id);
		$this->db->group_by('d.district_id');
		$result = $this->db->get();
		return $result->result();
	}
}
?>