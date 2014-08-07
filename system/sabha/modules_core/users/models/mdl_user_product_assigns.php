<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_User_product_assigns extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_user_product_assigns';
		$this->primary_key = 'sst_user_product_assigns.product_assign_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = 'product_assign_id';
	}
	function getBrandsByUser($user_id){
		$this->db->select('b.brand_id');
		$this->db->from($this->table_name.' AS up');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id=up.product_id','left');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id=p.brand_id','left');
		$this->db->where('up.user_id =',$user_id);
		$this->db->group_by('b.brand_id');
		$result = $this->db->get();
		return $result->result();
	}
	function getProductsByUser($user_id){
		$this->db->select('p.product_id');
		$this->db->from($this->table_name.' AS up');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id=up.product_id','left');
		$this->db->where('up.user_id =',$user_id);
		$this->db->group_by('p.product_id');
		$result = $this->db->get();
		return $result->result();
	}
}

?>