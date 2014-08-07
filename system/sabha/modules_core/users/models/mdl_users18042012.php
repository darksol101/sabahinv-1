<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Users extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'mcb_users';
		$this->primary_key = 'mcb_users.id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = 'id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getUserlist(){
		$searchtxt=$this->input->post('searchtxt');
		
		if(!empty($searchtxt)){
			$this->db->like('u.username', $this->db->escape_like_str($searchtxt));
			$this->db->or_like('ug.details', $this->db->escape_like_str($searchtxt));
			$this->db->or_like('sc.sc_name', $this->db->escape_like_str($searchtxt));
		}
		$this->db->select("u.id,u.email_address, u.user_id, u.username,ug.details, u.ustatus,ug.details as usergroup,sc.sc_name");
		$this->db->from($this->table_name.' as u');
		$this->db->join($this->mdl_usergroups->table_name.' as ug','ug.usergroup_id=u.usergroup_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=u.sc_id','left');
		$this->db->order_by('u.username ASC');
		
		$result = $this->db->get();
		$list['users'] = $result->result();
		$list['total'] = ($result->num_rows());
		//echo $this->db->last_query();
		return	$list;
	}
		
	public function getUser($userid){
		$params=array(
					 "select"=>"id, password, email_address, mobile_number, user_id, username, usergroup_id, ustatus,sc_id",
					 "where"=>array("id"=>$userid),
					 "limit"=>1
					 );
		$userarr=$this->get($params);
		$user=$userarr[0];
		$user->password=md5_decrypt($user->password,$user->username);
		return json_encode($user);
	}
	
	public function getUsername($userid){
		$params=array(
					 "select"=>"username",
					 "where"=>array("id"=>$userid),
					 "limit"=>1
					 );
		$userarr=$this->get($params);
		$user=$userarr[0];
		return $user->username;
	}
	function getScenterDetailsByUser($username){
		$this->db->select('distinct(sst_service_centers.sc_id) ,sst_service_centers.sc_name,sst_service_centers.city_id,sst_cities.city_name,sst_zones.zone_code,sst_zones.zone_name,sst_districts.district_name');
		$this->db->from('sst_service_centers', 'mcb_users');
		$this->db->join('mcb_users', 'mcb_users.sc_id = sst_service_centers.sc_id','left');
		$this->db->join('sst_cities', 'sst_cities.city_id = sst_service_centers.city_id','left');
		$this->db->join('sst_districts', 'sst_districts.district_id = sst_cities.district_id','left');
		$this->db->join('sst_zones', 'sst_zones.zone_id = sst_districts.zone_id','left');
		$this->db->where('mcb_users.username =', $username);
		$result = $this->db->get();
		$ss_details = $result->result();
		//echo $this->db->last_query();
		if(count($ss_details)>0){
			return $ss_details[0];
		}else{
			$ss_details[0]->sc_name='';
			$ss_details[0]->sc_id='';
			return $ss_details[0];
		}
		
	}
	function checkScenterByUser($sc_id)
	{
		$params=array(
					 "select"=>"count(user_id) as total",
					 "where"=>array("sc_id"=>$sc_id),
					 "limit"=>1
					 );
		$result = $this->get($params);
		$count = $result[0]->total;
		return $count;
	}
	function AutoIncrementId(){
		$query = $this->db->query("SHOW TABLE STATUS LIKE 'mcb_users'");
		$user = $query->result();
		return $user[0]->Auto_increment;
	}

}

?>