<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Cities_search extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_cities';
		$this->primary_key = 'sst_cities.city_id';
		$this->order_by = ' city_name ASC';
	}
	public function getCitylist($page)
	{	
		$table_district = $this->mdl_districts->table_name;
		$table_zone = $this->mdl_zones->table_name;
		$searchtxt=$this->input->post('searchtxt');
		$zone_id=$this->input->post('zone_id');
		$district_id=$this->input->post('district_id');
		if($searchtxt){
			$this->db->like("$this->table_name.city_name",$this->db->escape_like_str($searchtxt));
		}
		if((int)$zone_id>0){
			$this->db->where("$table_zone.zone_id =",$zone_id);
		}
		if((int)$district_id>0){
			$this->db->where("$this->table_name.district_id =",$district_id);
		}
		
		$this->db->select("$this->table_name.city_id,$table_zone.zone_name,$table_zone.zone_id,$table_district.district_id,$table_district.district_name,$this->table_name.district_id,$this->table_name.city_name");
	    $this->db->from($this->table_name);
		$this->db->join($table_district, "$table_district.district_id = $this->table_name.district_id",'left');
		$this->db->join($table_zone, "$table_zone.zone_id = $table_district.zone_id",'left');
		$this->db->order_by('city_name ASC');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.city_name",$searchtxt);
		}
		if((int)$zone_id>0){
			$this->db->where("$table_zone.zone_id =",$zone_id);
		}
		if((int)$district_id>0){
			$this->db->where("$this->table_name.district_id =",$district_id);
		}
		
		$this->db->select("$this->table_name.city_id,$table_zone.zone_name,$table_district.district_name,$this->table_name.district_id,$this->table_name.city_name");
	    $this->db->from($this->table_name);
		$this->db->join($table_district, "$table_district.district_id = $this->table_name.district_id",'left');
		$this->db->join($table_zone, "$table_zone.zone_id = $table_district.zone_id",'left');
		$total = $this->db->count_all_results();
		
		$list['list'] = $result->result();
		$list['total'] = $total;
		return $list;
	}	
}
?>