<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Manual extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_uploads';
		$this->primary_key = 'sst_uploads.manual_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = 'manual_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getManuals($model_id){		
		$this->db->select('manual_id,model_id,file_name,full_path,file_ext,manual_description');
		$this->db->from($this->table_name);
		$this->db->where('model_id =',$model_id);
		$manuals = $this->db->get();
		if((int)$model_id>0){
		//echo $this->db->last_query();
			return $manuals->result();
		}else{
			return array();
		}
	}
	function checkManualsByModel($model_id)
	{
		$this->db->select('COUNT(manual_id) AS cnt');
		$this->db->from($this->table_name);
		$this->db->where('model_id =',$model_id);
		$result = $this->db->get();
		$arr = $result->row();
		return $arr->cnt;
	}
	function getProductManuals($page){
		$searchtxt=$this->input->post('searchtxt');
		$product_id=$this->input->post('product_id');
		$brand_id=$this->input->post('brand_id');
		if(!empty($searchtxt)){
			$this->db->like('m.model_number', $this->db->escape_like_str($searchtxt));
		}
		if($product_id>0){
			$this->db->where(array('m.product_id'=>$product_id));
		}
		if($brand_id>0){
			$this->db->where(array('p.brand_id'=>$brand_id));
		}
		$this->db->select('u.manual_id as manual_id,u.model_id as model_id,u.file_name as file_name,m.model_number as model_number,u.manual_description,p.product_name');
		$this->db->from($this->table_name.' AS u ');
		$this->db->join($this->mdl_productmodel->table_name.' AS m', 'm.model_id = u.model_id', 'left');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id = m.product_id','left');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id = p.brand_id','left');
		$this->db->group_by('u.model_id');
		$this->db->limit($page['limit'],$page['start']);
		$result = $this->db->get();
		//echo $this->db->last_query();
		//for counting tota;
		if(!empty($searchtxt)){
			$this->db->like('m.model_number', $this->db->escape_like_str($searchtxt));
		}
		if($product_id>0){
			$this->db->where(array('m.product_id'=>$product_id));
		}
		if($brand_id>0){
			$this->db->where(array('p.brand_id'=>$brand_id));
		}
		$this->db->select('u.model_id as model_id');
		$this->db->from($this->table_name.' AS u ');
		$this->db->join($this->mdl_productmodel->table_name.' AS m', 'm.model_id = u.model_id', 'left');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id = m.product_id','left');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id = p.brand_id','left');
		$this->db->group_by('u.model_id');
		$result_total = $this->db->get();
		$list['total'] = $result_total->num_rows();
		$list['list'] = $result->result();
		return $list;
	}
}

?>