<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Call_happy_record extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls_happy_record';
		$this->primary_key = 'sst_calls_happy_record.happy_date_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' call_happy_date';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	
	 function saverecord(){
		//die('asdasd');
		$call_id = $this->input->post('call_id');
		$callrecord['call_id']= $this->input->post('call_id');
		$callrecord['remark'] = $this->input->post('remark');
		$callrecord['date']= date ('Y-m-d H:i:s');
		$callrecord['call_happy_record_created_by']= $this->session->userdata('user_id');
		$callrecord['call_happy_record_created_ts']= date('Y-m-d H:i:s');
		$this->save($callrecord);
		redirect('callcenter/callregistration/'.$call_id);
		
		}
	
	
	
	 function getHappyCallRecord($call_id)
	{
	$this->db->select('date,remark');
	$this->db->from($this->table_name.' AS cr');
	$this->db->where('cr.call_id =',$call_id);
	$result= $this->db->get();
	return $result->result();
	}
	
}

?>