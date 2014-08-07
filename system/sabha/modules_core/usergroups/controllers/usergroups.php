<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Usergroups extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->_post_handler();
		$this->load->language("users",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model(array('users/mdl_users','usergroups/mdl_usergroups'));

	}

	function index() {

		$this->redir->set_last_index();	
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html','users/mdl_users'));
		
		$ustatuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$status=$this->mdl_html->genericlist( $ustatuslist, 'status' );
		$userID = $this->mdl_users->AutoIncrementId();
		$usergroup=$this->mdl_html->genericlist( $this->mdl_usergroups->getusergroup(), 'usergroup' );
		$params = array(
			'limit'		=>	false,
			'paginate'	=>	false,
			'order_by'	=>	'id'
		);

		$data = array(
			'users' =>	$this->mdl_users->get($params),
			'status'=> $status,
			'usergroup'=>$usergroup,
			'userID'=>$userID
		);
		$this->load->view('index',$data);
	}
		
	function usergroups(){
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$statuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$status=$this->mdl_html->genericlist( $statuslist, 'status' );
		$params = array(
			'order_by'	=>	'details'
		);

		$data = array(
			'usergroups' =>	$this->mdl_usergroups->get($params),
			'status'=> $status
			);

	
		$this->load->view('tab-usergroups', $data);
		}
	
	function getgrouplist(){
		$this->redir->set_last_index();
		$ajaxaction=$this->input->post('ajaxaction');
		$grouplist=$this->mdl_usergroups->getGrouplist();			
		$data=array("groups"=>$grouplist, "ajaxaction"=>$ajaxaction);
		$this->load->view('process', $data);
		}
		
	function getgroup(){
		$this->redir->set_last_index();
		$group=$this->mdl_usergroups->getGroup($this->input->post('id'));
		echo $group;
		die();
		}
	
	function savegroup(){
		$this->redir->set_last_index();
		$group_id=$this->input->post('hdngroupid');
		$data=array(
						"details"=>$this->input->post('groupname'),
						"description"=>$this->input->post('description'),
						"usergroup_status"=>$this->input->post('status'),
						"user_id"=>$this->session->userdata("user_id")
						);
		if(!$group_id){
			$data["ent_date"]=date("Y-m-d");
			$this->mdl_usergroups->save($data);
		}else{
			$data["upd_date"]=date("Y-m-d");
			$this->mdl_usergroups->save($data, $group_id);
		}
		echo $this->lang->line('this_record_has_been_saved');
		}
	
	function deletegroup() {
		$group_id=$this->input->post('groupid');
		$this->mdl_usergroups->delete(array('usergroup_id'=>$group_id));
		echo $this->lang->line('group_deleted_successfully').'.';
	}
	
	function get($params = NULL) {

		return $this->mdl_users->get($params);

	}


	function _post_handler() {
		
	}

}

?>