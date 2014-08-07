<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Warranty extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("warranty",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('warranty/warranty',$data);
	}
	function getwarrantylist()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_warranty');
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_warranty->getWarrantylist($page);	
		$warrantys = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("warrantys"=>$warrantys, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function savewarranty(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_warranty');
		$warranty = $this->input->post('warranty');
		$warranty_id = $this->input->post('warranty_id');
		$params = array(
					  'warranty'=>$warranty
					  );
		if((int)$warranty_id==0){
			$params['warranty_created_ts'] = date('Y-m-d H:i:s');
			$params['warranty_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_warranty->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['warranty_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['warranty_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_warranty->save($params,$warranty_id)){
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
		$this->load->model('settings/mdl_warranty');
		$warranty_id = $this->input->post('warranty_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('warranty_status'=>$status);
		if($this->mdl_warranty->save($params,$warranty_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
		function getwarrantydetails(){
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_warranty');
		$warranty_id = (int)$this->input->post('warranty_id');
		echo json_encode($this->mdl_warranty->getwarrantydetails($warranty_id));
		} 
	function deletewarranty()
	{
		$this->redir->set_last_index();
		$this->load->model('settings/mdl_warranty');
		$warranty_id = (int)$this->input->post('warranty_id');
		if($this->mdl_warranty->delete(array('warranty_id'=>$warranty_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
}
?>