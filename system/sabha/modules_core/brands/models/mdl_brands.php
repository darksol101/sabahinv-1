<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Brands extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_brands';
		$this->primary_key = 'sst_brands.brand_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' brand_name ';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	public function getBrands($page){
		$searchtxt=$this->input->post('searchtxt');
		
		$where = array();
		if ($searchtxt){
			$where[] = 'LOWER(b.brand_name) LIKE "%'.$this->db->escape_like_str($searchtxt).'%"';
		}
		$limit = ' LIMIT '.(int)$page['start'].','.(int)$page['limit'];
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$orderby = ' ORDER BY b.brand_name ASC';
		$sql = 'SELECT b.brand_id AS brand_id,b.brand_name AS brand_name,b.brand_desc AS brand_desc,b.brand_status AS brand_status'
				. ' FROM '.$this->table_name.' AS b'
				. $where
				. $orderby
				. $limit
		;
		$sqltotal = 'SELECT count(b.brand_id) AS total'
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
	
	public function getBrandDetails($brand_id){
		$params=array(
					 "select"=>"brand_id,brand_name,brand_desc,brand_status",
					 "where"=>array("brand_id"=>$brand_id),
					 "limit"=>1
					 );
		$grouparr=$this->get($params);
		$group=$grouparr[0];
		return $group;
	}
	function getBrandOptions()
	{
		$params=array(
					 "select"=>"brand_name as text, brand_id as value",
					 "where"=>array("brand_status"=>1),
					 "order_by"=>'brand_name'
					 );
		$brand_options=$this->get($params);
		return $brand_options;
	}
}

?>