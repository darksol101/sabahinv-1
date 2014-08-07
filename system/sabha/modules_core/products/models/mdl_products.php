<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Products extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'sst_products';

		$this->primary_key = 'sst_products.product_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";

		$this->order_by = ' product_name ';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getProducts($page){
		$searchtxt=$this->input->post('searchtxt');
		
		if(!empty($searchtxt))
		{
			$this->db->like($this->table_name.'.product_name', $searchtxt);
			$this->db->or_like($this->mdl_brands->table_name.'.brand_name', $searchtxt);
		}
		
		$brand_id=$this->input->post('brand_id');
		if(!empty($brand_id))
		{
			$this->db->where($this->table_name.'.brand_id =', $brand_id);
		}
		$this->db->select('*');
		$this->db->from($this->table_name, $this->mdl_brands->table_name);
		$this->db->join($this->mdl_brands->table_name, $this->mdl_brands->table_name.'.brand_id='.$this->table_name.'.brand_id','left');
		$result_total = $this->db->get();
		$brand_id=$this->input->post('brand_id');
		$this->db->where($this->mdl_brands->table_name.'.brand_status =', '1');
		if(!empty($brand_id))
		{
			$this->db->where($this->table_name.'.brand_id =', $brand_id);
		}
		if(!empty($searchtxt))
		{
			$this->db->like($this->table_name.'.product_name', $searchtxt);
			$this->db->or_like($this->mdl_brands->table_name.'.brand_name', $searchtxt);
		}
		
		$this->db->select('*');
		$this->db->from($this->table_name, $this->mdl_brands->table_name);
		$this->db->join($this->mdl_brands->table_name, $this->mdl_brands->table_name.'.brand_id='.$this->table_name.'.brand_id','left');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		//echo $this->db->last_query();
		
		$list['total'] = $result_total->num_rows;
		$list['list'] = $result->result();
		return	$list;
	}
	function getProduct($product_id){
		$params = array(
						'select'=>'product_id,product_name,product_desc,brand_id,prod_category_id,product_status',
						'where'=>array('product_id'=>$product_id)
						);
		$arr=$this->get($params);
		$products = $arr[0];
		return $products;
	}
	function getProductOptions($brand_id=NULL)
	{
		
		if($brand_id){
				$params=array(
					  "select" => "product_id as value, product_name as text",
					  "order_by" => "text",
					  "where" =>array("brand_id"=>$brand_id)
					  );

		}else{
				$params=array(
					  "select" => "product_id as value, product_name as text",
					  "order_by" => "text"
					  );
		}
	
	
		$products=$this->get($params);
		
		return $products;
	}


	function checkBrandByProduct($brand_id)
	{
		$params=array(
					  "select" => "count(product_id) as total",
					  "where" => array('brand_id'=>(int)$brand_id)
					  );
		$arr = $this->get($params);
		$result = $arr[0]->total;
		return $result;
	}
	function checkCategoryByProduct($category_id)
	{
		$params=array(
					  "select" => "count(product_id) as total",
					  "where" => array('prod_category_id'=>(int)$category_id)
					  );
		$arr = $this->get($params);
		$result = $arr[0]->total;
		return $result;
	}
	function getProductsByBrand($brand_id)
	{
		$params=array(
					  "select" => "product_id as value, product_name as text",
					  "where" => array("product_status"=>1,'brand_id'=>(int)$brand_id),
					  "order_by" => "text"
					  );
		$products=$this->get($params);
		return $products;
	}
	function getProductsByBrands($brands){
		if(empty($brands)){
			$brands = '0';
		}
		$this->db->select('product_id,product_name,b.brand_name,b.brand_id');
		$this->db->from($this->table_name.' AS p',$this->mdl_brands->table_name.' AS b');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id = p.brand_id','left');
		$this->db->where("p.brand_id IN ($brands)");
		$this->db->order_by('b.brand_name ASC,p.product_name ASC');
		$result = $this->db->get();
		return $result->result();
	}
	function getProductOptionsByBrands($brands){
		if(empty($brands)){
			$brands = '0';
		}
		$this->db->select('product_id as value,CONCAT(brand_name," / ",product_name) as text,b.brand_name,b.brand_id',false);
		$this->db->from($this->table_name.' AS p',$this->mdl_brands->table_name.' AS b');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id = p.brand_id','left');
		$this->db->where("p.brand_id IN ($brands)");
		$this->db->order_by('b.brand_name ASC,p.product_name ASC');
		$result = $this->db->get();
		return $result->result();
	}
}

?>