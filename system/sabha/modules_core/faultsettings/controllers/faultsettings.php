<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Faultsettings extends Admin_Controller {
	function __construct() {
		parent::__construct();
		//$this->load->language("faults",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->mdl_mcb_modules->refresh();
		$data = array();
		echo modules::run('faultsettings/symptom/index');
		//$this->load->view('symptom/symptom',$data);
	}
}
?>