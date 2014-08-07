<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Closure1 extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("closure",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('closure/closure',$data);
	}
	function getclosurelist()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_closure');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_closure->getClosurelist($page);	
		$closures = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("closures"=>$closures, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function saveclosure(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_closure');
		$closure = $this->input->post('closure');
		$closure_id = $this->input->post('closure_id');
		$params = array(
					  'closure'=>$closure
					  );
		if((int)$closure_id==0){
			$params['closure_created_ts'] = date('Y-m-d H:i:s');
			$params['closure_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_closure->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['closure_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['closure_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_closure->save($params,$closure_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
			
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	
	function changestatusc()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_closure');
		$closure_id = $this->input->post('closure_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('closure_status'=>$status);
		if($this->mdl_closure->save($params,$closure_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
		function getclosuredetails(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_closure');
		$closure_id = (int)$this->input->post('closure_id');
		echo json_encode($this->mdl_closure->getclosuredetails($closure_id));
		} 
	function deleteclosure()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_closure');
		$closure_id = (int)$this->input->post('closure_id');
		if($this->mdl_closure->delete(array('closure_id'=>$closure_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
}
?>