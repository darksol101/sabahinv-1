<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Closure extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_reason_closure';
		$this->primary_key = 'sst_reason_closure.closure_id';
		$this->order_by = 'closure ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getClosureOptions()
	{
		$params = array(
						'select'=>'closure as value,closure as text',
						'where'=>array('closure_status'=>1),
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getClosurelist($page)
	{	
		$searchtxt=$this->input->post('searchtxt');
		if($searchtxt){
			$this->db->like("$this->table_name.closure",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.closure_id,$this->table_name.closure,$this->table_name.closure_status");
	    $this->db->from($this->table_name);
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by("closure DESC");
		$result = $this->db->get();
		$list['list'] = $result->result();
		
		//echo $this->db->last_query();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.closure",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.closure,$this->table_name.closure");
	    $this->db->from($this->table_name);
		$result_total = $this->db->get();
		//echo $this->db->last_query();
		$total = $result_total->num_rows();
		
		$list['total'] = $total;
		return $list;
	}	
	public function getclosuredetails($id)
	{
		$params=array(
					 "select"=>"closure_id, closure",
					 "where"=>array("closure_id"=>(int)$id),
					 "limit"=>1
					 );
		$closure_arr=$this->get($params);
		$closure=$closure_arr[0];

		return $closure;
	}
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;

	}
	public function validate() {
		$this->form_validation->set_rules('closure', $this->lang->line('closure'), 'required');
		return parent::validate();
	}
}
?>