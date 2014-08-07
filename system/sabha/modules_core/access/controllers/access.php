<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Access extends Admin_Controller {
	function __construct() {
		parent::__construct();		
		$this->load->helper('auth');
		$this->load->language("access",  $this->mdl_mcb_data->setting('default_language'));
	   
	}
	
	function index(){
		$this->redir->set_last_index();
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html','usergroups/mdl_usergroups','mdl_menus'));
		$usergroups = $this->mdl_usergroups->getusergroup();
		array_unshift($usergroups,$this->mdl_html->option('','Select Group'));
		$usergroup=$this->mdl_html->genericlist($usergroups, 'usergroup',array('class'=>'validate[required] text-input','onchange'=>'showgroup(this.value)'));		
		$menus=$this->mdl_html->genericlist( $this->mdl_menus->getMenuOptions(), 'menus' );
		//print_r($menus);
		$data = array(
					  'usergroup'=>$usergroup,
					  'menuOptions' =>$menus,
					  'menus' =>  $this->mdl_menus->getMenu()
					  );
		$this->load->view('index',$data);
	}
	
	function getgrouplist(){
		$this->redir->set_last_index();
		$this->load->model(array('usergroups/mdl_usergroups','mdl_menus'));
		$ajaxaction=$this->input->post('ajaxaction');
		$grouplist=$this->mdl_usergroups->getusergroup();
		$data=array("groups"=>$grouplist, "ajaxaction"=>$ajaxaction,'this_access'=>$this);
		$this->load->view('process', $data);
	}
	function getgroup(){
		$this->redir->set_last_index();
		$this->load->model(array('usergroups/mdl_usergroups','mdl_menus'));
		$id=$this->input->post('id');
		$this->mdl_menus->getMenusByGroup($id);
		die();
	}
	function savegroup(){
		$this->redir->set_last_index();
		$this->load->model(array('usergroups/mdl_usergroups','mdl_menus'));
		$selected_all = $this->input->post('selected');
		$unslected_all =$this->input->post('unselected');
		$unselected  = explode(",",$unslected_all);
		$filterUnselected = array();
		$selected = explode(",",$selected_all);
		$filterSelected =array();
		for($i=0;$i<count($selected)-1;$i++){
			$filterSelected[] = $selected[$i];
		}
		for($i=0;$i<count($unselected)-1;$i++){
			$filterUnselected[] = $unselected[$i];
		}
		$group_id = $this->input->post('hdngroupid');
		$this->mdl_menus->saveGroup($filterUnselected,$filterSelected,$group_id);
		$this->load->view('dashboard/ajax_messages',array("type"=>"success","message"=>$this->lang->line('this_record_has_been_saved_assign')));	

		
	}
	
	function  noaccess(){
		$this->redir->set_last_index();
		$this->load->view('rejected');
		
		
	}
	
}
?>