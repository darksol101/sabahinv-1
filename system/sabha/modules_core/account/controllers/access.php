<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Access extends Account_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   				
	}
	function index(){
	/* Check access */
		if ( ! check_access('view access'))
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
		$this->load->view('access/index',$data);
	}
	function manage(){
		$this->form_validation->set_rules('required', 'Group', 'trim');
		if($this->form_validation->run()==FALSE){
			echo modules::run('account/access/index');			
		}else{
			/* Check access */
			if ( ! check_access('save entry'))
			{
				$this->session->set_flashdata('custom_warning','Permission denied.');
				redirect('account');
				return;
			}
			$error = false;
			$access_arr = $this->input->post('chk_access');
			$hdnaccess_id = $this->input->post('hdnaccess_id');
			$hdusergroup_id = $this->input->post('hdusergroup_id');
			$this->load->model(array('account/access_model'));
			
			foreach ($hdusergroup_id as $group_id){
				if(isset( $access_arr[$group_id] )){
					$access = json_encode(array('task'=>$access_arr[$group_id]));
				}else{
					$access = json_encode(array('task'=>array()));
				}
				$data = array(
						'usergroup_id'=>$group_id,
						'access'=>$access
						);				
				if((int)$hdnaccess_id[$group_id]>0){
					if(!$this->access_model->save($data,$hdnaccess_id[$group_id])){
						$error = true;
					}
				}else{
					if(!$this->access_model->save($data)){
						$error = true;
					}
				}			
			}
			
			if($error == true){
				$this->session->set_flashdata('custom_warning','Error while saving');
			}else{
				$this->session->set_flashdata('success_save','Saved successfully');
			}
			redirect('account/access');		
		}				
	}
	function tasks(){
		$data = array();
		$this->load->view('access/tasks',$data);
	}
}