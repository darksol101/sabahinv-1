<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public $widgets = array();

	function __construct() {

		parent::__construct();

	}

	function index() {
        $this->redir->set_last_index();
		$this->mdl_mcb_modules->refresh();
		$data = array();
		echo modules::run('events/index');
		//$this->load->view('dashboard', $data);
	}
}

?>