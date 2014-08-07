<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Badparts_order_parts extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_badparts_order_parts';
		$this->primary_key		=	'sst_badparts_order_parts.badparts_order_part_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'badparts_order_part_id';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	
	function orderparts($id){
		$this->db->select('pd.part_number,pd.part_quantity,pd.badparts_order_part_id,part_desc,pd.badparts_order_part_status');
		$this->db->from($this->table_name.' AS pd');
		$this->db->join($this->mdl_parts->table_name.' AS p','pd.part_number = p.part_number','LEFT');
		$this->db->where('badparts_order_id =',$id);
		$result= $this->db->get();
		$parts = $result->result();
		
		return $parts;
		
		
		
		}
	
	
	
}
?>