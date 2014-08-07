<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Salesmaker extends MY_Model
{
	public function __construct(){
		parent::__construct();
		$this->table_name = 'sst_salesmaker';
		$this->primary_key		=	'sst_salesmaker.maker_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'maker_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
public function getmaxid(){
		$this->db->select('MAX(sales_id) AS maxid');
		$this->db->from($this->table_name);
		$result=$this->db->get();
		$maxid= $result->row();
		return $maxid;
	}
		

	public function getSalesMakerlist($page){
	
		$searchtxt=$this->input->post('searchtxt');
		

		if($searchtxt)
		{
			$this->db->where("(pt.sale_name LIKE '%$searchtxt%')");
		}
		
		$this->db->select('*');
		$this->db->from($this->table_name.' as pt');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		$parts = $result->result();
		

		if($searchtxt)
		{
			$this->db->where("(pt.sale_name LIKE '%$searchtxt%')");
		}
		$this->db->select('*');
		$this->db->from($this->table_name.' as pt');
		$result_total = $this->db->get();
		
		$list['list'] = $parts;
		$list['total'] = $result_total->num_rows();
		return	$list;
	}

	public function checksale(){
		//check sale exist or not

		
	}
	
	
}