<?php (defined('BASEPATH')) OR exit('no direct script access allowed');
class Vendors extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->language('vendors',$this->mdl_mcb_data->setting('default_language'));
    }
    public function index(){
        $this->redir->set_last_index();
        $this->load->model(array('vendors/mdl_vendors','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
       $result = $this->mdl_vendors->getvenderlist();
        $data= array(
            'lists'=>$result
        );        
        $this->load->view('index',$data);
    }		
		
	function savevendor(){
	  $this->redir->set_last_index();
	  $error = array();
	  $this->load->model(array('vendors/mdl_vendors'));
	  $vendor_id = $this->input->post('vendor_id');
	  $data=array(
				  "vendor_name"=>$this->input->post('vendor_name'),
				  "vendor_address"=>$this->input->post('vendor_address'),
				  "vendor_phone"=>$this->input->post('vendor_phone'),
				  "vendor_contact_person"=>$this->input->post('vendor_contact')
  				 );
	  if((int)$vendor_id==0){
		  $data["vendor_created_ts"]=date("Y-m-d H:i:s");
		  $data["vendor_created_by"]=$this->session->userdata('user_id');
		  if($this->mdl_vendors->save($data)){
				 $error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		  }else{
			  $error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
		  }
	  }else{
		  $data["vendor_last_mod_ts"]=date("Y-m-d");
		  $data["vendor_last_mod_by"]=$this->session->userdata('user_id');
		  if($this->mdl_vendors->save($data,$vendor_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		  }else{
			  $error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
		  }
	  }
	  $this->load->view('dashboard/ajax_messages',$error);
	}
	
	public function getvendorlist(){
		
        $this->redir->set_last_index();
        $this->load->model(array('vendors/mdl_vendors','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
       $result = $this->mdl_vendors->getvenderlist();
        $data= array(
            		'lists'=>$result
        			);
        $this->load->view('vendorlist',$data);        
    }	
	
	public function getvendordetails(){
		$vendor_id = $this->input->post('vendor_id');
		$params=array(
					 "select"=>"vendor_id,vendor_name,vendor_address,vendor_contact_person,vendor_phone",
					 "where"=>array("vendor_id"=>$vendor_id),
					 "limit"=>1
					);
		$grouparr = $this->get($params);
		$group=$grouparr[0];
		echo json_encode($group);
	}
	function deletevendor() {
		$vendor_id=$this->input->post('vendor_id');
		$this->load->model('vendors/mdl_vendors');
		$success = $this->mdl_vendors->delete(array('vendor_id'=>$vendor_id));
		if($success==true){
			$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('vendor_not_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}    
    function get($params = NULL) {
		$this->load->model('vendors/mdl_vendors');
		return $this->mdl_vendors->get($params);
	}
}
?>