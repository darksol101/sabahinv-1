<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Bulletin extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("bulletin",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('bulletin',$data);
	}
	function getbulletinlist()
	{
		$this->redir->set_last_index();
		$this->load->model('bulletin/mdl_bulletin');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_bulletin->getbulletinlist($page);	
		$bulletins = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("bulletins"=>$bulletins, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function savebulletin(){
		$this->redir->set_last_index();
		$this->load->model('bulletin/mdl_bulletin');
		$bulletin_board_name = $this->input->post('bulletin_board_name');
		$bulletin_board_desc = $this->input->post('bulletin_board_desc');
		$bulletin_board_status = $this->input->post('bulletin_board_status');		
		$bulletin_board_start_dt = date("Y-m-d",date_to_timestamp($this->input->post('bulletin_board_start_dt')));
		$bulletin_board_end_dt = date("Y-m-d",date_to_timestamp($this->input->post('bulletin_board_end_dt')));
		
		$bulletin_board_id = $this->input->post('bulletin_board_id');
		$params = array(
					  'bulletin_board_name'=>$bulletin_board_name
					  ,'bulletin_board_desc'=>$bulletin_board_desc
					  ,'bulletin_board_status'=>$bulletin_board_status
					  ,'bulletin_board_start_dt'=>$bulletin_board_start_dt
					  ,'bulletin_board_end_dt'=>$bulletin_board_end_dt
					  );
		if((int)$bulletin_board_id==0){
			$params['bulletin_board_created_ts'] = date('Y-m-d H:i:s');
			$params['bulletin_board_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_bulletin->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['bulletin_board_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['bulletin_board_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_bulletin->save($params,$bulletin_board_id)){
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
		$this->load->model('bulletin/mdl_bulletin');
		$bulletin_board_id = $this->input->post('bulletin_board_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('bulletin_board_status'=>$status);
		if($this->mdl_bulletin->save($params,$bulletin_board_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
		function getbulletindetails(){
		$this->redir->set_last_index();
		$this->load->model('bulletin/mdl_bulletin');
		$bulletin_board_id = (int)$this->input->post('bulletin_board_id');
		echo json_encode($this->mdl_bulletin->getbulletindetails($bulletin_board_id));
		} 
	function deletebulletin()
	{
		$this->redir->set_last_index();
		$this->load->model('bulletin/mdl_bulletin');
		$bulletin_board_id = (int)$this->input->post('bulletin_board_id');
		if($this->mdl_bulletin->delete(array('bulletin_board_id'=>$bulletin_board_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
}
?>