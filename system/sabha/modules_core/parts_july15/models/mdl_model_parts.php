<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Model_Parts extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_prod_part_models';
		$this->table_name1 = 'sst_prod_part_model';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' part_number_name ASC';
	}
	function getModelParts($part_number){
		$this->db->select('pp.part_number,pp.model_number,p.brand_id,p.product_id');
		$this->db->from($this->table_name.' AS pp');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_number=pp.model_number');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id=pm.product_id');
		$this->db->where('pp.part_number =',$part_number);
		$result = $this->db->get();
		return $result->result();
	}

		
	//edited for model/part id process in table sst_prod_part_model
	function getModelPartsWithId($part_id){
		$this->db->select('pp.*,p.brand_id,p.product_id');
		$this->db->from('sst_prod_part_model AS pp');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=pp.model_id');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id=pm.product_id');
		$this->db->where('pp.part_id =',$part_id);
		$result = $this->db->get();
		return $result->result();
	}
	//get Model Numbers 

	public function getModels()
	{
		$result = $this->db->select('GROUP_CONCAT(model_number SEPARATOR ",") as model_number', false)
						->from('sst_prod_models')
						->get();
		return $result->row_array();

	}

	
	//get model id from model Number
	/*function getModelId($model_numbers){
	
		$model_ids=array();
		
		foreach ($model_numbers as $model_number){
			$this->db->select('model_id')
			->from('sst_prod_models')
			->where('model_number',$model_number);
			$id=$this->db->get()->row();
			$model_ids[]=$id->model_id;
		}
		return $model_ids;
	}*/
	
	function getModelsId($model_numbers){
	if(is_array($model_numbers)){
		$model_ids=array();
		foreach ($model_numbers as $model_number){
		
		$this->db->select('model_id')
			->from('sst_prod_models')
			->where('model_number',$model_number);
			$id=$this->db->get()->row();
			$model_ids[]=$id->model_id;
		}
		return $part_ids;

	}
	else
	{
		$this->db->select('model_id')
			->from('sst_prod_models')
			->where('model_number',$model_numbers);
			$id=$this->db->get()->row();
			return $id->model_id;
	}
		
}
	

	
	
	function delete(){
		$this->table_name = 'sst_prod_part_model';
		parent::delete();
	}
}

?>