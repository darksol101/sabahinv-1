<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Zones extends Admin_Controller {
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('mdl_zones');
	}
	function getdistrictbyzone()
	{
		$this->redir->set_last_index();
		$this->load->model(array('zones/mdl_districts','utilities/mdl_html'));
		$zone_id = $this->input->post('zone_id');
		$active = $this->input->post('active');
		$districtOptions = $this->mdl_districts->getDistrictOptions($zone_id);
		array_unshift($districtOptions, $this->mdl_html->option( '', 'Select District'));
		$district_select  =  $this->mdl_html->genericlist($districtOptions,'district_select',array('class'=>'validate[required] select-one','onchange'=>'getcitiesbydistrict(this.value)'),'value','text',$active);
		echo $district_select;
	}
	function getcitiesbydistrict()
	{
		$this->redir->set_last_index();
		$this->load->model(array('cities/mdl_cities','utilities/mdl_html'));
		$district_id = $this->input->post('district_id');
		$active = $this->input->post('active');
		$cityOptions = $this->mdl_cities->getCityOptions($district_id);
		array_unshift($cityOptions, $this->mdl_html->option( '', 'Select City'));
		$city_select  =  $this->mdl_html->genericlist($cityOptions,'city_select',array('class'=>'validate[required] select-one'),'value','text',$active);
		echo $city_select;
	}
}

?>