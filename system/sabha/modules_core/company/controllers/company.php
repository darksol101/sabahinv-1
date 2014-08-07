<?php (defined('BASEPATH')) OR exit('no direct script access allowed');
class Company extends Admin_Controller{
    function __construct(){
        parent::__construct();
        $this->load->language('company',$this->mdl_mcb_data->setting('default_language'));
    }
    public function index(){
        $this->redir->set_last_index();
              
        $this->load->view('index');
    }		
	
	function savecompany(){
	  $this->redir->set_last_index();
	  $error = array();
	  $this->load->model(array('company/mdl_company'));
	  $company_id = $this->input->post('company_id');
	// print_r($this->input->post());
	// die();
	  $data=array(
				  "company_title"=>$this->input->post('company_name'),
				  "company_desc"=>$this->input->post('company_desc'),
				  "phone"=>$this->input->post('company_phone'),
				  "address"=>$this->input->post('company_address')
  				 );
	  if((int)$company_id==0){
		  $data["company_created_ts"]=date("Y-m-d H:i:s");
		  $data["company_created_by"]=$this->session->userdata('user_id');
		  if($this->mdl_company->save($data)){
				 $error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		  }else{
			  $error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
		  }
	  }else{
		  $data["company_last_mod_ts"]=date("Y-m-d");
		  $data["company_last_mod_by"]=$this->session->userdata('user_id');
		  if($this->mdl_company->save($data,$company_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		  }else{
			  $error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
		  }
	  }
	  $this->load->view('dashboard/ajax_messages',$error);
	}
		
	public function getcompanylist(){
		
	 $this->redir->set_last_index();
        $this->load->model(array('company/mdl_company','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
	    $ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$result = $this->mdl_company->getcompanylist($page);
	
		
        $lists = $result['list'];
		$config['total_rows'] = $result['total'];
		
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		
		$data = array(
						 'lists'=>$lists,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
		
		
        $this->load->view('companylist',$data);        
    }	

	public function getcompanydetail(){
		$company_id = $this->input->post('company_id');
		 $this->load->model(array('company/mdl_company'));
		$grouparr = $this->mdl_company->getcompanydetail($company_id);
		
		$group=$grouparr[0];
	//print_r($grouparr);
		//die();
		echo json_encode($group);
	}
	function deletecompany() {
		$company_id=$this->input->post('company_id');
		$this->load->model('company/mdl_company');
		$success = $this->mdl_company->delete(array('company_id'=>$company_id));
		if($success==true){
			$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('vendor_not_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}    
    function get($params = NULL) {
		$this->load->model('company/mdl_company');
		return $this->mdl_company->get($params);
	}
	
	public function selectpart(){
		$this->load->model('parts/mdl_parts');
		$partlist= $this->mdl_parts->getPartOptions();
		$company_id= $this->input->post('company_id');
		$data = array(
					 'partlist' =>$partlist
					  );
		
		$this->load->view('showpart',$data);
		
		
		
		
		}
	
	
	
}
?>