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
	public function getSymptomOptionsByProduct($product_id)
	{
		$params = array(
						'select'=>'symptom_id as value,symptom_code as text',
						//'where'=>array('product_id'=>intval($product_id)),
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
		if((int)$this->input->post('product_id')>0){
			$this->db->where($this->table_name.'.product_id ='.$this->input->post('product_id'));
		}
		if((int)$this->input->post('brand_id')>0){
			$this->db->where($this->mdl_products->table_name.'.brand_id ='.$this->input->post('brand_id'));
		}
		$this->db->select("$this->table_name.symptom_id,$this->table_name.symptom_code,$this->table_name.symptom_description,$this->table_name.symptom_status,".$this->mdl_products->table_name.".product_name");
	    $this->db->from($this->table_name);
		$this->db->join($this->mdl_products->table_name,$this->mdl_products->table_name.'.product_id = '.$this->table_name.'.product_id','left');
		$this->db->join($this->mdl_brands->table_name,$this->mdl_brands->table_name.'.brand_id = '.$this->mdl_products->table_name.'.brand_id','left');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by("symptom_code DESC");
		$result = $this->db->get();
		$list['list'] = $result->result();
		
		//echo $this->db->last_query();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.symptom_code",$this->db->escape_like_str($searchtxt));
		}
		if($this->input->post('product_id')>0){
			$this->db->where($this->table_name.'.product_id ='.$this->input->post('product_id'));
		}
		if($this->input->post('brand_id')>0){
			$this->db->where($this->mdl_products->table_name.'.brand_id ='.$this->input->post('brand_id'));
		}
		$this->db->select("$this->table_name.symptom_id");
	    $this->db->from($this->table_name);
		$this->db->join($this->mdl_products->table_name,$this->mdl_products->table_name.'.product_id = '.$this->table_name.'.product_id','left');
		$this->db->join($this->mdl_brands->table_name,$this->mdl_brands->table_name.'.brand_id = '.$this->mdl_products->table_name.'.brand_id','left');
		$result_total = $this->db->get();
		//echo $this->db->last_query();
		$total = $result_total->num_rows();
		
		$list['total'] = $total;
		return $list;
	}	
	public function getsymptomdetails($id)
	{
		$params=array(
					 "select"=>"symptom_id, symptom_code,symptom_description,symptom_status,".$this->table_name.".product_id,".$this->mdl_products->table_name.".brand_id",
					 'joins'=>array('sst_products'=>array("'".$this->mdl_products->table_name.'.product_id='.$this->table_name.'.product_id','left')),
					 "where"=>array("symptom_id"=>(int)$id),
					 "limit"=>1
					 );
		$symptom_arr=$this->get($params);
		$symptom=$symptom_arr[0];
		//echo $this->db->last_query();

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