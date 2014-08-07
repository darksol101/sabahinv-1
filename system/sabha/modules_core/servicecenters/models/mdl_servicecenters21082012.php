<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class mdl_Servicecenters extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'sst_service_centers';

		$this->primary_key = 'sst_service_centers.sc_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";

		$this->order_by = 'sc_name';
		$this->logged=$this->createlogtable($this->table_name);

	}
	
	public function get_sc(){
		$params=array(
					  "select"=>"sc_id as value, details as text",
					  "order_by"=>"sc_name",
					  "where"=>"published = 1"
					  );
		return $this->get($params);
	}
	
	public function get_ScByid($id){
		$params=array(
					  "select"=>"sc_name",
					  "where"=>array("sc_id"=>$id),
					  "limit"=>1
					  );
		return $this->get($params);
	}
	
	public function getServiceCenterList(){
		$searchtxt=$this->input->post('searchtxt');
		$params=array(
					 "select"=>"sc_id, sc_name, sc_address,sc_email,sc_fax,sc_address",
					 "order_by"=>"sc_name"
					 );
		if(!empty($searchtxt)){
			$params["where"][]="sc_name like '".$searchtxt."%'";
		}
		if($this->session->userdata('usergroup_id')==4){
			$params["where"]['sc_id'] = $this->session->userdata('sc_id');
		}
		$s_centers=$this->get($params);
		return $s_centers;
	}
	
	public function getServiceCenterdetails($id){
		$params=array(
					 "select"=>"sc_id, sc_name, sc_address,  sc_phone1, sc_phone2, sc_phone3, sc_fax, sc_email,city_id",
					 "where"=>array("sc_id"=>(int)$id),
					 "limit"=>1
					 );
		$serviceCenter_arr=$this->get($params);
		$serviceCenter_post=$serviceCenter_arr[0];
		return json_encode($serviceCenter_post);
	}
	function getServiceCentersOptions()
	{
		$params=array(
					 "select"=>"sc_id as value, sc_name as text",
					 "order_by"=>'text'
					 );
		$servicecenters=$this->get($params);
		return $servicecenters;
	}
	function getServiceCentersOptionsExclude($sc_id)
	{
		$params=array(
					 "select"=>"sc_id as value, sc_name as text",
					 "order_by"=>'text'
					 );
		if((int)$sc_id>0){
			$params["where"] = array('sc_id !='=>$sc_id);
		}
		$servicecenters=$this->get($params);
		return $servicecenters;
	}
	function getServiceCentersOptionsById($sc_id)
	{
		$params=array(
					 "select"=>"sc_id as value, sc_name as text",
					 "order_by"=>'text'
					 );
		if((int)$sc_id>0){
			$params["where"] = array('sc_id ='=>$sc_id);
		}
		$servicecenters=$this->get($params);
		return $servicecenters;
	}
	function getcentersbycity()
	{
		$city_table = $this->mdl_cities->table_name;
		$city_id = $this->input->post('city_id');
		$this->db->select("sc.sc_name as text,sc.sc_id as value");
		$this->db->from($this->table_name.' as sc');
		$this->db->join($city_table." as c", "c.sc_id = sc.sc_id",'left');
		$this->db->where("c.city_id =",$city_id);
		//$this->db->group_by('sc.sc_id');
		$result = $this->db->get();
		//echo $this->db->last_query();
		
		return $result->result();
	}
}
?>