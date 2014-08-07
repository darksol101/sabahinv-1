<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_reminderreport extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_reminders';
		$this->primary_key = 'sst_reminders.reminder_id';
		$this->logged=$this->createlogtable($this->table_name);
	}	


	function getReminderReport()
	{
		$call_uid=$this->input->post('call_uid');
		$sc_id=$this->input->post('sc_id');
	
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$product_id = $this->input->post('product_id');
		$this->load->helper('mcb_date');
		
		$where = array();
		if(!empty($call_uid)){
			$this->db->like('c.call_uid',$call_uid);
		}
		
		if($sc_id!=''){
			$this->db->where('c.sc_id IN ('.$sc_id.')');
		}
		if($product_id){
			$this->db->where('pm.product_id IN ('.$product_id.')');
		}
	
		if($from_date){
			$this->db->where('DATE(r.reminder_dt) >=',date("Y-m-d",date_to_timestamp($from_date)));
		}
		if($to_date){
			$this->db->where('DATE(r.reminder_dt) <=',date("Y-m-d",date_to_timestamp($to_date)));
		}
		//filter for user assigned products
	
		$this->db->select('c.call_uid,sc.sc_name,r.reminder_remarks,r.reminder_dt');
		$this->db->from($this->table_name.' AS r');
		$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id=r.call_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=c.sc_id','left');	
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN('.$product_id.')');
		$this->db->order_by("c.call_id ASC");
	
		$result = $this->db->get();
		//echo $this->db->last_query();
		
		$list['list'] = $result->result();
		return $list;		
	}	
}
?>
