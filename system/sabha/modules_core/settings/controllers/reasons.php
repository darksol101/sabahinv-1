<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reasons extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("reasons",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('pending/reason',$data);
	}
	function getreasonlist()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_reasons');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_reasons->getReasonlist($page);	
		$reasons = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("reasons"=>$reasons, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function savereason(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_reasons');
		$pending = $this->input->post('reason');
		$pending_id = $this->input->post('pending_id');
		$params = array(
					  'pending'=>$pending
					  );
		if((int)$pending_id==0){
			$params['pending_created_ts'] = date('Y-m-d H:i:s');
			$params['pending_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_reasons->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['pending_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['pending_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_reasons->save($params,$pending_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getreasondetails(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_reasons');
		$pending_id = (int)$this->input->post('pending_id');
		echo json_encode($this->mdl_reasons->getreasondetails($pending_id));
	}
	function changestatus()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_reasons');
		$pending_id = $this->input->post('pending_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('pending_status'=>$status);
		if($this->mdl_reasons->save($params,$pending_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function deletereason()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_reasons');
		$pending_id = (int)$this->input->post('pending_id');
		if($this->mdl_reasons->delete(array('pending_id'=>$pending_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
}
?>