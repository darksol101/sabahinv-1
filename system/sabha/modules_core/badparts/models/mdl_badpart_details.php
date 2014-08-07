<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Badpart_details extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_badpart_entry_details';
		$this->primary_key = 'sst_badpart_entry_details.badpart_entry_detail_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		//$this->order_by = ' part_number ASC';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	

	function getReturnPartDetails(){
		
		$sc_id=$this->input->post('sc_id');
		$part_number=$this->input->post('part_number');
		$fromdate = $this->input->post('from_date');
		$todate = $this->input->post('to_date');
		if($fromdate)
		{
			$this->db->where("DATE_FORMAT(DATE(badpart_entry_created_ts),'%d/%m/%Y') >=",$fromdate);
		}
		if($todate){
			$this->db->where("DATE_FORMAT(DATE(badpart_entry_created_ts),'%d/%m/%Y') <=",$todate);
		}
		$this->db->select('bpd.part_number,bpd.quantity,bpd.reason');
		$this->db->from($this->table_name.' AS bpd');
		$this->db->where('bpd.sc_id =',$sc_id);
		$this->db->where('bpd.part_number =',$part_number);
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
		
		}
	
}
?>