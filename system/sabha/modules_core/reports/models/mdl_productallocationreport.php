<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Productallocationreport extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_auto_ass_prod';
	}
	function getProductAllocation($sc_id){
		$this->db->select("GROUP_CONCAT(b.brand_name, ' / ', p.product_name) as product_name",false);
		$this->db->from($this->table_name.' AS pa');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id=pa.product_id','inner');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id=p.brand_id','inner');
		$this->db->where("pa.sc_id IN($sc_id)");
		$this->db->group_by('pa.sc_id');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->row();
	}
}
?>