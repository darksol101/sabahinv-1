<?php
class Access_task_model extends MY_Model{
public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_tasks';
		$this->primary_key = 'sst_tasks.task_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
	}
	function getTaskList(){
		$this->db->select('t.task_id,t.task_name,t.task_title,t.task_module,t.task_type');
		$this->db->from($this->table_name.' AS t');
		$this->db->order_by('t.task_module ASC,t.task_id');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
}