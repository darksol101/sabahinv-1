<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Users extends Admin_Controller {
	function __construct() {

		parent::__construct();

		$this->load->helper('auth');
		$this->load->language("users",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model(array('users/mdl_users','usergroups/mdl_usergroups'));

	}

	function index() {

		$this->redir->set_last_index();	
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html','users/mdl_users'));
		$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($scentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$scenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'validate[required] text-input'));

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
			'scenters'=>$scenters,
			'userID'=>$userID
		);
		$this->load->view('index',$data);
	}
	
	function getuserlist(){
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$this->load->library('ajaxpagination');
		$ajaxaction=$this->input->post('ajaxaction');
		$userID = $this->mdl_users->AutoIncrementId();
		
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_users->getUserlist($page);
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array(
					"users"=>$list['users'], 
					"ajaxaction"=>$ajaxaction,
					"userID"=>$userID,
					"navigation"=>$navigation,
					"page"=>$page
					);
		$this->load->view('process', $data);
	}
		
	function getuser(){
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data'));
		$user=$this->mdl_users->getUser($this->input->post('userid'));
		echo $user;
	}
	
	function saveuser(){
		$success = false;
		$error = array();
		$this->redir->set_last_index();
		$user_id=$this->input->post('hdnuserid');
		$userdata=array(
						"username"=>$this->input->post('username'),
						"user_id"=>strtoupper($this->input->post('userid')),
						"email_address"=>$this->input->post('email'),
						"mobile_number"=>$this->input->post('mobile_number'),
						"password"=>md5_encrypt($this->input->post('password'),$this->input->post('username')),
						"ustatus"=>$this->input->post('status'),
						"usergroup_id"=>$this->input->post('usergroup'),
						"sc_id"=>$this->input->post('sc_id')
						);
		if(!$this->input->post('hdnuserid')){
			$userdata["ent_date"]=date("Y-m-d");
			$success = $this->mdl_users->save($userdata);
		}else{
			$userdata["upd_date"]=date("Y-m-d");
			$success = $this->mdl_users->save($userdata, $user_id);
		}
		if($success==true){
			$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));			
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_not_saved'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
		
	

	function deleteuser() {
		$user_id=$this->input->post('userid');
			if ($user_id == $this->session->userdata('user_id')) {
				echo $this->lang->line('cannot_delete_user_account') . '.';
			}else {
				$this->mdl_users->delete(array('id'=>$user_id));
				echo $this->lang->line('user_deleted_successfully').'.';
			}
	}
	
	function userstab(){
		$this->redir->set_last_index();
		$this->load->model(array('branches/mdl_branches','mcb_data/mdl_mcb_data', 'utilities/mdl_html','users/mdl_users'));
		$branchOptions=$this->mdl_branches->getBranchOptions();
		array_unshift($branchOptions, $this->mdl_html->option( '', 'Select Branch'));
		$branch=$this->mdl_html->genericlist( $branchOptions, "branch_id",array('class'=>'validate[required] text-input'));

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
			'branch'=>$branch,
			'userID'=>$userID
		);
	
		$this->load->view('tab-users', $data);
	}		
	function chagestatus()
	{
		
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
}

?>