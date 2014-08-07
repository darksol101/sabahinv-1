<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Symptom extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("symptom",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('symptom/symptom',$data);
	}
	function getsymptomlist()
	{
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_symptom');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_symptom->getsymptomlist($page);	
		$symptoms = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("symptoms"=>$symptoms, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function savesymptom(){
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_symptom');
		$symptom_status = $this->input->post('symptom_status');
		$symptom_description = $this->input->post('symptom_description');
		$symptom_code = $this->input->post('symptom_code');
		$symptom_id = $this->input->post('symptom_id');
		$params = array(
					  'symptom_code'=>$symptom_code
					  ,'symptom_description'=>$symptom_description
					  ,'symptom_status'=>$symptom_status
					  );
		if((int)$symptom_id==0){
			$params['symptom_created_ts'] = date('Y-m-d H:i:s');
			$params['symptom_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_symptom->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['symptom_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['symptom_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_symptom->save($params,$symptom_id)){
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
		$this->load->model('faultsettings/mdl_symptom');
		$symptom_id = $this->input->post('symptom_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('symptom_status'=>$status);
		if($this->mdl_symptom->save($params,$symptom_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
		function getsymptomdetails(){
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_symptom');
		$symptom_id = (int)$this->input->post('symptom_id');
		echo json_encode($this->mdl_symptom->getsymptomdetails($symptom_id));
		} 
	function deletesymptom()
	{
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_symptom');
		$symptom_id = (int)$this->input->post('symptom_id');
		if($this->mdl_symptom->delete(array('symptom_id'=>$symptom_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
}
?>