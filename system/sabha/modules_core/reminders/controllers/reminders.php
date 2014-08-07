<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Reminders extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("reminder",  $this->mdl_mcb_data->setting('default_language'));
	}

	function index() {
		$this->redir->set_last_index();
		$this->load->model(array('reminders/mdl_reminders'));
		$data = array(
		);
		$this->load->view('index',$data);
	}

	function addreminder(){
		$this->redir->set_last_index();
		$data=array(
		);
		$this->load->view('addreminder', $data);
	}
	function getreminders(){
		$this->redir->set_last_index();
		$this->load->model(array('reminders/mdl_reminders','users/mdl_users'));
		$call_id = $this->input->post('call_id');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$call_id = $this->input->post('call_id');
		if($call_id){
			$reminders=	$this->mdl_reminders->getRemindersByCall($page);
		}else{
			$reminders=	$this->mdl_reminders->getReminders($page);
		}
		$config['total_rows'] = $reminders['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$ajaxaction  = $this->input->post('ajaxaction');
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'reminders'=>$reminders['list'],
					  'navigation'=>$navigation,
					  'page'=>$page
		);
		$this->load->view('process',$data);
	}
	function getreminderdetails(){
		$this->redir->set_last_index();
		$this->load->model(array('reminders/mdl_reminders'));
		$reminder_id = (int)$this->input->post('reminder_id');
		$brand=$this->mdl_reminders->getReminderDetails($reminder_id);
		echo json_encode($brand);
		die();
	}

	function savereminder(){
		$this->redir->set_last_index();
		$error = array();
		$this->load->model(array('reminders/mdl_reminders'));
		$reminder_id = $this->input->post('reminder_id');
		$data=array(
					"reminder_remarks"=>$this->input->post('reminder_remarks')
		);
		$msg = '';
		if((int)$reminder_id==0){
			$data['call_id'] =$this->input->post('call_id');
			$data['reminder_dt'] = date('Y-m-d H:i:s');
			$data["reminder_created_ts"]=date("Y-m-d H:i:s");
			$data["reminder_created_by"]=$this->session->userdata('user_id');
			if($this->mdl_reminders->save($data)){
				if($msg){
					$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_saved').$msg);
				}else{
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$data["reminder_last_mod_ts"]=date("Y-m-d");
			$data["reminder_last_mod_by"]=$this->session->userdata('user_id');
			if($this->mdl_reminders->save($data,$reminder_id)){
				if($msg){
					$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_saved').$msg);
				}else{
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function changestatus(){
		$this->load->model(array('brands/mdl_brands','products/mdl_products'));
		$brand_id=$this->input->post('brand_id');
		$status = ($this->input->post('status')==1)?0:1;
		$msg = ($status==1)?'active':'inactive';
		$brand_result = $this->mdl_products->checkBrandByProduct($brand_id);

		if((int)$brand_id>0){
			$data=array('brand_status'=>$status);
			/*if($brand_result>0){
				$error = array('type'=>'failure','message'=>'This brand could not be made '.$msg);
				}else{*/
			if($this->mdl_brands->save($data,$brand_id)){
				$error = array('type'=>'success','message'=>'This brand has been '.$msg.' successfully');
			}else{
				$error = array('type'=>'failure','message'=>'This brand could not be '.$msg);
			}
			/*}*/
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function deletereminder() {
		$this->load->model(array('reminders/mdl_reminders'));
		$reminder_id=$this->input->post('reminder_id');
		if($this->mdl_reminders->delete(array('reminder_id'=>$reminder_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
}
?>