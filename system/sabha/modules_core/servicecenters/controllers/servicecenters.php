<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Servicecenters extends Admin_Controller {

	function __construct() {
		parent::__construct();		
		$this->load->helper(array('auth'));
		$this->load->language("servicecenters",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model(array('servicecenters/mdl_servicecenters'));
	}

	function index() {
		$this->redir->set_last_index();	
		$this->load->model( array('cities/mdl_cities','utilities/mdl_html'));
		$cityOptions = $this->mdl_cities->getCtOptions();
		array_unshift($cityOptions, $this->mdl_html->option( '', 'Select City'));
		$city_select  =  $this->mdl_html->genericlist($cityOptions,'city_select',array('class'=>'validate[required]'),'value','text','');

		$data = array(
					  'city_select'=>$city_select
					  );
		$this->load->view('index',$data);
	}
	function tab_servicecenters() {
		$this->redir->set_last_index();	
		$this->load->model( array('cities/mdl_cities','utilities/mdl_html'));
		$cityOptions = $this->mdl_cities->getCtOptions();
		array_unshift($cityOptions, $this->mdl_html->option( '', 'Select City'));
		$city_select  =  $this->mdl_html->genericlist($cityOptions,'city_select',array(),'value','text','');
		$data = array(
					  'city_select'=>$city_select
					  );
		$this->load->view('servicecenters/tab_servicecenter',$data);
	}
	
	function getservicecenterlist(){
		$this->redir->set_last_index();
		$ajaxaction=$this->input->post('ajaxaction');
		$service_centers=$this->mdl_servicecenters->getServiceCenterList();			
		$data=array("service"=>$service_centers, "ajaxaction"=>$ajaxaction);
		$this->load->view('process', $data);
		}
		
	function getServiceCenterdetails(){
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters'));
		$service=$this->mdl_servicecenters->getServiceCenterdetails((int)$this->input->post('sc_id'));
		echo $service;
		}
	
	function saveservicecenter(){
		$this->load->model(array('servicecenters/mdl_servicecenters'));
		$sc_id = $this->input->post('sc_id');
		$this->redir->set_last_index();
		$data=array(
						"sc_name"=>$this->input->post('sc_name'),
						"sc_address"=>$this->input->post('sc_address'),
						"sc_phone1"=>(int)$this->input->post('sc_phone1'),
						"sc_phone2"=>(int)$this->input->post('sc_phone2'),
						"sc_phone3"=>(int)$this->input->post('sc_phone3'),
						"sc_fax"=>(int)$this->input->post('sc_fax'),
						"sc_email"=>$this->input->post('sc_email'),
						"city_id"=>(int)$this->input->post('city_id'),
						"sc_code"=>$this->input->post('sc_code')
						);
			//$data["ent_date"]=date("Y-m-d");
			if((int)$sc_id==0){ 
				$this->mdl_servicecenters->save($data);
			}else{
				//$data["upd_date"]=date("Y-m-d");
				$this->mdl_servicecenters->save($data,$sc_id);
			}
			echo $this->lang->line('this_record_has_been_saved');
		}
	function delete_ServiceCenter() {
		$this->load->model(array('users/mdl_users','servicecenters/mdl_servicecenters'));
		$sc_id=$this->input->post('id');
		$result_scenter = $this->mdl_users->checkScenterByUser($sc_id);
		if((int)$result_scenter>0){
			echo $this->lang->line('this_record_can_not_be_deleted');
		}else{
			$this->mdl_servicecenters->delete(array('sc_id'=>$sc_id));
			echo $this->lang->line('this_record_has_been_deleted');
		}
	}	
	function get($params = NULL) {
		return $this->mdl_servicecenters->get($params);

	}

}

?>