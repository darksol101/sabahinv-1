<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assign extends Account_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   				
	}
	function index(){
		if ( $this->session->userdata('global_admin')!=1)
		{
			$this->session->set_flashdata('custom_warning','Permission denied.');
			redirect('account');
			return;
		}
		$this->load->model(array('usergroups/mdl_usergroups','account/access_model','account/access_task_model'));
		$groups =$this->mdl_usergroups->getGrouplist();
		$permissions = $this->access_task_model->getTaskList();
		$access = $this->access_model->getAcessDetails();
		
		$data = array(
					'groups' => $groups,
					'access' => $access,
					'permissions'=>$permissions
					);
		$this->load->view('assign/index',$data);
	}
}