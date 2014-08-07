<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Sales_Return_Details extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_sales_return_details';
		$this->primary_key		=	'sst_sales_return_details.sales_return_details_id';
		$this->order_by			= 'sales_return_details_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	function salesReturnPartsDetails($sales__return_id){
		$this->db->select('srd.sales_return_details_id,srd.sales_return_id,srd.part_number,srd.part_id,srd.part_desc,srd.part_quantity,srd.part_rate,srd.sales_return_calc_price,srd.company_id,srd.part_return_quantity,c.company_title');
		$this->db->from($this->table_name.' AS srd');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = srd.company_id','left');
		$this->db->where('srd.sales_return_id',$sales__return_id);
		$result = $this->db->get();
		return  $result->result();
	}	
}
?>