<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Salesmakerlist extends MY_Model
{
	public function __construct(){
		parent::__construct();
		$this->table_name = 'sst_salesmaker_list';
		$this->primary_key		=	'sst_salesmaker_list.assigned_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'assigned_id';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	
	public function getmaxid(){
		$this->db->select('MAX(sales_id) AS maxid');
		$this->db->from($this->table_name);
		$result=$this->db->get();
		$maxid= $result->row();
		return $maxid;
	}
		
	public function getList($maker_id,$table_name,$joinOn,$get)
	{
		$ifall=$this->db->select('assigned_to')->from($this->table_name)->where('maker_id', $maker_id)->get()->row();
		
		if($ifall->assigned_to == 0){
			
			$rr=$this->db->select($get.' as name')->from($table_name)->get();
			$ss=$rr->result();
			return $ss;
		}
		else{
		$rr=$this->db->select('ss.assigned_to,bb.'.$get.' as name')
			->from($this->table_name.' ss')
			->join($table_name.' bb', 'bb.'.$joinOn.'= ss.assigned_to', 'inner')
			->where('ss.maker_id',$maker_id)
			->get();

		$ss=$rr->result();
		if($rr->num_rows>0){
			return $ss;
		}
		
		}
		
	}

	public function getSaleMakerPartList($maker_id){
		$result = $this->db->select('ss.part_id,m.model_id,m.product_id,m.brand_id')
						->from($this->table_name.' ss')
						->join('sst_prod_part_model pm', 'pm.part_id = ss.part_id', 'left')
						->join('sst_prod_models m', 'm.model_id = pm.model_id', 'left')
						->where('ss.maker_id',$maker_id)
						->get()->result();
		return $result;				

	}
	
}