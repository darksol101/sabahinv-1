<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class servicemanuals extends Client_Center_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("servicemanuals",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('servicecenters/mdl_servicecenters');
	}
	function index(){
		$this->redir->set_last_index();		
		$this->load->model(array('brands/mdl_brands','utilities/mdl_html'));
		
		$brandOptions = $this->mdl_brands->getBrandOptions();
		array_unshift($brandOptions, $this->mdl_html->option( '0', 'All Brand'));
		$brand_select = $this->mdl_html->genericlist( $brandOptions, "brand_id",array('onchange'=>'getProductsByBrand(this.value);','class'=>'validate[required] text-input'));
		$product_select = '';
		$data = array(
					'brand_select'=>$brand_select,
					'product_select'=>$product_select
					);
		$this->load->view('index',$data);

	}
	function getproductsbybrand(){
		$this->redir->set_last_index();		
		$brand_id = $this->input->post('brand_id');
		$this->load->model(array('utilities/mdl_html','products/mdl_products'));
		$productOptions = $this->mdl_products->getProductsByBrand($brand_id);
		array_unshift($productOptions, $this->mdl_html->option( '0', 'All Products'));
		$product_select = $this->mdl_html->genericlist( $productOptions, "product_id",array('class'=>'validate[required] text-input'));
		echo $product_select;
	}
	function getbrandetails(){
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands'));
		$brand_id = (int)$this->input->post('id');
		$brand=$this->mdl_brands->getBrandDetails($brand_id);
		echo json_encode($brand);
		die();
	}
	function getservicemanualslist(){
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','manual/mdl_manual'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_manual->getProductManuals($page);	
		$productlist = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("servicemanuals"=>$productlist, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'navigation'=>$navigation);
		$this->load->view('process', $data);
	}
}
?>