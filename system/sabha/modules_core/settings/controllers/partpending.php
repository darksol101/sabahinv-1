<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Partpending extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("partpending",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('partpending/partpending',$data);
	}
	function getpartpendinglist()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_partpending');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_partpending->getPartpendinglist($page);	
		$partpendings = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("partpendings"=>$partpendings, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function savepartpending(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_partpending');
		$partpending = $this->input->post('partpending');
		$partpending_id = $this->input->post('partpending_id');
		$params = array(
					  'partpending'=>$partpending
					  );
		if((int)$partpending_id==0){
			$params['partpending_created_ts'] = date('Y-m-d H:i:s');
			$params['partpending_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_partpending->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['partpending_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['partpending_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_partpending->save($params,$partpending_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
			
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getpartpendingdetails(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_partpending');
		$partpending_id = (int)$this->input->post('partpending_id');
		echo json_encode($this->mdl_partpending->getpartpendingdetails($partpending_id));
	}
	function changestatus()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_partpending');
		$partpending_id = $this->input->post('partpending_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('partpending_status'=>$status);
		if($this->mdl_partpending->save($params,$partpending_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function deletepartpending()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_partpending');
		$ppending_id = (int)$this->input->post('partpending_id');
		if($this->mdl_partpending->delete(array('partpending_id'=>$partpending_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
}
?>