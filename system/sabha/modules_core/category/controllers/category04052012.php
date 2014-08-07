<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Category extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("category",  $this->mdl_mcb_data->setting('default_language'));
	}
	
	function index() {
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$statuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$category_status=$this->mdl_html->genericlist( $statuslist, 'category_status' );
		$data = array(
					  'category_status'=>$category_status
					  );
		$data = array(
					  'category_status'=>$category_status
					  );
		$this->load->view('index',$data);
	}
	
	function getcategorylist(){
		$this->redir->set_last_index();
		$this->load->model(array('category/mdl_category','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$categories=$this->mdl_category->getCategories($page);			
		$config['total_rows'] = $categories['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array(
					"categories"=>$categories['list'],
					"ajaxaction"=>$ajaxaction,
					"page"=>$page,
					"navigation"=>$navigation
					);
		$this->load->view('process', $data);
	}
		
	function getcategoryetails(){
		$this->redir->set_last_index();
		$this->load->model(array('category/mdl_category'));
		$prod_category_id = (int)$this->input->post('prod_category_id');
		$category=$this->mdl_category->getCategoryDetails($prod_category_id);
		echo json_encode($category);
	}
	
	function savecategory(){
		$this->redir->set_last_index();
		$this->load->model(array('category/mdl_category','products/mdl_products'));
		$id = $this->input->post('id');
		$error = array();
		$data=array(
						"prod_category_name"=>$this->input->post('prod_category_name'),
						"prod_category_desc"=>$this->input->post('prod_category_desc'),
						);
		$category_result = $this->mdl_products->checkCategoryByProduct($id);
		$msg = '';
		if($category_result>0){
			$details = $this->mdl_category->getCategoryDetails($id);
			if($details->prod_category_status !=$this->input->post('prod_category_status') ){
				$msg = ' but could not change status';
			}
		}else{
			$data["prod_category_status"]=$this->input->post('prod_category_status');
		}
		if((int)$id==0){
			$data["prod_category_created_ts"]=date("Y-m-d");
			$data["prod_category_created_by"]=$this->session->userdata('user_id');
			if($this->mdl_category->save($data)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved').$msg);
			}
		}else{
			$data["prod_category_last_mod_ts"]=date("Y-m-d");
			$data["prod_category_last_mod_by"]=$this->session->userdata('user_id');
			if($this->mdl_category->save($data,$id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved').$msg);
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function changestatus(){
		$this->load->model(array('category/mdl_category','products/mdl_products'));
		$id=$this->input->post('id');
		$status = ($this->input->post('status')==1)?0:1;
		$msg = ($status==1)?'published':'unpublished';
		$category_result = $this->mdl_products->checkCategoryByProduct($id);
		if((int)$id>0){
			$data=array('prod_category_status'=>$status);
			if($category_result>0){
				$error = array('type'=>'warning','message'=>'This category can not be '.$msg);
			}else{
				if($this->mdl_category->save($data,$id)){
					$error = array('type'=>'success','message'=>'This category has been '.$msg.' successfully');
				}
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function deletecategory() {
		$error = array();
		$this->load->model(array('category/mdl_category','products/mdl_products'));
		$prod_category_id=$this->input->post('prod_category_id');
		if($this->mdl_category->delete(array('prod_category_id'=>$prod_category_id))){
			$error = array('type'=>'delete','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function tab_category()
	{
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$statuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$category_status=$this->mdl_html->genericlist( $statuslist, 'category_status' );
		$data = array(
					  'category_status'=>$category_status
					  );
		$this->load->view('tab_category',$data);
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
}
?>