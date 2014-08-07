<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Performance extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls';
		$this->primary_key = 'sst_calls.call_id';
		$this->logged=$this->createlogtable($this->table_name);
	}	
	public function getReportsSearch()
	{				
		$table_calls = $this->mdl_calls->table_name;
		//$searchtxt=$this->input->post('searchcitytxt');
		$this->db->select('*');
	    $this->db->join($table_district, "$table_district.district_id = $this->table_name.district_id",'left');
		$this->db->join($table_zone, "$table_zone.zone_id = $table_district.zone_id",'left');
		$this->db->like("$this->table_name.city_name", $searchtxt); 
		$cities=$this->get();
		return	$reports;
	}
	function getcallsbyusers()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		$users = $this->getCallCreatedBy();
		foreach($users as $user){
			$user->username = $this->mdl_users->getUserNameByUserId($user->call_created_by);
			$user->total_call_reg = $this->totalCallsByUser($user->call_created_by);
		}
		return $users;
		
		/*$sql = 'SELECT u.username,c.call_created_by,COUNT(call_id) AS total_call_reg 
				FROM sst_calls AS c 
				LEFT JOIN mcb_users AS u ON u.user_id=c.call_created_by
				WHERE c.call_dt >= "'.$from_date.'" AND c.call_dt <= "'.$to_date.'"
				GROUP BY c.call_created_by'
				;
		$result = $this->db->query($sql);
		return $result->result();*/
	}
	function totalCallsByUser($user_id){
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		$sql = 'SELECT COUNT(call_id) AS total_call_reg 
				FROM sst_calls AS c 
				WHERE c.call_dt >= "'.$from_date.'" AND c.call_dt <= "'.$to_date.'"
				AND call_created_by="'.$user_id.'"'
				;
		$result = $this->db->query($sql);
		$calls = $result->row();
		return $calls->total_call_reg;
	}
	public function getCallCreatedBy()
	{
		$sql = 'SELECT c.call_created_by 
				FROM sst_calls AS c 
				LEFT JOIN mcb_users AS u ON u.user_id=c.call_created_by
				GROUP BY c.call_created_by ORDER BY u.username ASC'
				;
		$result = $this->db->query($sql);
		return $result->result();
		/*$params = array(
						'select'=>'call_created_by',
						'group_by'=>'call_created_by'
						);
		$result = $this->get($params);
		return $result;*/
	}
	function getAssocEngineers($sc_id)
	{
		$sql = "SELECT c.engineer_id,e.engineer_name,c.sc_id
				FROM sst_calls AS c 
				LEFT JOIN sst_engineers AS e ON e.engineer_id=c.engineer_id
				WHERE e.engineer_name!='' AND c.sc_id='$sc_id' 
				GROUP BY c.engineer_id ORDER BY e.engineer_name ASC"
				;
		$result = $this->db->query($sql);
		return $result->result();		
	}
	function getCallsByEngineers(){
		$sc_id = $this->input->post('sc_id');
		$engineers = $this->getAssocEngineers($sc_id);
		foreach($engineers as $engineer){
			$engineer->total_call_open = $this->getTotalCallsByEngineer($sc_id,$engineer->engineer_id,0);
			$engineer->total_call_pending = $this->getTotalCallsByEngineer($sc_id,$engineer->engineer_id,1);
			$engineer->total_call_partpending = $this->getTotalCallsByEngineer($sc_id,$engineer->engineer_id,2);
			$engineer->total_call_closed = $this->getTotalCallsByEngineer($sc_id,$engineer->engineer_id,3);
			$engineer->total_call_cancelled = $this->getTotalCallsByEngineer($sc_id,$engineer->engineer_id,4);
		}
		return $engineers;
	}
	function getTotalCallsByEngineer($sc_id,$engineer_id,$call_status)
	{
		$partpending_condition = '';
		if($call_status==1){
			$partpending_condition = ' AND call_reason_pending!="Part Pending"';
		}
		if($call_status==2){
			$call_status=1;
			$partpending_condition = ' AND call_reason_pending="Part Pending"';
		}
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		$sql = 'SELECT COUNT(call_id) AS total_calls 
				FROM sst_calls AS c 
				WHERE c.call_dt >= "'.$from_date.'" AND c.call_dt <= "'.$to_date.'"
				AND sc_id="'.$sc_id.'" AND call_status="'.$call_status.'"'.$partpending_condition.' AND engineer_id="'.$engineer_id.'"'
				;
		$result = $this->db->query($sql);
		$calls = $result->row();
		return $calls->total_calls;
	}
}
?>