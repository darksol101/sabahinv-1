<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Settings extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("reasons",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('pending/reason',$data);
	}
}
?>