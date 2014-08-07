<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_UserGroups extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'mcb_usergroup';

		$this->primary_key = 'mcb_usergroup.usergroup_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";

		$this->order_by = ' details ';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	public function getusergroup(){
		$params=array(
					  "select"=>"usergroup_id as value, details as text,usergroup_status",
					  "order_by"=>"usergroup_id",
					  "where"=>"usergroup_status = 1"
					  );
					  return $this->get($params);
	}
	public function getUserGroupOptionsById($usergroup_id){
		$params=array(
					  "select"=>"usergroup_id as value, details as text",
					  "where"=>array("usergroup_id"=>$usergroup_id),
					  "limit"=>1
		);
		return $this->get($params);
	}
	public function getUserGroupByid($id){
		$params=array(
					  "select"=>"details",
					  "where"=>array("usergroup_id"=>$id),
					  "limit"=>1
					  );
		return $this->get($params);
		}
	
	public function getGrouplist(){
		$searchtxt=$this->input->post('searchtxt');
		$params=array(
					 "select"=>"details,usergroup_id, usergroup_status",
					 "order_by"=>"details"
					 );
		if(!empty($searchtxt)){
			$params["where"][]="details like '".$searchtxt."%'";
		}
		$groups=$this->get($params);
		return $groups;
		}
	
	public function getGroup($id){
		$params=array(
					 "select"=>"usergroup_id, details, description,  usergroup_status",
					 "where"=>array("usergroup_id"=>(int)$id),
					 "limit"=>1
					 );
		$grouparr=$this->get($params);
		$group=$grouparr[0];
		return json_encode($group);
		}
}

?>