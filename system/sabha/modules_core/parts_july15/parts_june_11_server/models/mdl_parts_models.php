<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Parts_models extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_prod_part_model';
		$this->table_name1 = 'sst_prod_part_models';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' part_id ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	function getParts($page){
		$model_number = $this->input->post('model_number');
		$arr = explode(",",$model_number);
		$str = '';
		$arr1 = array();
		foreach($arr as $model){
			$arr1[] = '"'.$model.'"';
		}
		$model_number = implode(",",$arr1);
		$this->db->select('DISTINCT(pr.part_number),pm.model_number,b.brand_name,p.product_name,pr.part_desc,pr.part_landing_price,pr.part_sc_price,pr.part_customer_price');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','mdl.model_id= pm.model_id');
		$this->db->join($this->mdl_brands->table_name.' AS b','pm.brand_id=b.brand_id');
		$this->db->join($this->mdl_products->table_name.' AS p','pm.product_id=p.product_id');
		$this->db->join($this->mdl_parts->table_name.' AS pr','pr.part_id = mdl.part_id');
		$this->db->from($this->table_name.' AS mdl');
		$this->db->where("pm.model_number IN ($model_number)");
		$this->db->order_by('pm.model_number ASC');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		
		$result = $this->db->get();
		$parts = $result->result();
		
		//echo $this->db->last_query();
		$this->db->select('DISTINCT(pr.part_number),pm.model_number,b.brand_name,p.product_name');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','mdl.model_id= pm.model_id');
		$this->db->join($this->mdl_brands->table_name.' AS b','pm.brand_id=b.brand_id');
		$this->db->join($this->mdl_products->table_name.' AS p','pm.product_id=p.product_id');
		$this->db->join($this->mdl_parts->table_name.' AS pr','pr.part_id = mdl.part_id');
		$this->db->from($this->table_name.' AS mdl');
		$this->db->where("pm.model_number IN ($model_number)");
		$this->db->order_by('pm.model_number ASC');
		$result_total = $this->db->get();
		$list['list'] = $parts;
		$list['total'] = $result_total->num_rows();

		return	$list;
	}
	
	
	function getParts_excel(){

		$model_number = $this->input->post('model_number');


		$model_number = array_map('addquote',$model_number);
		
		$str = implode(",",$model_number);
		$this->db->select('DISTINCT(pr.part_number),b.brand_name,p.product_name,pm.model_number,pr.part_desc,pr.part_sc_price,pr.part_customer_price');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','mdl.model_id= pm.model_id');
		$this->db->join($this->mdl_brands->table_name.' AS b','pm.brand_id=b.brand_id');
		$this->db->join($this->mdl_products->table_name.' AS p','pm.product_id=p.product_id');
		$this->db->join($this->mdl_parts->table_name.' AS pr','pr.part_id = mdl.part_id');
		$this->db->from($this->table_name.' AS mdl');
		$this->db->where("pm.model_number IN (".$str.")");
		$this->db->order_by('pm.model_number ASC');
		$result = $this->db->get();
		$list['result']=$result;

		return	$list;
		}
		
		
		function getPartModelOptions(){
			$this->db->select('p.part_number , m.model_number');
			$this->db->from($this->table_name.' as pm');
			$this->db->join('sst_prod_parts as p', 'p.part_id= pm.part_id', 'LEFT');	
			$this->db->join('sst_prod_models as m', 'm.model_id= pm.model_id', 'LEFT');
			$result=$this->db->get();
			/*dump($this->db->last_query()); die();*/
			return $result->result();
			}

	 	function getPartsByModel($model_id){
	 		$where="pm.model_id in($model_id)";
			$result=$this->db->select('p.part_number,p.part_id,pm.model_id')
						->from($this->table_name.' as pm')
						->join('sst_prod_parts as p', 'p.part_id= pm.part_id', 'INNER')
						->where($where)
						->get();
			/*dump($this->db->last_query());*/
			return $result->result();
		}


		}

		function addquote($val){
			return "'".$val."'";
		}


