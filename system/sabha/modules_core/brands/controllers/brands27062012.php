<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Brands extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("brands",  $this->mdl_mcb_data->setting('default_language'));
	}
	
	function index() {
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ustatuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$brand_status=$this->mdl_html->genericlist( $ustatuslist, 'brand_status' );
		$product_status=$this->mdl_html->genericlist( $ustatuslist, 'product_status' );
		$brandlist = $this->mdl_html->genericlist($this->mdl_brands->getBrandOptions(),'brand_select');
		$data = array(
					  'brand_status'=>$brand_status,
					  'product_status'=>$product_status
					  );
		$this->load->view('index',$data);
	}
	
	function getbrandlist(){
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$brands=$this->mdl_brands->getBrands($page);			
		$config['total_rows'] = $brands['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array(
					"brands"=>$brands['list'],
					"ajaxaction"=>$ajaxaction,
					"page"=>$page,
					"navigation"=>$navigation
					);
		$this->load->view('process', $data);
	}
		
	function getbrandetails(){
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands'));
		$brand_id = (int)$this->input->post('id');
		$brand=$this->mdl_brands->getBrandDetails($brand_id);
		echo json_encode($brand);
	}
	
	function savebrand(){
		$this->redir->set_last_index();
		$error = array();
		$this->load->model(array('brands/mdl_brands','products/mdl_products'));
		$id = $this->input->post('id');
		$data=array(
						"brand_name"=>$this->input->post('brandname'),
						"brand_desc"=>$this->input->post('description'),
						);
		$brand_result = $this->mdl_products->checkBrandByProduct($id);
		$msg = '';
		if($brand_result>0){
			$details = $this->mdl_brands->getBrandDetails($id);
			if($details->brand_status !=$this->input->post('status') ){
				$msg = ' but could not change status';
			}
		}else{
			$data["brand_status"]=$this->input->post('status');
		}
		if((int)$id==0){
			$data["brand_created_ts"]=date("Y-m-d H:i:s");
			$data["brand_created_by"]=$this->session->userdata('user_id');
			if($this->mdl_brands->save($data)){
				if($msg){
					$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_saved').$msg);
				}else{
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$data["brand_last_mod_ts"]=date("Y-m-d");
			$data["brand_last_mod_by"]=$this->session->userdata('user_id');
			if($this->mdl_brands->save($data,$id)){
				if($msg){
					$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_saved').$msg);
				}else{
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function changestatus(){
		$this->load->model(array('brands/mdl_brands','products/mdl_products'));
		$brand_id=$this->input->post('brand_id');
		$status = ($this->input->post('status')==1)?0:1;
		$msg = ($status==1)?'active':'inactive';
		$brand_result = $this->mdl_products->checkBrandByProduct($brand_id);
		
		if((int)$brand_id>0){
			$data=array('brand_status'=>$status);
			if($brand_result>0){
				$error = array('type'=>'failure','message'=>'This brand could not be made '.$msg);
			}else{
				if($this->mdl_brands->save($data,$brand_id)){
					$error = array('type'=>'success','message'=>'This brand has been '.$msg.' successfully');
				}else{
					$error = array('type'=>'failure','message'=>'This brand could not be '.$msg);
				}
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function deletebrand() {
		$this->load->model(array('brands/mdl_brands','products/mdl_products'));
		$brand_id=$this->input->post('brand_id');
		if($this->mdl_brands->delete(array('brand_id'=>$brand_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
}
?>