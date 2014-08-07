<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Cancellation extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("cancellation",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('cancellation/cancellation',$data);
	}
	function getcancellationlist()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_cancellation');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_cancellation->getCancellationlist($page);	
		$cancellations = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("cancellations"=>$cancellations, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function savecancellation(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_cancellation');
		$cancellation = $this->input->post('cancellation');
		$cancellation_id = $this->input->post('cancellation_id');
		$params = array(
					  'cancellation'=>$cancellation
					  );
		if((int)$cancellation_id==0){
			$params['cancellation_created_ts'] = date('Y-m-d H:i:s');
			$params['cancellation_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_cancellation->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['cancellation_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['cancellation_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_cancellation->save($params,$cancellation_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
			
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function changestatus()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_cancellation');
		$cancellation_id = $this->input->post('cancellation_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('cancellation_status'=>$status);
		if($this->mdl_cancellation->save($params,$cancellation_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getcancellationdetails()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_cancellation');
		$cancellation_id = (int)$this->input->post('cancellation_id');
		echo json_encode($this->mdl_cancellation->getcancellationdetails($cancellation_id));
		} 
	function deletecancellation()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_cancellation');
		$cancellation_id = (int)$this->input->post('cancellation_id');
		if($this->mdl_cancellation->delete(array('cancellation_id'=>$cancellation_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
}
?>