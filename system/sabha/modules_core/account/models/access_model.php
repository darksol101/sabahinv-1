<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Access_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'access';
		$this->primary_key = 'access.access_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
	}
	function getAcessDetails(){
		$this->db->select('g.usergroup_id,g.details,a.access,a.access_id');
		$this->db->from($this->mdl_usergroups->table_name.' AS g');
		$this->db->join($this->table_name.' AS a', 'a.usergroup_id = g.usergroup_id','left');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
}
