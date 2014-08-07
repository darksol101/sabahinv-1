<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Cities extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_cities';
		$this->primary_key = 'sst_cities.city_id';
		$this->logged=$this->createlogtable($this->table_name);
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
						'order_by'=>'text'
						);
		$this->db->where("district_id =",(int)$district_id);
		$result = $this->get($params);
		return $result;
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
		$this->db->select("$this->table_name.city_id,$table_zone.zone_name,$table_district.district_name,$this->table_name.district_id,$this->table_name.city_name");
	    $this->db->from($this->table_name);
		$this->db->join($table_district, "$table_district.district_id = $this->table_name.district_id",'left');
		$this->db->join($table_zone, "$table_zone.zone_id = $table_district.zone_id",'left');
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