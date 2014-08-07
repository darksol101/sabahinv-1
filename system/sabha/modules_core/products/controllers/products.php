<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Products extends Admin_Controller {

	function __construct() {

		parent::__construct();
		$this->load->language("products",  $this->mdl_mcb_data->setting('default_language'));
	}

	function index() {

		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','category/mdl_category','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ustatuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$product_status=$this->mdl_html->genericlist( $ustatuslist, 'product_status' );
		$brandOptions = $this->mdl_brands->getBrandOptions();
		array_unshift($brandOptions,$this->mdl_html->option('','Select Brand'));
		$brandlist = $this->mdl_html->genericlist($brandOptions,'brand_select',array('class'=>'validate[required]'));
		$categoryOptions = $this->mdl_category->getCategoryOptions();
		array_unshift($categoryOptions,$this->mdl_html->option('','Select Category'));
		$category_select = $this->mdl_html->genericlist($categoryOptions,'category_select',array('class'=>'validate[required]'));
		$data=array(
					'product_status'=>$product_status,
					'brandlist'=>$brandlist,
					'category_select'=>$category_select
					);
		$this->load->view('index',$data);
	}

	function saveproduct(){
		$this->redir->set_last_index();
		$error = array();
		$this->load->model(array('products/mdl_products','productmodel/mdl_productmodel'));
		$product_id=$this->input->post('product_id');
		$data=array(
					"product_name"=>$this->input->post('product_name'),
					"product_desc"=>$this->input->post('product_desc'),
					"prod_category_id"=>$this->input->post('prod_category_id'),
					"brand_id"=>$this->input->post('brand_id')
					);
		$product_result = $this->mdl_productmodel->checkProductByModel($product_id);
		$msg = '';
		if($product_result>0){
			$details = $this->mdl_products->getProduct($product_id);
			if($details->product_status != $this->input->post('product_status') ){
				$msg = ' but could not change status';
			}
		}else{
			$data["product_status"]=$this->input->post('product_status');
		}
		if((int)$product_id==0){
			$data["product_created_ts"]=date("Y-m-d H:i:s");
			$data["product_created_by"]=$this->session->userdata('user_id');
			if($this->mdl_products->save($data)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_not_saved'));
			}
		}else{
			$data["product_last_mod_ts"]=date("Y-m-d H:i:s");
			$data["product_last_mod_by"]=$this->session->userdata('user_id');
			if($this->mdl_products->save($data, $product_id)){
				if($msg){
					$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_saved').$msg);
				}else{
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_not_saved'));
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getproductlist(){
		$this->redir->set_last_index();
		$this->load->model(array('products/mdl_products','brands/mdl_brands'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$products=$this->mdl_products->getProducts($page);
		$config['total_rows'] = $products['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array(
					"products"=>$products['list'], 
					'page'=>$page,
					"ajaxaction"=>$ajaxaction,
					'navigation'=>$navigation
					);
		$this->load->view('process', $data);
	}
	function changestatus()
	{
		$this->redir->set_last_index();
		$error = array();
		$this->load->model(array('products/mdl_products','productmodel/mdl_productmodel'));
		$id = $this->input->post('id');
		$status = ($this->input->post('status')==1)?0:1;
		$msg = ($status==1)?'made active':'made inactive';
		$product_result = $this->mdl_productmodel->checkProductByModel($id);
		if((int)$id>0){
			if($product_result>0){
				$error = array('type'=>'failure','message'=>'This product can not be '.$msg);
			}else{
				$data=array('product_status'=>$status);
				if($this->mdl_products->save($data,$id)){
					$error = array('type'=>'success','message'=>'This Product has been '.$msg.' successfully');
				}else{
					$error = array('type'=>'failure','message'=>'This product can not be '.$msg);
				}
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getproductdetails()
	{
		$this->redir->set_last_index();
		$this->load->model(array('products/mdl_products'));
		$product_id =$this->input->post('product_id');
		$details = $this->mdl_products->getProduct($product_id);
		echo json_encode($details);
	}
	function deleteproduct()
	{
		$this->redir->set_last_index();
		$this->load->model(array('products/mdl_products'));
		$product_id = $this->input->post('product_id');
		if($this->mdl_products->delete(array('product_id'=>$product_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getproductsbybrand()
	{
		$this->load->model(array('products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$brandOptions = $this->mdl_products->getProductsByBrand($this->input->post('brand_id'));
		array_unshift($brandOptions,$this->mdl_html->option( '', 'Select Product'));
		$brandlist = $this->mdl_html->genericlist($brandOptions,'product_select',array('class'=>'validate[required] text-input'),'value','text',$active);
		echo $brandlist;
	}
	function get($params = NULL) {

		return $this->mdl_users->get($params);
	}
}
?>