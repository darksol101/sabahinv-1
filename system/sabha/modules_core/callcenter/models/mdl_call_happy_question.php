<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Call_happy_question extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_call_happy_question';
		$this->primary_key = 'sst_call_happy_question.question_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' question_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	
	function getQuestions(){
		$this->db->select('question_id,question, question_type, question_sort, status');
		$this->db->from($this->table_name);
		$this->db->where('status =','1');
		$this->db->order_by('question_sort asc');
		$result= $this->db->get();
		
		
		$question['list']= $result->result();
		$question['total']=$result->num_rows();
		return $question;
		
		}
	function getQuestionsreport(){
		$this->db->select('question_id,question, question_type, question_sort, status');
		$this->db->from($this->table_name);
		$this->db->where('status =','1');
		$this->db->order_by('question_id asc');
		$result= $this->db->get();
		
		
		$question['list']= $result->result();
		$question['total']=$result->num_rows();
		return $question;
		
		}	
	
}

?>