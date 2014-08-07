<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Callreport extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("callreport",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel','brands/mdl_brands','cities/mdl_cities','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','mcb_data/mdl_mcb_data','customers/mdl_customers','servicecenters/mdl_servicecenters','utilities/mdl_html'));
		$this->load->model('engineers/mdl_engineers');
		$engineerOptions = $this->mdl_engineers->getEngineerOptions();
		array_unshift($engineerOptions, $this->mdl_html->option( '0', '< Not Assigned >'));
		array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
		$engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer',array('class'=>''),'value','text',$this->input->get('eg'));
		$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($scentersOptions,$this->mdl_html->option( '0', '< Not Assigned >'));
		array_unshift($scentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$scenters=$this->mdl_html->genericlist( $scentersOptions, "scenter",array('class'=>'validate[required] text-input'),'value','text',$this->input->get('sc'));
		
		$brandOptions = $this->mdl_brands->getBrandOptions();
	
		$brand_select = $this->mdl_html->genericlist($brandOptions,'brand_id',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);

		//select box for products
		$productOptions = $this->mdl_products->getProductOptions(0);
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		
		$data = array(
					  'engineer_select'=>$engineer_select,
					  'scenters'=>$scenters,
					  'brand_select'=>$brand_select,
					  'product_select'=>$product_select
		);
		$this->load->view('reports/callreport/index',$data);
	}
	function generatecallreport(){
		ini_set("memory_limit","256M");
		$this->load->helper('url');
		$this->load->helper('calls');
		$this->load->model(array('mdl_callreports','productmodel/mdl_productmodel','brands/mdl_brands','cities/mdl_cities','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','customers/mdl_customers','servicecenters/mdl_servicecenters'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->get('currentpage');
		$calls = $this->mdl_callreports->getCallReports($page);
		$config['total_rows'] = $calls['total'];
		$this->ajaxpagination->cur_page=$this->input->get('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
					  'calls'=>$calls['list'],
					  'page'=>$page,
					  'config'=>$config,
					  'navigation'=>$navigation
		);
		$this->load->view('callreport/listcallreport',$data);
	}
	function getbrands(){
		$this->load->model(array('products/mdl_products','brands/mdl_brands','utilities/mdl_html'));
		$brands = $this->mdl_brands->getBrandOptions();
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'brands'=>$brands,
					  'ajaxaction'=>$ajaxaction
		);
		$this->load->view('callreport/listbrands',$data);
	}
	function getproductsbybrands()
	{
		$this->load->model(array('brands/mdl_brands','products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$productOptions = $this->mdl_products->getProductOptionsByBrands($this->input->post('brand_ids'));
		//array_unshift($productOptions,$this->mdl_html->option( '0', 'Select Product'));
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		echo $product_select;
		die();
	}
}
?>