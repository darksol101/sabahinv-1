<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Customers extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'sst_cust_profile';

		$this->primary_key = 'sst_cust_profile.cust_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";

		$this->order_by = ' cust_first_name ';

	}
	
	public function getCustomer($cust_id){
		$this->db->select("*");
		$this->db->from($this->table_name);
		$this->db->where(array('cust_id'=>(int)$cust_id));
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->row();
	}
	
	public function getUserGroupByid($id){
		$params=array(
					  "select"=>"details",
					  "where"=>array("groupcode"=>$id),
					  "limit"=>1
					  );
		return $this->get($params);
		}
	
	public function getCustomers(){
		$searchtxt=$this->input->post('searchtxt');
		$params=array(
					 "select"=>"cust_id,cust_first_name,cust_last_name,cust_address,cust_phone_home,cust_phone_office,cust_phone_mobile",
					 "order_by"=>"cust_first_name ASC"
					 );
		if(!empty($searchtxt)){
			$this->db->or_like("cust_first_name",$searchtxt);
			$this->db->or_like("cust_last_name",$searchtxt);
			$this->db->or_like("cust_address",$searchtxt);
			$this->db->or_like("cust_phone_home",$searchtxt);
			$this->db->or_like("cust_phone_mobile",$searchtxt);
		}
		$customers=$this->get($params);
		return $customers;
		}
	
	public function getGroup($id){
		$params=array(
					 "select"=>"groupcode, details, description,  locked",
					 "where"=>array("groupcode"=>$id),
					 "limit"=>1
					 );
		$grouparr=$this->get($params);
		$group=$grouparr[0];
		return json_encode($group);
		}
}

?>