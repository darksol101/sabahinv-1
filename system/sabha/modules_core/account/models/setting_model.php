<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Setting_model extends MY_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_current()
	{
		$this->db->from('settings')->where('id', 1);
		$account_q = $this->db->get();
		return $account_q->row();
	}
}
