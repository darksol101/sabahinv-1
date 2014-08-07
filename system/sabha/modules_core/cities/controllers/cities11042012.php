<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Cities extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("cities",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model(array('cities/mdl_cities','zones/mdl_zones','zones/mdl_districts'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('utilities/mdl_html','cities/mdl_cities','cities/mdl_zones','zones/mdl_districts'));
		$zoneOptions = $this->mdl_zones->getZoneOptions();
		array_unshift($zoneOptions, $this->mdl_html->option( '', 'Select Zone'));
		$zone_select  =  $this->mdl_html->genericlist($zoneOptions,'zone_select',array('class'=>'validate[required] select-one','onchange'=>'getdistrictbyzone(this.value)'),'value','text','');
		
		$zone_search  =  $this->mdl_html->genericlist($zoneOptions,'zone_search',array('class'=>'validate[required] select-one','onchange'=>'getdistricts(this.value)'),'value','text','');
		
		$districts= array();
		array_unshift($districts, $this->mdl_html->option( '', 'Select District'));
		$district_search = $this->mdl_html->genericlist( $districts, "district_search",array('class'=>'validate[required] text-input'));	
		
		$zone_id = $this->input->post('zone_id');
		$districtOptions = $this->mdl_districts->getDistrictOptions($zone_id);
		array_unshift($districtOptions, $this->mdl_html->option( '', 'Select District'));
		$district_select = $this->mdl_html->genericlist( $districtOptions, "district_select",array('class'=>'validate[required] text-input'));		
		$data = array(
					'zone_select'=>$zone_select,
					'district_select'=>$district_select,
					'zone_select'=>$zone_select,
					'zone_search'=>$zone_search,
					'district_search'=>$district_search
					);
		$this->load->view('index',$data);
	}
	function getcitylist()
	{
	    $this->redir->set_last_index();
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_cities->getCitylist($page);	
		$citylist = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("cities"=>$citylist, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function getCitydetails()
	{
		$this->redir->set_last_index();
		$this->load->model(array('cities/mdl_cities'));
		$city=$this->mdl_cities->getcitydetails((int)$this->input->post('city_id'));
		echo $city;
	}
	function getcitysearch()
	{
	    $this->redir->set_last_index();
		$ajaxaction=$this->input->post('ajaxaction');
		$citylist=$this->mdl_cities->getCitySearch();
		$data=array("cities"=>$citylist, "ajaxaction"=>$ajaxaction);
		$this->load->view('process', $data);
	}
	function deletecity()
	{
		$error = array();
		$cities_id=$this->input->post('city_id');
		if($this->mdl_cities->delete(array('city_id'=>$cities_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('city_deleted_successfully'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('city_not_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	
	function savecity()
	{	
		$error = array();
		$this->redir->set_last_index();
		$city_id=$this->input->post('city_id');
		$data=array(
						"city_name"=>$this->input->post('city_name'),
						"district_id"=>$this->input->post('district_id'),
						);
		if((int)$city_id==0){
			$data["city_created_ts"]=date("Y-m-d");
 			$data["city_created_by"]=$this->session->userdata('user_id');
			if($this->mdl_cities->save($data)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_not_saved'));
			}
		}else{
			$data["city_last_mod_ts"]=date("Y-m-d");			
			$data["city_last_mod_by"]=$this->session->userdata('user_id');
			
			if($this->mdl_cities->save($data, $city_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_not_saved'));
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getdistrictbyzone()
	{
		$this->redir->set_last_index();
		$this->load->model(array('zones/mdl_districts','utilities/mdl_html'));
		$zone_id = $this->input->post('zone_id');
		$active = $this->input->post('active');
		$districtOptions = $this->mdl_districts->getDistrictOptions($zone_id);
		array_unshift($districtOptions, $this->mdl_html->option( '', 'Select District'));
		$district_select  =  $this->mdl_html->genericlist($districtOptions,'district_select',array('class'=>'validate[required] select-one'),'value','text',$active);
		echo $district_select;
	}
	function getdistricts()
	{
		$this->redir->set_last_index();
		$this->load->model(array('zones/mdl_districts','utilities/mdl_html'));
		$zone_id = $this->input->post('zone_id');
		$active = $this->input->post('active');
		$districtOptions = $this->mdl_districts->getDistrictOptions($zone_id);
		array_unshift($districtOptions, $this->mdl_html->option( '', 'Select District'));
		$district_select  =  $this->mdl_html->genericlist($districtOptions,'district_search',array('class'=>'validate[required] select-one'),'value','text',$active);
		echo $district_select;
	}
}
?>