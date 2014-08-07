<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Productmodel extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_prod_models';
		$this->primary_key = 'sst_prod_models.model_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = 'model_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	public function getProductModels($page){
		$searchtxt=$this->input->post('searchtxt');
		$product_id=$this->input->post('product_id');
		/**
		for calculating total rows of data
		*/
		if ($searchtxt){
			$this->db->like($this->table_name.'.model_number', $this->db->escape_like_str($searchtxt));
			$this->db->or_like($this->mdl_brands->table_name.'.brand_name', $this->db->escape_like_str($searchtxt));
			$this->db->or_like($this->mdl_products->table_name.'.product_name', $this->db->escape_like_str($searchtxt));
		}
		if((int)$product_id>0){
			$this->db->where($this->mdl_products->table_name.'.product_id =', $product_id);
		}
		$this->db->where($this->mdl_brands->table_name.'.brand_status =', '1');
		$this->db->select($this->table_name.'.model_id as model_id');
		$this->db->from($this->table_name,$this->mdl_products->table_name,$this->mdl_brands->table_name);
		$this->db->join($this->mdl_products->table_name,$this->mdl_products->table_name.'.product_id='.$this->table_name.'.product_id','left');
		$this->db->join($this->mdl_brands->table_name, $this->mdl_brands->table_name.'.brand_id='.$this->table_name.'.brand_id','left');
		$result_total = $this->db->get();
		/** ends here **/
		/**
		get list of all models of products
		*/
		if ($searchtxt){
			$this->db->like($this->table_name.'.model_number', $this->db->escape_like_str($searchtxt));
			$this->db->or_like($this->mdl_brands->table_name.'.brand_name', $this->db->escape_like_str($searchtxt));
			$this->db->or_like($this->mdl_products->table_name.'.product_name', $this->db->escape_like_str($searchtxt));
		}
		if((int)$product_id>0){
			$this->db->where($this->mdl_products->table_name.'.product_id =', $product_id);
		}
		$this->db->where($this->mdl_brands->table_name.'.brand_status =', '1');
		$this->db->select($this->table_name.'.model_id as model_id,'.
						  $this->table_name.'.model_number as model_number,'.
						  $this->table_name.'.model_desc as model_desc,'.
						  $this->mdl_brands->table_name.'.brand_name as brand_name,'.
						  $this->mdl_products->table_name.'.product_name'  
						  );
		$this->db->from($this->table_name,$this->mdl_products->table_name,$this->mdl_brands->table_name);
		$this->db->join($this->mdl_products->table_name,$this->mdl_products->table_name.'.product_id='.$this->table_name.'.product_id','left');
		$this->db->join($this->mdl_brands->table_name, $this->mdl_brands->table_name.'.brand_id='.$this->table_name.'.brand_id','left');
		$this->db->order_by('model_id desc');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		//echo $this->db->last_query();
		/** ends here*/

		$list['total'] =$result_total->num_rows;
		$list['list'] = $result->result();
		return	$list;
	}
		
	public function getModelDetails($id){
		$params=array(
					 "select"=>"model_id, model_number,model_warranty, brand_id, product_id, model_desc,model_status",
					 "where"=>array("model_id"=>$id),
					 "limit"=>1
					 );
		$modelarr=$this->get($params);
		$details=$modelarr[0];
		return $details;
	}
	public function getModelOptions(){
		$params=array(
					  "select" => "model_id as value, model_number as text",
					  "where" => "model_status = 1",
					  "order_by" => "text"
					  );
		$models=$this->get($params);
		return $models;
	}
	public function getModelOptionsByProduct($product_id){
		$params=array(
					  "select" => "model_id as value, model_number as text",
					  "where" => "product_id =".(int)$product_id,
					  "order_by" => "text"
					  );
		$models=$this->get($params);
		return $models;
	}
	function checkProductByModel($product_id)
	{
		$params=array(
					  "select" => "count(model_id) as total",
					  "where" => array('product_id'=>(int)$product_id)
					  );
		$arr = $this->get($params);
		$result = $arr[0]->total;
		return $result;
	}
	public function getModelsByProduct($product_id){
		/*$params=array(
					  "select" => "model_id,model_number",
					  "where" => array("product_id"=>$product_id,"model_status"=>"1"),
					  "order_by" => "model_number"
					  );
		$models=$this->get($params);*/
		
		$this->db->select('pm.model_id,pm.model_number,c.prod_category_name');
		$this->db->from($this->table_name.' as pm',$this->mdl_category->table_name.' as c');
		$this->db->join($this->mdl_products->table_name.' as p','p.product_id=pm.product_id');
		$this->db->join($this->mdl_category->table_name.' as c','c.prod_category_id=p.prod_category_id');
		$this->db->where("pm.product_id = ".(int)$product_id);
		$this->db->where("pm.model_status = 1");
		$result=$this->db->get();
		$models =$result->result();
		return $models;
	}
	function checkModelNumber($id,$brand_id,$product_id,$model_number)
	{
		$this->db->select('count(model_number) as total');
		$this->db->from($this->table_name);
		$this->db->where('model_number = "'.$model_number.'"');
		if((int)$brand_id>0){
			$this->db->where("brand_id = ".(int)$brand_id);
		}
		if((int)$product_id>0){
			$this->db->where("product_id = ".(int)$product_id);
		}
		if((int)$id>=0){
			$this->db->where("model_id != ".(int)$id);
		}
		$result=$this->db->get();
		//echo $this->db->last_query();		
		$arr = $result->row();
		return $arr->total;
	}
}

?>