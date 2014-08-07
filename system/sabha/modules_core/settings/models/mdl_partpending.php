<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Partpending extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_reason_partpending';
		$this->primary_key = 'sst_reason_partpending.partpending_id';
		$this->order_by = 'partpending ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getPartpendingOptions()
	{
		$params = array(
						'select'=>'partpending as value,partpending as text',
						'where'=>array('partpending_status'=>1),
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getPartpendinglist($page)
	{	
		$searchtxt=$this->input->post('searchtxt');
		if($searchtxt){
			$this->db->like("$this->table_name.partpending",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.partpending_id,$this->table_name.partpending,$this->table_name.partpending_status");
	    $this->db->from($this->table_name);
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by("partpending DESC");
		$result = $this->db->get();
		$list['list'] = $result->result();
		
		//echo $this->db->last_query();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.partpending",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.partpending,$this->table_name.partpending");
	    $this->db->from($this->table_name);
		$result_total = $this->db->get();
		//echo $this->db->last_query();
		$total = $result_total->num_rows();
		
		$list['total'] = $total;
		return $list;
	}	
	public function getpartpendingdetails($id)
	{
		$params=array(
					 "select"=>"partpending_id, partpending",
					 "where"=>array("partpending_id"=>(int)$id),
					 "limit"=>1
					 );
		$partpending_arr=$this->get($params);
		$partpending=$partpending_arr[0];

		return $partpending;
	}
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;

	}
	public function validate() {
		$this->form_validation->set_rules('partpending', $this->lang->line('partpending'), 'required');
		return parent::validate();
	}
}
?>