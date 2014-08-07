<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Cities extends MY_Model {

	public function __construct() {

		parent::__construct();
		$this->table_name = 'sst_cities';
		$this->primary_key = 'sst_cities.city_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' city_name';
	}
	public function getCityOptions($district_id){
		
		$params=array(
					  "select" => "city_id as value, city_name as text",
					  "where"=>array('district_id'=>$district_id), 
					  "order_by" => "text"
					  );
		$zones=$this->get($params);
		return $zones;
	}
}

?>