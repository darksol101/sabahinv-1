<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Productmodel extends Admin_Controller {
	function __construct() {
		parent::__construct(TRUE);
		$this->load->language("productmodel",  $this->mdl_mcb_data->setting('default_language'));
		$this->_post_handler();
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel','products/mdl_products','brands/mdl_brands','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$statuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$model_status=$this->mdl_html->genericlist( $statuslist, 'model_status' );
		$brandOptions = $this->mdl_brands->getBrandOptions();
		array_unshift($brandOptions, $this->mdl_html->option( '', 'Select Brand'));
		$brandlist = $this->mdl_html->genericlist($brandOptions,'brand_select',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one'));
		$productOptions = $this->mdl_products->getProductOptions();
		array_unshift($productOptions, $this->mdl_html->option( '', 'Select Product'));
		$productlist = $this->mdl_html->genericlist($productOptions,'product_select',array('class'=>'validate[required] select-one'));
		$data = array(
					  'brandlist'=>$brandlist,
					  'productlist'=>$productlist,
					  'model_status'=>$model_status
					  );
		$this->load->view('index',$data);
	}
	function saveproductmodel(){
		$error =array();
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel'));
		$id = $this->input->post('id');
		$params = array(
						'model_number'=>$this->input->post('model_number'),
						'model_warranty'=>$this->input->post('model_warranty'),
						'brand_id'=>$this->input->post('brand_id'),
						'product_id'=>$this->input->post('product_id'),
						'model_desc'=>$this->input->post('model_desc'),
						'model_status'=>$this->input->post('model_status')
						);
		$total = $this->mdl_productmodel->checkModelNumber($id,$this->input->post('brand_id'),$this->input->post('product_id'),$this->input->post('model_number'));
		if($total>0){
			$error = array('type'=>'error','message'=>'Could not save, Model number already exists');
			$this->load->view('dashboard/ajax_messages',$error);
			return false;
		}
		if((int)$id>0){
			$params['model_created_ts']=date("Y-m-d H:i:s");
			if($this->mdl_productmodel->save($params,$id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['model_last_mod_ts']=date("Y-m-d H:i:s");
			if($this->mdl_productmodel->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function deleteproductmodel()
	{
		$this->redir->set_last_index();
		$error = array();
		$this->load->model(array('productmodel/mdl_productmodel'));
		$id = (int)$this->input->post('id');
		if($this->mdl_productmodel->delete(array('model_id'=>$id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getmodeldetails()
	{
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel'));
		$id = (int)$this->input->post('id');
		echo json_encode($this->mdl_productmodel->getModelDetails($id));
	}
	function getproductmodellist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel','products/mdl_products','brands/mdl_brands'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$productmodels = $this->mdl_productmodel->getProductModels($page);
		$config['total_rows'] = $productmodels['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$ajaxaction = $this->input->post('ajaxaction');
		
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'productmodels'=>$productmodels['list'],
					  'navigation'=>$navigation,
					  'page'=>$page
					  );
		$this->load->view('process',$data);
	}
	function checkmodelnumer()
	{
		$this->load->model(array('productmodel/mdl_productmodel'));
		$id = (int)$this->input->post('id');
		$brand_id = $this->input->post('brand_id');
		$product_id = $this->input->post('product_id');
		$model_number = $this->input->post('model_number');
		$total = $this->mdl_productmodel->checkModelNumber($id,$brand_id,$product_id,$model_number);
		if($total>0){
			echo 'This model number in use';
		}else{
			echo '';
		}
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
	function _post_handler() {		
	}
}

?>