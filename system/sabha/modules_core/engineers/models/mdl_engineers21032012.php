<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Engineers extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'sst_engineers';

		$this->primary_key = 'sst_engineers.engineer_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' engineer_name';
	}	
	public function getuserengineer(){
		$params=array(
					  "select"=>"engineer_name as value, details as text",
					  "order_by"=>"engineer_name",
					  "where"=>"engineer_published = 1"
					  );
		return $this->get($params);
		}	
	public function getEngineerByid($id){
		$params=array(
					  "select"=>"engineer_name",
					  "where"=>array("engineer_id"=>$id),
					  "limit"=>1
					  );
		return $this->get($params);
		}
	
	public function getEngineerlist(){
		$searchtxt=$this->input->post('searchtxt');
		$params=array(
					 "select"=>"engineer_name,engineer_id, engineer_status",
					 "order_by"=>"engineer_name"
					 );
		if(!empty($searchtxt)){
			$params["where"][]="engineer_name like '".$searchtxt."%'";
		}
		$engineers=$this->get($params);
		return $engineers;
		}
	
	public function getEngineer($id){
		$params=array(
					 "select"=>"engineer_id,engineer_name, engineer_desc,  engineer_status",
					 "where"=>array("engineer_id"=>(int)$id),
					 "limit"=>1
					 );
		$arr=$this->get($params);
		$engineer=$arr[0];
		return $engineer;
	}
	public function getEngineerOptions(){
		$params=array(
					 "select"=>"engineer_id as value,engineer_name as text",
					 "where"=>array("engineer_status"=>"1"),
					 "order_by"=>'text'
					 );
		$options=$this->get($params);
		return $options;
	}
}

?>