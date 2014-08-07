<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Recoverpassword extends CI_Controller {
	function __construct() {
		parent::__construct();
        $this->load->library(array('session'));
        $this->load->database();
        $this->load->helper('mcb_app');
        $this->load->model('mcb_data/mdl_mcb_data');
        $this->mdl_mcb_data->set_application_title();
	}
	function index() {
        $this->lang->load('recover', 'english');
		$this->load->model('mdl_recover');
        $this->load->helper(array('url', 'form'));
		if($this->session->userdata('is_loggedin')==TRUE){
			redirect('');
		}
        if (!$this->mdl_recover->validate_recover()) {
            $this->load->view('recover');
        }
        else {
            $this->mdl_recover->recover_password($this->input->post('username'));
			redirect('userprofile/recoverpassword');
        }
    }
}
?>