<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Reminders extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_reminders';
		$this->primary_key = 'sst_reminders.reminder_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' reminder_id ';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getReminders($page){
		$searchtxt=$this->input->post('searchtxt');

		if ($searchtxt){
			$this->db->like('r.reminder_remarks', $this->db->escape_like_str($searchtxt));
		}
		$this->db->select('r.reminder_id,r.reminder_remarks,r.reminder_dt,u.username,r.reminder_created_by');
		$this->db->from($this->table_name.' AS r');
		$this->db->join($this->mdl_users->table_name.' AS u','u.user_id=r.reminder_created_by','left');
		$this->db->order_by('r.reminder_id ASC');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();

		$list['list'] = $result->result();
		if ($searchtxt){
			$this->db->like('r.reminder_remarks', $this->db->escape_like_str($searchtxt));
		}
		$this->db->select('r.reminder_id');
		$this->db->from($this->table_name.' AS r');
		$this->db->order_by('r.reminder_id ASC');
		$resultotal = $this->db->get();
		//echo $this->db->last_query();

		$list['total'] = $resultotal->num_rows;
		return	$list;
	}
	public function getRemindersByCall($page){
		$searchtxt=$this->input->post('searchtxt');
		$call_id = $this->input->post('call_id');

		if ($searchtxt){
			$this->db->like('r.reminder_remarks', $this->db->escape_like_str($searchtxt));
		}
		$this->db->where('r.call_id = "'.$call_id.'"');
		$this->db->select('r.reminder_id,r.reminder_remarks,r.reminder_dt,u.username');
		$this->db->from($this->table_name.' AS r');
		$this->db->join($this->mdl_users->table_name.' AS u','u.user_id=r.reminder_created_by','left');
		$this->db->order_by('r.reminder_id ASC');
		$result = $this->db->get();

		$list['list'] = $result->result();
		if ($searchtxt){
			$this->db->like('r.reminder_remarks', $this->db->escape_like_str($searchtxt));
		}
		$this->db->where('r.call_id = "'.$call_id.'"');
		$this->db->select('r.reminder_id');
		$this->db->from($this->table_name.' AS r');
		$this->db->order_by('r.reminder_id ASC');
		$resultotal = $this->db->get();
		//echo $this->db->last_query();

		$list['total'] = $resultotal->num_rows;
		return	$list;
	}
	public function getReminderDetails($reminder_id){
		$params=array(
					 "select"=>"reminder_id,reminder_remarks",
					 "where"=>array("reminder_id"=>$reminder_id),
					 "limit"=>1
		);
		$grouparr=$this->get($params);
		$group=$grouparr[0];
		return $group;
	}
	function getBrandOptions()
	{
		$params=array(
					 "select"=>"brand_name as text, brand_id as value",
					 "where"=>array("brand_status"=>1),
					 "order_by"=>'brand_name'
					 );
					 $brand_options=$this->get($params);
					 return $brand_options;
	}
}

?>