<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Defect extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("defect",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','products/mdl_products','faultsettings/mdl_symptom','utilities/mdl_html'));
		$sysmptomOptions = array();
		array_unshift($sysmptomOptions, $this->mdl_html->option( '', 'Select Symptom'));
		$symptom_select  =  $this->mdl_html->genericlist($sysmptomOptions,'symptom_code',array('class'=>'validate[required] select-one'),'value','text','');
		$dstatuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$defect_status=$this->mdl_html->genericlist( $dstatuslist, 'defect_status' );
		
		$brandOptions = $this->mdl_brands->getBrandOptions();
		array_unshift($brandOptions, $this->mdl_html->option( '', 'Select Brand'));
		$brand_select = $this->mdl_html->genericlist($brandOptions,'brand_select',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] text-input'),'value','text','');
		unset($brandOptions[0]);
		array_unshift($brandOptions, $this->mdl_html->option( '', 'All Brand'));
		$brand_search = $this->mdl_html->genericlist($brandOptions,'brand_search',array('onchange'=>'getProductBybrandSearch($(this).val());','class'=>''),'value','text','');
		
		//get product select box
		$productOptions = array();
		array_unshift($productOptions, $this->mdl_html->option( '', 'Select Product'));
		$product_select = $this->mdl_html->genericlist($productOptions,'product_select',array('onchange'=>'getSymptomsByProduct(this.value)','class'=>'validate[required] text-input'),'value','text','');
		unset($productOptions[0]);
		array_unshift($productOptions, $this->mdl_html->option( '', 'All Product'));
		$product_search = $this->mdl_html->genericlist($productOptions,'product_search',array(),'value','text','');
		
		$data = array(
					  'symptom_select'=>$symptom_select,
					  'defect_status'=>$defect_status,
					  'brand_select'=>$brand_select,
					  'brand_search'=>$brand_search,
					  'product_select'=>$product_select,
					  'product_search'=>$product_search
					);
		
		$this->load->view('defect/defect', $data);
	}
	function getdefectlist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','products/mdl_products','faultsettings/mdl_defect','faultsettings/mdl_symptom'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_defect->getDefectlist($page);	
		$defects = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("defects"=>$defects, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function savedefect(){
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_defect');
		$defect_code = $this->input->post('defect_code');
		$defect_description = $this->input->post('defect_description');
		$symptom_id= $this->input->post('symptom_id');
		$defect_id = $this->input->post('defect_id');
		$defect_status = $this->input->post('defect_status');
		$params = array(
					  'defect_code'=>$defect_code ,
					  'symptom_id'=>$symptom_id,
					  'defect_description'=>$defect_description,
					  'defect_status'=>$defect_status
					  );
		if((int)$defect_id==0){
			$params['defect_created_ts'] = date('Y-m-d H:i:s');
			$params['defect_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_defect->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['defect_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['defect_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_defect->save($params,$defect_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
			
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	
	function changestatus()
	{
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_defect');
		$defect_id = $this->input->post('defect_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('defect_status'=>$status);
		if($this->mdl_defect->save($params,$defect_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getdefectdetails(){
		$this->redir->set_last_index();
		$this->load->model(array('products/mdl_products','faultsettings/mdl_symptom','faultsettings/mdl_defect'));
		$defect_id = (int)$this->input->post('defect_id');
		echo json_encode($this->mdl_defect->getdefectdetails($defect_id));
	} 
	function deletedefect()
	{
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_defect');
		$defect_id = (int)$this->input->post('defect_id');
		if($this->mdl_defect->delete(array('defect_id'=>$defect_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getdefectsearch()
	{
	    $this->redir->set_last_index();
		$ajaxaction=$this->input->post('ajaxaction');
		$defectlist=$this->mdl_defect->getDefectsearch();
		$data=array("cities"=>$citylist, "ajaxaction"=>$ajaxaction);
		$this->load->view('process', $data);
	}
	function getproductsbybrand()
	{
		$this->load->model(array('products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$brandOptions = $this->mdl_products->getProductsByBrand($this->input->post('brand_id'));
		array_unshift($brandOptions,$this->mdl_html->option( '', 'Select Product'));
		$brandlist = $this->mdl_html->genericlist($brandOptions,'product_select',array('onchange'=>'getSymptomsByProduct(this.value)','class'=>'validate[required] text-input'),'value','text',$active);
		echo $brandlist;
	}
	function getsymptomsbyproduct(){
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','products/mdl_products','faultsettings/mdl_symptom','utilities/mdl_html'));
		$product_id = $this->input->post('product_id');
		$active = (int)$this->input->post('active');
		$sysmptomOptions = $this->mdl_symptom->getSymptomOptionsByProduct($product_id);
		array_unshift($sysmptomOptions, $this->mdl_html->option( '', 'Select Symptom'));
		$symptom_select  =  $this->mdl_html->genericlist($sysmptomOptions,'symptom_code',array('class'=>'validate[required] select-one'),'value','text',$active);
		echo $symptom_select ;
	}
	function getproductsbybrandsearch()
	{
		$this->load->model(array('products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$brandOptions = $this->mdl_products->getProductsByBrand($this->input->post('brand_id'));
		array_unshift($brandOptions,$this->mdl_html->option( '', 'All Product'));
		$brandlist = $this->mdl_html->genericlist($brandOptions,'product_search',array('class'=>'validate[required] text-input'),'value','text',$active);
		echo $brandlist;
	}
}
?>