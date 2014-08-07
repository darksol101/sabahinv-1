<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public $widgets = array();

	function __construct() {

		parent::__construct();

	}

	function index() {
        $this->redir->set_last_index();
		$data = array();
		$this->load->view('dashboard', $data);

	}
}

?>