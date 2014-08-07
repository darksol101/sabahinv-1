<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Reasons extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_reasons_pending';
		$this->primary_key = 'sst_reasons_pending.pending_id';
		$this->order_by = ' pending_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getReasonOptions()
	{
		$params = array(
						'select'=>'pending_id as value,pending as text',
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getReasonlist($page)
	{	
		$searchtxt=$this->input->post('searchtxt');
		if($searchtxt){
			$this->db->like("$this->table_name.pending",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.pending_id,$this->table_name.pending,$this->table_name.pending_status");
	    $this->db->from($this->table_name);
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by("pending_id DESC");
		$result = $this->db->get();
		$list['list'] = $result->result();
		
		//echo $this->db->last_query();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.pending",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.pending_id,$this->table_name.pending");
	    $this->db->from($this->table_name);
		$result_total = $this->db->get();
		//echo $this->db->last_query();
		$total = $result_total->num_rows();
		
		$list['total'] = $total;
		return $list;
	}	
	public function getreasondetails($id)
	{
		$params=array(
					 "select"=>"pending_id, pending",
					 "where"=>array("pending_id"=>(int)$id),
					 "limit"=>1
					 );
		$reason_arr=$this->get($params);
		$reason=$reason_arr[0];

		return $reason;
	}
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;

	}
	public function getPendingOptions(){
		$params=array(
					 "select"=>"pending as value,pending as text",
					 "order_by"=>'text'
					 );
		$options=$this->get($params);
		return $options;
	}
	public function validate() {
		$this->form_validation->set_rules('pending', $this->lang->line('reason'), 'required');
		return parent::validate();
	}
}
?>