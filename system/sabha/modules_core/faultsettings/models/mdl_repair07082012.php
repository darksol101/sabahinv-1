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
	public function getRepairCodeById($repair_id)
	{
		$params = array(
						'select'=>'repair_code',
						'where'=>array('repair_id'=>$repair_id)
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
		$searchtxt=$this->input->post('searchtxt');
		$brand_id=$this->input->post('brand_id');
		$product_id=$this->input->post('product_id');
		$sort_field=$this->input->post('sort_field');
		
		$table_symptom = $this->mdl_symptom->table_name;
		$table_defect = $this->mdl_defect->table_name;
		$table_brand = $this->mdl_brands->table_name;
		$table_product = $this->mdl_products->table_name;
		$sort_type=($this->input->post('sort_type')=='')?'ASC':strtoupper($this->input->post('sort_type'));
		//for ordering
		if(!empty($sort_field)){
			$this->db->order_by("$sort_field $sort_type");
		}else{
			$this->db->order_by("brand_name ASC");
		}
		//search for text
		if(!empty($searchtxt)){
			$this->db->where("($this->table_name.repair_code LIKE '%$searchtxt%' OR $table_symptom.symptom_code LIKE '%$searchtxt%' OR  $this->table_name.repair_code LIKE '%$searchtxt%')");
		}
		//ends here
		//search for brand
		if(!empty($brand_id)){
			$this->db->where("$table_brand.brand_id =", $brand_id); 
		}
		//search for product
		if(!empty($product_id)){
			$this->db->where("$table_product.product_id =", $product_id); 
		}
		
		$this->db->select("$this->table_name.repair_id,$table_defect.symptom_id,$table_symptom.symptom_code,$this->table_name.repair_code,$this->table_name.repair_status,$this->table_name.defect_id,$table_defect.defect_code,$table_brand.brand_name,$table_product.product_name");
		$this->db->from($this->table_name,$table_brand);
	    $this->db->join($table_defect, "$table_defect.defect_id = $this->table_name.defect_id",'left');
	    $this->db->join($table_symptom, "$table_symptom.symptom_id = $table_defect.symptom_id",'left');
		$this->db->join($table_product, "$table_product.product_id = $table_symptom.product_id",'left');
		$this->db->join($table_brand, "$table_brand.brand_id = $table_product.brand_id",'left');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result=$this->db->get();
		//echo $this->db->last_query();
		
		//for counting total 
		//search for text
		if(!empty($searchtxt)){
			$this->db->where("($this->table_name.repair_code LIKE '%$searchtxt%' OR $table_symptom.symptom_code LIKE '%$searchtxt%' OR  $this->table_name.repair_code LIKE '%$searchtxt%')");
		}
		//ends here
		//search for brand
		if(!empty($brand_id)){
			$this->db->where("$table_brand.brand_id =", $brand_id); 
		}
		//search for product
		if(!empty($product_id)){
			$this->db->where("$table_product.product_id =", $product_id); 
		}
		$this->db->select("$this->table_name.repair_id");
		$this->db->from($this->table_name,$table_brand);
	    $this->db->join($table_defect, "$table_defect.defect_id = $this->table_name.defect_id",'left');
	    $this->db->join($table_symptom, "$table_symptom.symptom_id = $table_defect.symptom_id",'left');
		$this->db->join($table_product, "$table_product.product_id = $table_symptom.product_id",'left');
		$this->db->join($table_brand, "$table_brand.brand_id = $table_product.brand_id",'left');
		
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