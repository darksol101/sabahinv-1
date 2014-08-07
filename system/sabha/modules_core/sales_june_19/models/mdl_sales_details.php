<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Sales_Details extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_sales_details';
		$this->primary_key		=	'sst_sales_details.sales_details_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'sales_details_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	function salesparts($sales_id){
		$this->load->model('company/mdl_company');
		$this->db->select('maker_id,sales_details_id,s.part_id,s.part_number,part_quantity,part_desc as part_description,price_with_discount as dis_rate,price as part_rate,s.company_id,c.company_title,s.call_id');
		$this->db->from($this->table_name.' AS s');
		$this->db->join($this->mdl_parts->table_name.' AS pr','pr.part_id = s.part_id','INNER');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = s.company_id','INNER');
		$this->db->where('s.sales_id =',$sales_id);
		$result = $this->db->get();
		return  $result->result();
	}

    function salesparts2($sales_id){
        $this->load->model('company/mdl_company');
        $this->db->select('sales_details_id,pr.part_number,part_quantity,part_desc as part_description,price_with_discount as dis_rate,maker_id,price as part_rate,s.company_id,c.company_title');
        $this->db->from($this->table_name.' AS s');
        $this->db->join($this->mdl_parts->table_name.' AS pr','pr.part_id = s.part_id','INNER');
        $this->db->join($this->mdl_company->table_name.' AS c','c.company_id = s.company_id','INNER');
        $this->db->where('s.sales_id =',$sales_id);
        $result = $this->db->get();
        return  $result->result();
    }

    function salesparttotrow($sales_id){
		
		$this->db->select('sales_details_id,s.part_number,part_quantity,part_desc as part_description,price as part_rate');
		$this->db->from($this->table_name.' AS s');
		$this->db->join($this->mdl_parts->table_name.' AS pr','pr.part_number = s.part_number','INNER');
		$this->db->where('s.sales_id =',$sales_id);
		$result = $this->db->get();
		return  $result->num_rows();	
	}

    function getSalesId($call_id){
	$this->db->select('sales_id');
	$this->db->from($this->table_name.' AS s');
	$this->db->where('s.call_id =',$call_id);
	$this->db->group_by('sales_id');
	$result = $this->db->get();
	$sales_id= $result->row();
	if($result->num_rows()==0){
			$sales_id = new stdClass();
			$sales_id->sales_id= 0;
		}
		return $sales_id;	
	}
}
?>