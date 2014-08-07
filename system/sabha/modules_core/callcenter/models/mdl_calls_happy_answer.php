<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Calls_happy_answer extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_call_happy_answers';
		$this->primary_key = 'sst_call_happy_answers.answer_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' answer_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	
	function getAnswersOptionsByQuestion($question){
		$this->db->select('answer_id as value,answer_desc as text, sort_id, status');
		$this->db->from($this->table_name);
		$this->db->where('question_id =',$question);
		$this->db->where('status =','1');
		$this->db->order_by('sort_id asc');
		$result= $this->db->get();
		return $result->result();
		
		}

	
}

?>