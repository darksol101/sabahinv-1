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
	public function getDefectCodeById($defect_id)
	{
		$params = array(
						'select'=>'defect_code',
						'where'=>array('defect_id'=>$defect_id)
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
		$table_brand = $this->mdl_brands->table_name;
		$table_product = $this->mdl_products->table_name;
		$table_brand = $this->mdl_brands->table_name;
		$product_id = $this->input->post('product_id');
		$brand_id = $this->input->post('brand_id');
		$symptom_id = $this->input->post('symptom_id');
		
		$searchtxt=$this->input->post('searchtxt');
		if(!empty($searchtxt)){
			//$this->db->or_like("$this->table_name.defect_code", $searchtxt);
			$this->db->like("LOWER($this->table_name.defect_code)", strtolower($searchtxt)); 
		}
		if(!empty($searchtxt)){
			//$this->db->or_like("$table_symptom.symptom_code", $searchtxt); 
			$this->db->or_like("LOWER($table_symptom.symptom_code)", strtolower($searchtxt)); 
		}
		if(!empty($symptom_id)){
			$this->db->where("$table_symptom.symptom_id =", $symptom_id);
		}
		if(!empty($product_id)){
			$this->db->where("$table_symptom.product_id =", $product_id);
		}
		if(!empty($brand_id)){
			$this->db->where("$table_product.brand_id =", $brand_id);
		}
		$this->db->select("$this->table_name.defect_id,$this->table_name.symptom_id,$table_symptom.symptom_code,$this->table_name.defect_code,$this->table_name.defect_description,$this->table_name.defect_status,$table_product.product_name,$table_brand.brand_name");
		$this->db->from($this->table_name);
	    $this->db->join($table_symptom, "$table_symptom.symptom_id = $this->table_name.symptom_id",'left');
		$this->db->join($table_product,"$table_product.product_id = $table_symptom.product_id");
		$this->db->join($table_brand,"$table_brand.brand_id = $table_product.brand_id");
		$this->db->order_by('symptom_code ASC');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result=$this->db->get();
		//echo $this->db->last_query();
		
		//for counting total 
		if(!empty($searchtxt)){
			$this->db->like("LOWER($this->table_name.defect_code)", strtolower($searchtxt)); 
		}
		if(!empty($searchtxt)){
			$this->db->or_like("LOWER($table_symptom.symptom_code)", strtolower($searchtxt)); 
		}
		if(!empty($symptom_id)){
			$this->db->where("$table_symptom.symptom_id =", $symptom_id);
		}
		if(!empty($product_id)){
			$this->db->where("$table_symptom.product_id =", $product_id);
		}
		if(!empty($brand_id)){
			$this->db->where("$table_product.brand_id =", $brand_id);
		}
		$this->db->select("$this->table_name.defect_id");
		$this->db->from($this->table_name);
	    $this->db->join($table_symptom, "$table_symptom.symptom_id = $this->table_name.symptom_id",'left');
		$this->db->join($table_product,"$table_product.product_id = $table_symptom.product_id");
		$this->db->order_by('symptom_code ASC');
		
		$total =$this->db->count_all_results();
		
		$defects['list'] = $result->result();
		$defects['total'] = $total;
		return $defects;
		
		}	
	public function getdefectdetails($defect_id)
	{
		$this->db->select('d.defect_id, d.defect_code,d.defect_description,d.defect_status,d.symptom_id,s.product_id,p.brand_id');
		$this->db->from($this->table_name.' AS d');
		$this->db->join($this->mdl_symptom->table_name.' AS s','s.symptom_id=d.symptom_id','left');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id=s.product_id','left');
		$this->db->where("d.defect_id =", $defect_id);
		$result = $this->db->get();
		$defect = $result->row();
		/*$params=array(
			 "select"=>"defect_id, defect_code,defect_description,defect_status,symptom_id",
			 "where"=>array("defect_id"=>(int)$id),
			 "limit"=>1
			 );
		$defect_arr=$this->get($params);
		$defect=$defect_arr[0];*/

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