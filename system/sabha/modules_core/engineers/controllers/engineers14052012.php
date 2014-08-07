<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Engineers extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("engineers",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model(array('engineers/mdl_engineers'));
	}
	function index(){
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$statuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$status=$this->mdl_html->genericlist( $statuslist, 'status' );
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($servicecenterOptions, $this->mdl_html->option( '', 'Select Store'));
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_select",array('class'=>'validate[required] text-input'));
		$servicecenter_search = $this->mdl_html->genericlist( $servicecenterOptions, "sc_search",array());
		
		$params = array(
			'order_by'	=>	'engineer_name'
		);
		$data = array(
			'engineers' =>	$this->mdl_engineers->get($params),
			'servicecenter_select'=>$servicecenter_select,
			'status'=> $status,
			'servicecenter_search'=>$servicecenter_search
			);	
		$this->load->view('index', $data);
	}	

	function getengineerlist(){
		$this->redir->set_last_index();
		$this->load->model('servicecenters/mdl_servicecenters');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list = $this->mdl_engineers->getEngineerlist($page);			
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data=array("engineers"=>$list['list'], "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}		
	function getengineer(){
		$this->redir->set_last_index();
		$engineers=$this->mdl_engineers->getEngineer($this->input->post('engineer_id'));
		echo json_encode($engineers);
		}	
	function saveengineer(){
		$this->redir->set_last_index();
		$engineer_id=$this->input->post('engineer_id');
		$data=array(
					"engineer_name" =>$this->input->post('engineer_name'),
					"engineer_desc" =>$this->input->post('engineer_desc'),
					"engineer_status" =>$this->input->post('engineer_status'),
					"engineer_phone"=>$this->input->post('engineer_phone'),
					"engineer_skill_qualification"=>$this->input->post('skill_qualification'),
					'sc_id'=>$this->input->post('sc_id')
					);
		if((int)$engineer_id==0){
			$data["engineer_created_by"]=$this->session->userdata("user_id");
			$data["engineer_created_ts"]=date("Y-m-d");
			$success = $this->mdl_engineers->save($data);
		}else{
			$data["engineer_last_mod_ts"]=date("Y-m-d");
			$data["engineer_last_mod_by"]=$this->session->userdata("user_id");
			$success = $this->mdl_engineers->save($data,$engineer_id);
		}
		if($success==true){
			$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));			
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_not_saved'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}		
	function changeEstatus(){
		$this->load->model(array('engineers/mdl_engineers'));
		$id=$this->input->post('id');
		$status = ($this->input->post('status')==1)?0:1;
		$msg = ($status==1)?'published':'unpublished';
		if((int)$id>0){
			$data=array('engineer_status'=>$status);
			$success = $this->mdl_engineers->save($data,$id);
		}
		if($success==true){
			$error = array('type'=>'success','message'=>$this->lang->line('status_chanaged'));			
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('status_not_chanaged'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}	
	function deleteengineer() {
		$engineer_id=$this->input->post('engineer_id');
		$success = $this->mdl_engineers->delete(array('engineer_id'=>$engineer_id));
		if($success==true){
			$error = array('type'=>'success','message'=>$this->lang->line('engineer_deleted'));			
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('engineer_not_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}	

}
?>