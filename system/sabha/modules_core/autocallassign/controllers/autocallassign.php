<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Autocallassign extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("autoassign",  $this->mdl_mcb_data->setting('default_language'));
	}
	
	function index() {
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','servicecenters/mdl_servicecenters','utilities/mdl_html'));
		$brands = $this->mdl_brands->getBrandOptions();
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($servicecenterOptions, $this->mdl_html->option( '0', 'Select Store'));
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_id",array('onchange'=>'getCallassigns(this.value);','class'=>'validate[required] text-input'));
		$data = array(
					  'brands'=>$brands,
					  'servicecenter_select'=>$servicecenter_select
					  );
		$this->load->view('index',$data);
	}
	function getzones(){
		$this->redir->set_last_index();		
		$this->load->model(array('zones/mdl_zones','zones/mdl_districts','cities/mdl_cities','autocallassign/mdl_cityassign','utilities/mdl_html'));
		$sc_id = $this->input->post('sc_id');
		$ajaxaction = $this->input->post('ajaxaction');
		$zones = $this->mdl_zones->getZoneOptions();
		

		$zones_by_sc = $this->mdl_cityassign->getZoneAssignsBySc($sc_id);
		$arr = array();
		foreach($zones_by_sc as $zone){
			$arr[] = $zone->zone_id;
		}
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'zones'=>$zones,
					  'zones_by_sc'=>$arr
					  );
		$this->load->view('process',$data);
	}
	function getbrands(){
		$this->redir->set_last_index();		
		$this->load->model(array('brands/mdl_brands','autocallassign/mdl_productassign','products/mdl_products'));
		$sc_id = $this->input->post('sc_id');
		$ajaxaction = $this->input->post('ajaxaction');
		$brands = $this->mdl_brands->getBrandOptions();
		$brands_by_sc = $this->mdl_productassign->getBrandAssignsBySc($sc_id);
		$arr = array();
		foreach($brands_by_sc as $brand){
			$arr[] = $brand->brand_id;
		}
		//echo $this->db->last_query();
		
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'brands'=>$brands,
					  'brands_by_sc'=>$arr
					  );
		$this->load->view('process',$data);
	}
	function getdistricts(){
		$this->redir->set_last_index();		
		$this->load->model('zones/mdl_zones');
		$this->load->model(array('zones/mdl_districts','utilities/mdl_html'));
		$zones = $this->input->post('zones');
		
		if($zones==''){
			echo 'Please select zone first';
			die();
		}
		$this->load->model(array('zones/mdl_zones','zones/mdl_districts','cities/mdl_cities','autocallassign/mdl_cityassign'));
		$district_by_sc = $this->mdl_cityassign->getDistrictAssignsBySc($this->input->post('sc_id'));
		$districts = $this->mdl_districts->getDistrictByZone($zones);
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'districts'=>$districts,
					  'district_by_sc'=>$district_by_sc
					  );
		$this->load->view('process',$data);
	}
	function getcities(){
		$this->redir->set_last_index();		
		$this->load->model(array('cities/mdl_cities','zones/mdl_districts','zones/mdl_zones'));
		$districts = $this->input->post('districts');
		$this->load->model(array('zones/mdl_zones','zones/mdl_districts','cities/mdl_cities','autocallassign/mdl_cityassign'));
		$cities_by_sc = $this->mdl_cityassign->getCityAssignsBySc($this->input->post('sc_id'));
		
		$cities = $this->mdl_cities->getCitiesByDistrictAssigns($districts,$this->input->post('sc_id'));		
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'cities'=>$cities,
					  'cities_by_sc'=>$cities_by_sc
					  );
		$this->load->view('process',$data);
	}
	function getproducts()
	{
		$this->redir->set_last_index();		
		$this->load->model(array('brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','autocallassign/mdl_productassign'));
		$products_by_sc = $this->mdl_productassign->getProductAssignsBySc($this->input->post('sc_id'));
		$brands = $this->input->post('brands');
		$products = $this->mdl_products->getProductsByBrands($brands);
		
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'products'=>$products,
					  'products_by_sc'=>$products_by_sc
					  );
		$this->load->view('process',$data);
	}
	function getproductmodels()
	{
		$this->redir->set_last_index();		
		$this->load->model(array('brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','autocallassign/mdl_modelassign'));
		$productmodels_by_sc = $this->mdl_modelassign->getProductModelsAssignsBySc($this->input->post('sc_id'));
		$products = $this->input->post('products');
		$productmodels = $this->mdl_productmodel->getModelsByProducts($products);
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'productmodels'=>$productmodels,
					  'productmodels_by_sc'=>$productmodels_by_sc
					  );
		$this->load->view('process',$data);
	}
	function saveassignment()
	{
		ini_set("memory_limit","256M");
		$this->load->model(array('mdl_cityassign','mdl_productassign'));
		$sc_id = $this->input->post('sc_id');		
		$cities = $this->input->post('cities');
		$products = $this->input->post('products');
		
		if($cities){
			$cities = explode(",",$cities);
		}else{
			$cities = array();
		}
		if($products){
			$products = explode(",",$products);
		}else{
			$products = array();
		}
		$segments = array();
		$seg = array();
		
		foreach($products as $product){
			$segments[] =  "('".$sc_id."','".$product."')";
		}
		$success = false;
		$success = $this->mdl_productassign->delete(array('sc_id'=>$sc_id));
		if(count($segments)){
			$sql = 'REPLACE INTO '.$this->mdl_productassign->table_name.' (sc_id,product_id) VALUES'.implode(",",$segments);
			$success = $this->db->query($sql);
		}
		/**
		* store Stores and city
		*/	
		foreach($cities as $city){
			$seg[] =  "('".$sc_id."','".$city."')";
		}
		$success = $this->mdl_cityassign->delete(array('sc_id'=>$sc_id));
		if(count($seg)){
			$sql = 'REPLACE INTO '.$this->mdl_cityassign->table_name.' (sc_id,city_id) VALUES'.implode(",",$seg);
			$success = $this->db->query($sql);
		}
		//echo $sql;
		if($success==true){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		}else{
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_saved'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getdistrictassigns()
	{
		$this->redir->set_last_index();		
		$this->load->model(array('zones/mdl_zones','zones/mdl_districts','cities/mdl_cities','autocallassign/mdl_cityassign'));
		$zones = $this->mdl_cityassign->getZoneAssignsBySc($this->input->post('sc_id'));
		echo json_encode($zones);
	}
	function getmodelassigns(){
		$this->redir->set_last_index();		
		$this->load->model(array('brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','autocallassign/mdl_modelassign'));
		$brands = $this->mdl_modelassign->getBrandAssignsBySc($this->input->post('sc_id'));
		echo json_encode($brands);
	}
	function getcentersbycity(){
		$this->redir->set_last_index();
		$city_id = $this->input->post('city_id');
		$product_id = $this->input->post('product_id');
		
		$this->load->model(array('autocallassign/mdl_autocallassign','autocallassign/mdl_productassign','autocallassign/mdl_cityassign','servicecenters/mdl_servicecenters'));
		$service_centers = $this->mdl_autocallassign->getServiceCentersByAssign($city_id,$product_id);
		$arr = array();
		$total_service_centers = count($service_centers);
		
		$arr['total'] = $total_service_centers;
		if($total_service_centers==1){
			$arr['centers'] = $service_centers[0]->sc_id;	
		}else{
			$arr['centers'] = $service_centers;
		}
		echo json_encode($arr);
	}
	/*function getcentersbydistrict(){
		$this->redir->set_last_index();
		$district_id = $this->input->post('district_id');
		$product_id = $this->input->post('product_id');
		
		$this->load->model(array('autocallassign/mdl_autocallassign','autocallassign/mdl_productassign','autocallassign/mdl_districtassign','servicecenters/mdl_servicecenters'));
		$service_centers = $this->mdl_autocallassign->getServiceCentersByAssign($district_id,$product_id);
		$arr = array();
		$total_service_centers = count($service_centers);
		
		$arr['total'] = $total_service_centers;
		if($total_service_centers==1){
			$arr['centers'] = $service_centers[0]->sc_id;	
		}else{
			$arr['centers'] = $service_centers;
		}
		echo json_encode($arr);
	}*/
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
}
?>