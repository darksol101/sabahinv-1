<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Repair extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("repair",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','products/mdl_products','faultsettings/mdl_symptom','faultsettings/mdl_defect','utilities/mdl_html'));
		$sysmptomOptions = $this->mdl_symptom->getSymptomOptions();
		array_unshift($sysmptomOptions, $this->mdl_html->option( '', 'Select Symptom Code'));
		$symptom_select  =  $this->mdl_html->genericlist($sysmptomOptions,'symptom_code',array('class'=>'validate[required] select-one','onchange'=>'getDefectCodeBySymptom(this.value);'),'value','text','');
		$defectOptions = array();
		array_unshift($defectOptions, $this->mdl_html->option( '', 'Select Defect Code'));
		$defect_select  =  $this->mdl_html->genericlist($defectOptions,'defect_code',array('class'=>'validate[required] select-one'),'value','text','');
		
		//select options for brands
		$brandOptions = $this->mdl_brands->getBrandOptions();
		array_unshift($brandOptions, $this->mdl_html->option( '', 'All Brand'));		
		$brand_search = $this->mdl_html->genericlist($brandOptions,'brand_search',array('onchange'=>'getProductBybrandSearch($(this).val());','class'=>''),'value','text','');
		
		//get product select box
		$productOptions = $this->mdl_products->getProductOptions(0);
		array_unshift($productOptions, $this->mdl_html->option( '', 'All Product'));
		$product_search = $this->mdl_html->genericlist($productOptions,'product_search',array(),'value','text','');
		
		//list box for active/inactive
		$statuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$repair_status=$this->mdl_html->genericlist( $statuslist, 'repair_status' );
		
		$data = array(
					  'symptom_select'=>$symptom_select,
					  'defect_select'=>$defect_select,
					  'brand_search'=>$brand_search,
					  'product_search'=>$product_search,
					  'repair_status'=>$repair_status
					);
		
		$this->load->view('repair/repair', $data);
	}
	function getrepairlist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','products/mdl_products','faultsettings/mdl_repair','faultsettings/mdl_symptom','faultsettings/mdl_defect'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_repair->getRepairlist($page);	
		$repairs = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("repairs"=>$repairs, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	function saverepair(){
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_repair');
		$repair_code = $this->input->post('repair_code');
		$repair_description = $this->input->post('repair_description');
		$defect_id= $this->input->post('defect_id');
		$repair_id = $this->input->post('repair_id');
		$repair_status = $this->input->post('repair_status');
		$params = array(
					  'repair_code'=>$repair_code ,
					  'defect_id'=>$defect_id,
					  'repair_description'=>$repair_description,
					  'repair_status'=>$repair_status
					  );
		if((int)$repair_id==0){
			$params['repair_created_ts'] = date('Y-m-d H:i:s');
			$params['repair_created_by'] = $this->session->userdata('user_id');
			if($this->mdl_repair->save($params)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'error','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$params['repair_last_mod_ts'] = date('Y-m-d H:i:s');
			$params['repair_last_mod_by'] = $this->session->userdata('user_id');
			if($this->mdl_repair->save($params,$repair_id)){
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
		$this->load->model('faultsettings/mdl_repair');
		$repair_id = $this->input->post('repair_id');
		$status = ($this->input->post('status')==0)?1:0;
		$params = array('repair_status'=>$status);
		if($this->mdl_repair->save($params,$repair_id)){
			$error = array('type'=>'success','message'=>$this->lang->line('status_changed'));
		}else{
			$error = array('type'=>'error','message'=>$this->lang->line('status_not_changed'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getrepairdetails(){
		$this->redir->set_last_index();
		$this->load->model(array('faultsettings/mdl_defect','faultsettings/mdl_symptom','faultsettings/mdl_repair'));
		$repair_id = (int)$this->input->post('repair_id');
		echo json_encode($this->mdl_repair->getrepairdetails($repair_id));
	} 
	function deleterepair()
	{
		$this->redir->set_last_index();
		$this->load->model('faultsettings/mdl_repair');
		$repair_id = (int)$this->input->post('repair_id');
		if($this->mdl_repair->delete(array('repair_id'=>$repair_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getdefectcodebysymptom()
	{
		$this->redir->set_last_index();
		$this->load->model(array('faultsettings/mdl_defect','utilities/mdl_html'));
		$symptom_id = $this->input->post('symptom_id');
		$active = $this->input->post('active');
		
		$defectOptions = $this->mdl_defect->getDefectOptionsBySymptom($symptom_id);
		array_unshift($defectOptions, $this->mdl_html->option( '', 'Select Defect Code'));
		$defect_select  =  $this->mdl_html->genericlist($defectOptions,'defect_code',array('class'=>'validate[required] select-one'),'value','text',$active);
		echo $defect_select;
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