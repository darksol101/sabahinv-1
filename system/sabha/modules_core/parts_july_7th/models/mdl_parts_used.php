<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Parts_used extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_parts_used';
		$this->primary_key = 'sst_parts_used.parts_used_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' parts_used_stocks_id ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getPartUsedByCall($call_id){
		$this->db->select('pu.parts_used_id,pu.part_number,pu.part_quantity,pp.part_desc,c.company_title');
		$this->db->from($this->table_name.' AS pu');
		$this->db->join($this->mdl_parts->table_name.' AS pp','pp.part_number=pu.part_number');
		$this->db->join($this->mdl_company->table_name.' AS c','pu.company_id = c.company_id');
		$this->db->where('pu.call_id =',$call_id);
		$result = $this->db->get();
		$used_parts = $result->result();
		return $used_parts;
	}
	function getPartsUsedById($parts_used_id){
		$this->db->select('pu.parts_used_id');
		$this->db->from($this->table_name.' AS pu');
		$this->db->join($this->mdl_parts->table_name.' AS pp','pp.part_number=pu.part_number');
		$this->db->where('pu.parts_used_id =',$parts_used_id);
		$result = $this->db->get();
		$used_parts = $result->result();
		return $used_parts;
	}
	
	function generateUsedPartReport(){
		$sc_id = $this->input->post('sc_id');
		$engineer_id = $this->input->post('engineer_id');
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		if($sc_id){ 
			$this->db->where('c.sc_id',$sc_id);
		}
		if($engineer_id)
		{
			$this->db->where('e.engineer_id',$engineer_id);	
		}
		$this->db->select('pu.part_number,c.call_id,c.call_uid,pu.part_quantity,sc.sc_name,e.engineer_name,pu.part_number,p.part_desc,pu.parts_used_created_ts as created_date');
		$this->db->from($this->table_name.' AS pu');
		$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id=pu.call_id');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=c.sc_id');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id=c.engineer_id');
		$this->db->join($this->mdl_parts->table_name.' AS p','p.part_number=pu.part_number');
		$this->db->where("date(pu.parts_used_created_ts) BETWEEN '".$fromdate."' AND '".$todate."'");
		//$this->db->where('date(pu.parts_used_created_ts) >=',$fromdate);
		//$this->db->where('date(pu.parts_used_created_ts) <=',$todate);
		$result=$this->db->get();
		//echo $this->db->last_query();
		return  $result->result();
		
	}
	
	function getUsedPartsDownload()
{
		$sc_id = $this->session->userdata('sc_id_rl');
		$engineer_id = $this->session->userdata('engineer_id_rl');
		$fromdate = $this->session->userdata('fromdate_rl');
		$todate = $this->session->userdata('todate_rl');
		if($sc_id){ 
			$this->db->where('c.sc_id',$sc_id);
		}
		if($engineer_id)
		{
			$this->db->where('e.engineer_id',$engineer_id);	
		}
		$this->db->select('pu.parts_used_id,c.call_uid,pr.product_name,pm.model_number,sc.sc_name,e.engineer_name,pu.part_number,p.part_desc,pu.part_quantity,pu.parts_used_created_ts as created_date');
		$this->db->from($this->table_name.' AS pu');
		$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id=pu.call_id');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=c.sc_id');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id=c.engineer_id');
		$this->db->join($this->mdl_parts->table_name.' AS p','p.part_number=pu.part_number');
		
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id = c.model_id');
		$this->db->join($this->mdl_products->table_name.' AS pr','pr.product_id = pm.product_id');
		
		
		
		$this->db->where("date(pu.parts_used_created_ts) BETWEEN '".$fromdate."' AND '".$todate."'");
		$result=$this->db->get();
		//echo $this->db->last_query();
		return  $result->result();
									  
}
	
								
}

?>