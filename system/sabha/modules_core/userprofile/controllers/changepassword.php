<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Changepassword extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("userprofile",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('userprofile/mdl_userprofile');
	}
	function index()
	{
		//$this->load->library('my_form_validation');
		if (!$this->mdl_userprofile->validate())
		{
			$this->load->view('index');
		}
		else
		{
			$this->load->helper('auth');
			$this->load->model('users/mdl_users');
			$newpassword = $this->input->post('newpassword');
			if($this->mdl_userprofile->save()==TRUE){
				$this->session->set_flashdata('password_save', 'Password changed successfully');
			}else{
				$this->session->set_flashdata('error_save', 'Password not changed');
			}
			redirect('userprofile/changepassword');
		}
	}
}
?>