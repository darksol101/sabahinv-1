<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Bulletin extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_bulletin_board';
		$this->primary_key = 'sst_bulletin_board.bulletin_board_id';
		$this->order_by = 'bulletin_board_name ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getBulletinOptions()
	{
		$params = array(
						'select'=>'bulletin_board_id as value,bulletin_board_name as text,bulletin_board_desc as text2, bulletin_board_start_dt as date1, bulletin_board_end_dt as date2',
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getBulletinlist($page)
	{	
		$searchtxt=$this->input->post('searchtxt');
		if($searchtxt){
			$this->db->like("$this->table_name.bulletin_board_name",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.bulletin_board_id,$this->table_name.bulletin_board_name,$this->table_name.bulletin_board_desc,$this->table_name.bulletin_board_start_dt,$this->table_name.bulletin_board_end_dt,$this->table_name.bulletin_board_status");
	    $this->db->from($this->table_name);
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by("bulletin_board_name DESC");
		$result = $this->db->get();
		$list['list'] = $result->result();
		
		//echo $this->db->last_query();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.bulletin_board_name",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.bulletin_board_id,$this->table_name.bulletin_board_name,$this->table_name.bulletin_board_desc,$this->table_name.bulletin_board_status");
	    $this->db->from($this->table_name);
		$result_total = $this->db->get();
		//echo $this->db->last_query();
		$total = $result_total->num_rows();
		
		$list['total'] = $total;
		return $list;
	}	
	public function getbulletindetails($id)
	{
		$params=array(
					 "select"=>"bulletin_board_id, bulletin_board_name,bulletin_board_desc,bulletin_board_status,bulletin_board_start_dt,bulletin_board_end_dt",
					 "where"=>array("bulletin_board_id"=>(int)$id),
					 "limit"=>1
					 );
		$bulletin_arr=$this->get($params);
		$bulletin=$bulletin_arr[0];
		$bulletin->bulletin_board_start_dt = format_date(strtotime($bulletin->bulletin_board_start_dt));
		$bulletin->bulletin_board_end_dt = format_date(strtotime($bulletin->bulletin_board_end_dt));

		return $bulletin;
	}
	function getEvents($page)
	{
		$current_date = date('Y-m-d');
		$this->db->select('bulletin_board_id,bulletin_board_name,bulletin_board_desc,bulletin_board_start_dt,bulletin_board_end_dt');
		$this->db->from($this->table_name);
		$this->db->where('bulletin_board_end_dt >=',$current_date);
		$this->db->where('bulletin_board_status =',1);
		$this->db->order_by('bulletin_board_start_dt desc');
		$this->db->limit($page['limit'],$page['start']);
		$result = $this->db->get();
		$list['list'] = $result->result();
		$this->db->select('count(bulletin_board_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where('bulletin_board_end_dt >=',$current_date);
		$this->db->where('bulletin_board_status =',1);
		$this->db->order_by('bulletin_board_start_dt desc');
		$result_total = $this->db->get();
		$arr = $result_total->row();
		$list['total'] = $arr->cnt;
		
		return $list;
	}
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;
		

	}
	public function validate() {
		$this->form_validation->set_rules('bulletin', $this->lang->line('bulletin'), 'required');
		return parent::validate();
	}
}
?>