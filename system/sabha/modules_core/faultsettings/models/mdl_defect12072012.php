<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Defect extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_defects';
		$this->primary_key = 'sst_defects.defect_id';
		$this->order_by = 'defect_code ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getDefectOptions()
	{
		$params = array(
						'select'=>'defect_id as value,defect_code as text',
						'where'=>array('defect_status'=>1),
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getDefectOptionsBySymptom($symptom_id)
	{
		$params = array(
						'select'=>'defect_id as value,defect_code as text',
						'where'=>array('defect_status'=>1,'symptom_id'=>$symptom_id),
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getDefectlist($page)
	{
		$table_symptom = $this->mdl_symptom->table_name;
		$searchtxt=$this->input->post('searchtxt');
		if(!empty($searchtxt)){
			$this->db->or_like("$this->table_name.defect_code", $searchtxt); 
		}
		if(!empty($searchtxt)){
			$this->db->or_like("$table_symptom.symptom_code", $searchtxt); 
		}
		$this->db->select("$this->table_name.defect_id,$this->table_name.symptom_id,$table_symptom.symptom_code,$this->table_name.defect_code,$this->table_name.defect_description,$this->table_name.defect_status");
		$this->db->from($this->table_name);
	    $this->db->join($table_symptom, "$table_symptom.symptom_id = $this->table_name.symptom_id",'left');
		$this->db->order_by('symptom_code ASC');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result=$this->db->get();
		//echo $this->db->last_query();
		
		//for counting total 
		if(!empty($searchtxt)){
			$this->db->like("$this->table_name.defect_code", $searchtxt); 
		}
		$this->db->select("$this->table_name.defect_id");
		$this->db->from($this->table_name);
	    $this->db->join($table_symptom, "$table_symptom.symptom_id = $this->table_name.symptom_id",'left');
		$this->db->order_by('symptom_code ASC');
		
		$total =$this->db->count_all_results();
		
		$defects['list'] = $result->result();
		$defects['total'] = $total;
		return $defects;
		
		}	
	public function getdefectdetails($id)
	{
				$params=array(
					 "select"=>"defect_id, defect_code,defect_description,defect_status,symptom_id",
					 "where"=>array("defect_id"=>(int)$id),
					 "limit"=>1
					 );
		$defect_arr=$this->get($params);
		$defect=$defect_arr[0];

		return $defect;
	}
	
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;
		

	}
	public function validate() {
		$this->form_validation->set_rules('defect', $this->lang->line('defect'), 'required');
		return parent::validate();
	}
	}
?>