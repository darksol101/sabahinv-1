<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Symptom extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_symptoms';
		$this->primary_key = 'sst_symptoms.symptom_id';
		$this->order_by = 'symptom_code ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getSymptomOptions()
	{
		$params = array(
						'select'=>'symptom_id as value,symptom_code as text',
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getSymptomlist($page)
	{	
		$searchtxt=$this->input->post('searchtxt');
		if($searchtxt){
			$this->db->like("$this->table_name.symptom_code",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.symptom_id,$this->table_name.symptom_code,$this->table_name.symptom_description,$this->table_name.symptom_status");
	    $this->db->from($this->table_name);
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by("symptom_code DESC");
		$result = $this->db->get();
		$list['list'] = $result->result();
		
		//echo $this->db->last_query();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.symptom_code",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.symptom_id,$this->table_name.symptom_code,$this->table_name.symptom_description,$this->table_name.symptom_status");
	    $this->db->from($this->table_name);
		$result_total = $this->db->get();
		//echo $this->db->last_query();
		$total = $result_total->num_rows();
		
		$list['total'] = $total;
		return $list;
	}	
	public function getsymptomdetails($id)
	{
		$params=array(
					 "select"=>"symptom_id, symptom_code,symptom_description,symptom_status",
					 "where"=>array("symptom_id"=>(int)$id),
					 "limit"=>1
					 );
		$symptom_arr=$this->get($params);
		$symptom=$symptom_arr[0];

		return $symptom;
	}
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;
		

	}
	public function validate() {
		$this->form_validation->set_rules('symptom', $this->lang->line('symptom'), 'required');
		return parent::validate();
	}
}
?>