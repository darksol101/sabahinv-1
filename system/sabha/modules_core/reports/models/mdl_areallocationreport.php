<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Areallocationreport extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_auto_ass_city';
	}
	function getAreallocationReport($sc_id){
		$this->db->select('sc.sc_name,c.city_name,d.district_name,z.zone_name,asac.sc_id');
		$this->db->from($this->table_name.' AS asac');
		$this->db->join($this->mdl_cities->table_name.' AS c','c.city_id=asac.city_id','inner');
		$this->db->join($this->mdl_districts->table_name.' AS d','d.district_id=c.district_id','inner');
		$this->db->join($this->mdl_zones->table_name.' AS z','z.zone_id=d.zone_id','inner');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=asac.sc_id','inner');
		$this->db->where("asac.sc_id IN($sc_id)");
		//$this->db->order_by("sc.sc_name ASC,z.zone_name,d.district_name,c.city_name");
		$result = $this->db->get();
		//echo $this->db->last_query();
			
		return $result->result();
	}
	function getCitesBySc($sc_id){
		/*$this->db->select('c.city_name');
		 $this->db->from($this->mdl_cities->table_name.' as c');
		 $this->db->join($this->mdl_districts->table_name.' as d','d.district_id=d.district_id','inner');
		 $this->db->join($this->mdl_zones->table_name.' as z','z.zone_id=d.zone_id','inner');
		 $this->db->where('c.city_id IN (SELECT city_id FROM '.$this->table_name.' where sc_id='.$sc_id.')');*/
		$this->db->select('ca.city_id,c.city_name,c.district_id');
		$this->db->from($this->table_name.' as ca');
		$this->db->join($this->mdl_cities->table_name.' as c','c.city_id=ca.city_id','inner');
		$this->db->where('ca.sc_id =',$sc_id);
		$result = $this->db->get();
		return $result->result();
	}
	function getCitiesByDistrict($district_id){
		$this->db->select("d.zone_id,z.zone_name,d.district_name,GROUP_CONCAT(' ', c.city_name) as city_name",false);
		$this->db->from($this->mdl_cities->table_name.' as c');
		$this->db->join($this->mdl_districts->table_name.' as d','d.district_id=c.district_id','inner');
		$this->db->join($this->mdl_zones->table_name.' as z','z.zone_id=d.zone_id','inner');
		$this->db->where('d.district_id =',$district_id);
		$this->db->group_by('d.district_id');
		$result = $this->db->get();
		return $result->row();

	}
	function autocallassignreport($sc_id){
		$this->db->select('sc.sc_id,count(sc.sc_id) as total_sc,sc.sc_name,ca.city_id,c.district_id,',false);
		$this->db->from($this->mdl_servicecenters->table_name.' AS sc');
		$this->db->join($this->table_name.' ca','ca.sc_id=sc.sc_id','left');
		$this->db->join($this->mdl_cities->table_name.' as c','c.city_id = ca.city_id','left');
		$this->db->where('sc.sc_id IN ('.$sc_id.')');
		$this->db->group_by('sc.sc_id,c.district_id');
		$this->db->order_by('sc.sc_name ASC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
}
?>