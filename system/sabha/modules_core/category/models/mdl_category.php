<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Category extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_prod_categories';
		$this->primary_key = 'sst_prod_categories.prod_category_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' prod_category_name ';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getCategories($page){
		$searchtxt=$this->input->post('searchtxt');
		
		$where = array();
		if ($searchtxt){
			$where[] = 'LOWER(b.prod_category_name) LIKE "%'.$this->db->escape_like_str($searchtxt).'%"';
		}
		$limit = ' LIMIT '.(int)$page['start'].','.(int)$page['limit'];
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$orderby = ' ORDER BY b.prod_category_name ASC';
		$sql = 'SELECT b.prod_category_id AS prod_category_id,b.prod_category_name AS prod_category_name,b.prod_category_desc AS prod_category_desc,b.prod_category_status AS prod_category_status'
				. ' FROM '.$this->table_name.' AS b'
				. $where
				. $orderby
				. $limit
		;
		$sqltotal = 'SELECT count(b.prod_category_id) AS total'
				. ' FROM '.$this->table_name.' AS b'
				. $where
		;
		$result = $this->db->query($sql);
		//echo $this->db->last_query();
		$resultotal = $this->db->query($sqltotal);
		$arr = $resultotal->row();
		$list['total'] = $arr->total;
		$list['list'] = $result->result();
		return	$list;
	}
	
	public function getCategoryDetails($id){
		$params=array(
					 "select"=>"prod_category_id,prod_category_name,prod_category_desc,prod_category_status",
					 "where"=>array("prod_category_id"=>$id),
					 "limit"=>1
					 );
		$grouparr=$this->get($params);
		$group=$grouparr[0];
		return $group;
	}
	function getCategoryOptions()
	{
		$params=array(
					 "select"=>"prod_category_name as text, prod_category_id as value",
					 "where"=>array("prod_category_status"=>1),
					 "order_by"=>'text'
					 );
		$brand_options=$this->get($params);
		return $brand_options;
	}
}

?>