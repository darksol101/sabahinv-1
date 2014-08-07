<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Zones extends MY_Model {

	public function __construct() {

		parent::__construct();
		$this->table_name = 'sst_zones';
		$this->primary_key = 'sst_zones.zone_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' zone_name';
	}
	public function getZoneOptions(){
		$params=array(
					  "select" => "zone_id as value, zone_name as text",
					  "order_by" => "text"
					  );
				

		$zones=$this->get($params);


		return $zones;
	}
	public function getZonecode($zone_id){
		$this->db->select("zone_code");
		$this->db->from($this->table_name);
		$this->db->where(array('zone_id'=>(int)$zone_id));
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->row();
	}
}

?>