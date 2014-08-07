<?php defined('BASEPATH') or die('Direct access script is not allowed');
class Bad_parts extends Admin_Controller{
	public function  __construct(){
		parent::__construct();
		$this->load->language("bad_parts",  $this->mdl_mcb_data->setting('default_language'));
	}
	public function index(){
		$data = array();
		$this->load->view('parts/bad_parts/index',$data);
	}
	public function getbadparts(){
		$this->load->model(array('parts/mdl_bad_parts','stocks/mdl_stocks','servicecenters/mdl_servicecenters','engineers/mdl_engineers'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$list = $this->mdl_bad_parts->getbadparts($page);
		$bad_parts = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
					  'bad_parts'=>$bad_parts,
					  'navigation'=>$navigation
					  );
		$this->load->view('parts/bad_parts/badpartslist',$data);
	}
	function edit(){
		$this->load->model(array('parts/mdl_bad_parts','stocks/mdl_stocks','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$bad_parts_id = $this->uri->segment(4);
		$this->form_validation->set_rules('bad_parts_id', '', 'required|int');
		if ($this->form_validation->run() == FALSE){
		}else{
			$parts_data['sc_id'] = $this->input->post('sc_id');
			$parts_data['bad_parts_status'] = $this->input->post('bad_parts_status');
			$parts_data['bad_parts_last_mod_by'] = $this->session->userdata('bad_parts_last_mod_by');
			$parts_data['bad_parts_last_mod_ts'] = date('Y-m-d H:i:s');
			if($this->mdl_bad_parts->save($parts_data,$this->input->post('bad_parts_id'))){
				$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				redirect('parts/bad_parts/edit/'.$this->input->post('bad_parts_id'));
			}
			
		}
		$bad_part_details = $this->mdl_bad_parts->getbadpartdetails($bad_parts_id);
		$serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'sc_id',array(),'value','text',$bad_part_details->sc_id);
		
		$partstatuslist=$this->mdl_mcb_data->getStatusOptions('bad_parts_status');
		array_unshift($partstatuslist, $this->mdl_html->option( '0', 'Select Status'));
		$bad_parts_status=$this->mdl_html->genericlist( $partstatuslist, 'bad_parts_status' ,array(),'value','text',$bad_part_details->bad_parts_status);
	   
		
		//echo $this->db->last_query();
		$data = array(
					  'bad_part_details'=>$bad_part_details,
					  'servicecenter_select'=>$servicecenter_select,
					  'bad_parts_status'=>$bad_parts_status
					  );
		$this->load->view('parts/bad_parts/edit',$data);
	}
}
?>