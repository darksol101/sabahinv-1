<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Cities extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_cities';
		$this->primary_key = 'sst_cities.city_id';
	}	
	public function getCitySearch()
	{				
		$table_district = $this->mdl_districts->table_name;
		$table_zone = $this->mdl_zones->table_name;
		$searchtxt=$this->input->post('searchcitytxt');
		$this->db->select('*');
	    $this->db->join($table_district, "$table_district.district_id = $this->table_name.district_id",'left');
		$this->db->join($table_zone, "$table_zone.zone_id = $table_district.zone_id",'left');
		$this->db->like("$this->table_name.city_name", $searchtxt); 
		$cities=$this->get();
		return	$cities;
	}
	public function getCityOptions($district_id)
	{
		$params = array(
						'select'=>'city_id as value,city_name as text',
						 "where"=>array("district_id"=>(int)$district_id),
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getCitylist()
	{	
		$table_district = $this->mdl_districts->table_name;
		$table_zone = $this->mdl_zones->table_name;
		$searchtxt=$this->input->post('searchtxt');
		$this->db->select("$this->table_name.city_id,$table_zone.zone_name,$table_district.district_name,$this->table_name.district_id,$this->table_name.city_name");
	    $this->db->from($this->table_name);
		$this->db->join($table_district, "$table_district.district_id = $this->table_name.district_id",'left');
		$this->db->join($table_zone, "$table_zone.zone_id = $table_district.zone_id",'left');
		$result = $this->db->get();
		return $result->result();
	}	
	public function getcitydetails($id)
	{
		$params=array(
					 "select"=>"city_id, city_name, district_id",
					 "where"=>array("city_id"=>(int)$id),
					 "limit"=>1
					 );
		$city_arr=$this->get($params);
		$city_post=$city_arr[0];
		
		$table_zone = $this->mdl_zones->table_name;
		$table_district = $this->mdl_districts->table_name;
		$this->db->select("$this->table_name.city_id,$table_zone.zone_id,$this->table_name.district_id,$this->table_name.city_name");
	    $this->db->from($this->table_name);
		$this->db->join($table_district, "$table_district.district_id = $this->table_name.district_id",'left');
		$this->db->join($table_zone, "$table_zone.zone_id = $table_district.zone_id",'left');
		$this->db->where(array('city_id'=>$id));
		$result = $this->db->get();
		$city_arr = $result->result();
		$city_post=$city_arr[0];
		return json_encode($city_post);
	}	
}
?>