<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Productassign extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_auto_ass_prod';
	}
	function getBrandAssignsBySc($sc_id){
		$this->db->select('b.brand_id');
		$this->db->from($this->mdl_brands->table_name.' AS b');
		$this->db->join($this->mdl_products->table_name.' AS p','p.brand_id=b.brand_id','left');
		$this->db->join($this->table_name.' pa','pa.product_id=p.product_id','left');
		$this->db->where('pa.sc_id =',$sc_id);
		$this->db->group_by('b.brand_id');
		$result = $this->db->get();
		return $result->result();
	}
	function getProductAssignsBySc($sc_id){
		$this->db->select('p.product_id');
		$this->db->from($this->mdl_brands->table_name.' AS b');
		$this->db->join($this->mdl_products->table_name.' AS p','p.brand_id=b.brand_id','left');
		$this->db->join($this->table_name.' pa','pa.product_id=p.product_id','left');
		$this->db->where('pa.sc_id =',$sc_id);
		$this->db->group_by('p.product_id');
		$result = $this->db->get();
		return $result->result();
	}
	function getProductModelsAssignsBySc($sc_id){
		$this->db->select('pm.model_id');
		$this->db->from($this->mdl_brands->table_name.' AS b');
		$this->db->join($this->mdl_products->table_name.' AS p','p.brand_id=b.brand_id','left');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.product_id=p.product_id','left');
		$this->db->join($this->table_name.' ma','ma.model_id=pm.model_id','left');
		$this->db->where('ma.sc_id =',$sc_id);
		$this->db->group_by('pm.model_id');
		$result = $this->db->get();
		return $result->result();
	}
}
?>