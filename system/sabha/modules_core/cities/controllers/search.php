<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Search extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("cities",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model(array('cities/mdl_cities_search','zones/mdl_zones','zones/mdl_districts'));
	}
	function getcitysearchpop()
	{
		$this->redir->set_last_index();
		$this->load->model(array('utilities/mdl_html'));
		$zoneOptions = $this->mdl_zones->getZoneOptions();
		array_unshift($zoneOptions, $this->mdl_html->option( '', 'Select Zone'));		
		$zone_search  =  $this->mdl_html->genericlist($zoneOptions,'zone_search',array('class'=>'validate[required] select-one','onchange'=>'getdistricts(this.value)'),'value','text','');
		
		$districts= array();
		array_unshift($districts, $this->mdl_html->option( '', 'Select District'));
		$district_search = $this->mdl_html->genericlist( $districts, "district_search",array('class'=>'validate[required] text-input'));	
		$data = array(
					  'zone_search'=>$zone_search,
					  'district_search'=>$district_search
					  );
		$this->load->view('search/listcities',$data);
	}
	function getsearchcitylist()
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
		$list=$this->mdl_cities_search->getCitylist($page);	
		$citylist = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("cities"=>$citylist, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('search/process', $data);	
	}
}
?>