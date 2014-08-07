<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Repair extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_repairs';
		$this->primary_key = 'sst_repairs.repair_id';
		$this->order_by = 'repair_code ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}

	public function getRepairOptions()
	{
		$params = array(
						'select'=>'repair_id as value,repair_code as text',
						'where'=>array('repair_status'=>1),
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getRepairOptionsByDefect($defect_id)
	{
		$params = array(
						'select'=>'repair_id as value,repair_code as text',
						'where'=>array('repair_status'=>1,'defect_id'=>$defect_id),
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getRepairlist($page)
	{
		$table_symptom = $this->mdl_symptom->table_name;
		$table_defect = $this->mdl_defect->table_name;
		$searchtxt=$this->input->post('searchtxt');
		if(!empty($searchtxt)){
			$this->db->or_like("$this->table_name.repair_code", $searchtxt); 
		}
		if(!empty($searchtxt)){
			$this->db->or_like("$table_symptom.symptom_code", $searchtxt); 
		}
		if(!empty($searchtxt)){
			$this->db->or_like("$table_defect.defect_code", $searchtxt); 
		}
		$this->db->select("$this->table_name.repair_id,$table_defect.symptom_id,$table_symptom.symptom_code,$this->table_name.repair_code,$this->table_name.repair_description,$this->table_name.repair_status,$this->table_name.defect_id,$table_defect.defect_code");
		$this->db->from($this->table_name);
	    $this->db->join($table_defect, "$table_defect.defect_id = $this->table_name.defect_id",'left');
	    $this->db->join($table_symptom, "$table_symptom.symptom_id = $table_defect.symptom_id",'left');
		$this->db->order_by('symptom_code ASC');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result=$this->db->get();
		//echo $this->db->last_query();
		
		//for counting total 
		if(!empty($searchtxt)){
			$this->db->like("$this->table_name.repair_code", $searchtxt); 
		}
		$this->db->select("$this->table_name.repair_id");
		$this->db->from($this->table_name);
	    $this->db->join($table_defect, "$table_defect.defect_id = $this->table_name.defect_id",'left');
	    $this->db->join($table_symptom, "$table_symptom.symptom_id = $table_defect.symptom_id",'left');
		$this->db->order_by('symptom_code ASC');
		
		$total =$this->db->count_all_results();
		
		$repairs['list'] = $result->result();
		$repairs['total'] = $total;
		return $repairs;
		
		}	
	public function getrepairdetails($repair_id)
	{
		$this->db->select('r.repair_id,r.repair_code,r.repair_description,r.repair_status,s.symptom_id,r.defect_id');
		$this->db->from($this->table_name.' as r',$this->mdl_defect->table_name.' as d',$this->mdl_symptom->table_name.' as s');
		$this->db->join($this->mdl_defect->table_name.' as d','d.defect_id=r.defect_id','left');
		$this->db->join($this->mdl_symptom->table_name.' as s','s.symptom_id=d.symptom_id','left');
		$this->db->where('r.repair_id ='.$repair_id);
		$result = $this->db->get();
		return $result->row();
	}
	
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;
		

	}
	public function validate() {
		$this->form_validation->set_rules('repair', $this->lang->line('repair'), 'required');
		return parent::validate();
	}
	}
?>