<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Districts extends MY_Model {

	public function __construct() {

		parent::__construct();
		$this->table_name = 'sst_districts';
		$this->primary_key = 'sst_zones.disctrict_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' zone_name';
	}
	public function getDistrictOptions($zone_id){
		
		$params=array(
					  "select" => "district_id as value, district_name as text",
					  "where"=>array('zone_id'=>$zone_id), 
					  "order_by" => "text"
					  );
		$zones=$this->get($params);
		return $zones;
	}
	public function getDistrictByZone($zones){
		if(empty($zones)){
			$zones = '0';
		}
		$this->db->select('district_id,district_name,d.zone_id,zone_name');
		$this->db->from($this->table_name.' AS d',$this->mdl_zones->table_name.' AS z');
		$this->db->join($this->mdl_zones->table_name.' AS z','z.zone_id = d.zone_id','left');
		$this->db->where("d.zone_id IN ($zones)");
		$this->db->order_by('z.zone_name ASC,district_name ASC');
		$result = $this->db->get();
		return $result->result();
	}
}

?>