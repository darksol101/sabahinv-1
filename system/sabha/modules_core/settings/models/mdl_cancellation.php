<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Cancellation extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_reason_cancellation';
		$this->primary_key = 'sst_reason_cancellation.cancellation_id';
		$this->order_by = 'cancellation ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getCancellationOptions()
	{
		$params = array(
						'select'=>'cancellation_id as value,cancellation as text',
						'where'=>array('cancellation_status'=>1),
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getCancellationlist($page)
	{	
		$searchtxt=$this->input->post('searchtxt');
		if($searchtxt){
			$this->db->like("$this->table_name.cancellation",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.cancellation_id,$this->table_name.cancellation,$this->table_name.cancellation_status");
	    $this->db->from($this->table_name);
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by("cancellation DESC");
		$result = $this->db->get();
		$list['list'] = $result->result();
		
		//echo $this->db->last_query();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.cancellation",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.cancellation,$this->table_name.cancellation");
	    $this->db->from($this->table_name);
		$result_total = $this->db->get();
		//echo $this->db->last_query();
		$total = $result_total->num_rows();
		
		$list['total'] = $total;
		return $list;
	}	
	public function getcancellationdetails($id)
	{
		$params=array(
					 "select"=>"cancellation_id, cancellation",
					 "where"=>array("cancellation_id"=>(int)$id),
					 "limit"=>1
					 );
		$cancellation_arr=$this->get($params);
		$cancellation=$cancellation_arr[0];

		return $cancellation;
	}
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;

	}
	public function validate() {
		$this->form_validation->set_rules('cancellation', $this->lang->line('cancellation'), 'required');
		return parent::validate();
	}
public function getcancellationreason($id)
	{
		$this->db->select("cancellation");
		$this->db->from($this->table_name);
		$this->db->where("cancellation_id =",(int)$id);
		$result= $this->db->get();
	    //print_r( $result->row());
		return $result->row();
		
}
}
?>