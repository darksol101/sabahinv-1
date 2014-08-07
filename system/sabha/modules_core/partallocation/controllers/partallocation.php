<?php (defined('BASEPATH')) OR exit('no direct script access allowed');
class Partallocation extends Admin_Controller {
    function __construct(){
        parent::__construct();
        $this->load->language('partallocation',$this->mdl_mcb_data->setting('default_language'));
		$this->load->model(array('partallocation/mdl_partallocation'));
    }
	
	
	
	function index(){
		
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','parts/mdl_parts','engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html','company/mdl_company','servicecenters/mdl_servicecenters'));
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		
		
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('onchange'=>'getpartbyscid($(this).val()), getengineer($(this).val()); ','class'=>'validate[required] text-input'),'value','text','');
		
		$servicecenters_search=$this->mdl_html->genericlist( $scentersOptions, "sc_id_search",array('onchange'=>'getengineer1($(this).val()); ','class'=>'validate[required] text-input'),'value','text','');
		
	
		
		//$partnumber = $this->mdl_parts_stocks->getpartbyscid($sc_id);
		if($this->session->userdata('usergroup_id')==1){
		$part_options = $this->mdl_parts_stocks->getPartAssignmentOptions(0);
		}else{
		$part_options = $this->mdl_parts_stocks->getPartAssignmentOptions($this->session->userdata('sc_id'));
		}
		
		$company_options = $this->mdl_company->getCompanyOptions();
		
		array_unshift($company_options, $this->mdl_html->option( '', 'Select Company'));
		$company_options = $this->mdl_html->genericlist($company_options,'select_company',array('class'=>' select-one'),'value','text','');
		
		
		array_unshift($part_options,$this->mdl_html->option('','Select Item'));
		$part_options = $this->mdl_html->genericlist($part_options,'part_select',array('class'=>'validate[required]'));
		
		
		if($this->session->userdata('usergroup_id')==1){
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc(0);
		}else{
			$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($this->session->userdata('sc_id'));
		}
		
		
		
		
		//$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc(0);
		
		array_unshift($engineerOption,$this->mdl_html->option('','Select Engineer'));
		$engineer = $this->mdl_html->genericlist($engineerOption,'engineer',array('class'=>' select-one'),'value','text','');
		$engineerOption = $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>' validate[required]'),'value','text','');
		
		$data=array(
					'part_options'=>$part_options,
					'engineerOption'=>$engineerOption,
					'servicecenters' =>$servicecenters,
					'company_options'=>$company_options,
					'engineer'=>$engineer,
					'servicecenters'=>$servicecenters,
					'servicecenters_search'=>$servicecenters_search
		);
		$this->load->view('index',$data);
	}
	
	function saveallocation (){
		$ret =0;
		$this->load->model(array('stocks/mdl_parts_stocks','partallocation/mdl_partallocation_details'));
		$part_number = $this->input->post('part_number');
		$engineer_id = $this->input->post('engineer_id');
		$quantity = $this->input->post('quantity');
		//print_r( $this->input->post('sc_id'));
		$part = explode(':',$part_number);
		//print_r($part);
		//die();
		$part_number = $part[0];
		$company_id=$part[1];
		
		$data['engineer_id']= $engineer_id;
		$data['sc_id']= $this->input->post('sc_id');
		$data['part_number']=$part_number;
		$data['allocated_quantity']=$quantity;
		$data['company_id']=$company_id;
		$available = $this->mdl_parts_stocks->checkPartsStock($data['sc_id'],$part_number,$company_id);
		//print_r($available);
		//echo($available->stock_quantity);
		if ($available->stock_quantity < $quantity)
			{
				$ret = 1;
				echo $ret;
				die();
				}
		$ret = $this->mdl_partallocation->checkallocation($data);
		$this->mdl_partallocation_details->adddetail($data);
		$this->mdl_parts_stocks->updateallocatedpart($data); 
		echo $ret;
		die();
		}
		
		
		
	function allocationdetails(){
			
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','parts/mdl_parts','engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html','company/mdl_company'));
		$sc_id = $this->uri->segment(6);
		//$sc_id = $this->session->userdata('sc_id');
		
		//$partnumber = $this->mdl_parts_stocks->getpartbyscid($sc_id);
		$part_options = $this->mdl_parts_stocks->getPartAssignmentOptions($sc_id);
		
		array_unshift($part_options,$this->mdl_html->option('','Select Item'));
		$part_options = $this->mdl_html->genericlist($part_options,'part_select',array('class'=>'validate[required]'));
		
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
		
		array_unshift($engineerOption,$this->mdl_html->option('','Select Engineer'));
		$engineer = $this->mdl_html->genericlist($engineerOption,'engineer',array('class'=>' select-one'),'value','text','');
		$engineerOption = $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'validate[required]'));
		
		$company_options = $this->mdl_company->getCompanyOptions();
		array_unshift($company_options, $this->mdl_html->option( '', 'Select Company'));
		$company_options = $this->mdl_html->genericlist($company_options,'select_company',array('class'=>' select-one'),'value','text','');
		
		$data=array(
					'part_options'=>$part_options,
					'engineerOption'=>$engineerOption,
					'company_options'=>$company_options,
					'engineer'=>$engineer
					
		);
		$this->load->view('tab_engineers',$data);
		
		}
		
		
		
		
		function allocatedlist() 
		{
		 $this->redir->set_last_index();
	    $this->load->model(array('company/mdl_company','engineers/mdl_engineers','mcb_data/mdl_mcb_data','partallocation/mdl_partallocation_details','servicecenters/mdl_servicecenters','parts/mdl_parts'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		//$sc_id = $this->session->userdata('sc_id');
		$list =  $this->mdl_partallocation->getallocatedlist($page);
        $purchases = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
						  'lists' =>$list,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
			$this->load->view('allocationlist',$data);
			}
			
	function revoke(){
		$this->load->model(array('stocks/mdl_parts_stocks','partallocation/mdl_partallocation_details'));
		$test =0;
				 $test = $this->mdl_partallocation->revoke();
		echo $test;
		die();
		}
		
		
	 function partallocationdetails(){
		 $this->load->model(array('partallocation/mdl_partallocation_details','company/mdl_company','engineers/mdl_engineers'));
		 $return = $this->mdl_partallocation_details->detaillist();
		 $data = array(
					  'lists'=>$return
					  
					  );
		$this->load->view('partallocation/allocationdetails',$data);
			
			}
			
			
			
	function getpartbyscid($sc_id){
		
		$sc_id = $this->input->post('sc_id');
		$this->load->model(array('stocks/mdl_parts_stocks','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$part = $this->mdl_parts_stocks->getPartAssignmentOptions($sc_id);
		array_unshift($part, $this->mdl_html->option( '', 'Select Item'));
		$part_select  =  $this->mdl_html->genericlist($part,'part_name',array('class'=>'validate[required] select-one'),'value','text','');
		echo $part_select;
		
		}
		
		
		
		function getengineer(){
		$sc_id = $this->input->post('sc_id');
		$this->load->model(array('engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
		array_unshift($engineerOption, $this->mdl_html->option( '', 'Select Engineer'));
		$engineer_select  =  $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'select-one'),'value','text','');
		echo $engineer_select;
		}
		//  function for allocation report
	function printAllocationList() {
		$this->redir->set_last_index();
	    $this->load->model(array('company/mdl_company','engineers/mdl_engineers','partallocation/mdl_partallocation_details','partallocation/mdl_partallocation','servicecenters/mdl_servicecenters'));
		$list =  $this->mdl_partallocation->getallocatedlist_print();
   		$data = array('lists' =>$list);
		$this->load->view('printallocationlist',$data);
					
		}
		
		
		function allocationreport()
		{
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','parts/mdl_parts','engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html','company/mdl_company','servicecenters/mdl_servicecenters'));
		$statusOptions = $this->mdl_mcb_data->getStatusOptions('allocation_status');
		$status_select = $this->mdl_html->genericlist($statusOptions,'allocation_status',array('class'=>'text-input'),'value','text','');
		
			$sc_id_get=$this->uri->segment(3);
			$engineer_id_get=$this->uri->segment(4);
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'Select Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		$servicecenters_search=$this->mdl_html->genericlist( $scentersOptions, "sc_id_search",array('onchange'=>'getengineer($(this).val()); ','class'=>'validate[required] text-input'),'value','text',$sc_id_get);
					
		if($this->session->userdata('usergroup_id')==1){			
		$engineerOption = $this->mdl_engineers->getEngineerOptions();
			}
		else{
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($this->session->userdata('sc_id'));
			}
		
		array_unshift($engineerOption,$this->mdl_html->option('','Select Engineer'));
		$engineerOption = $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'text-input'),'value','text',$engineer_id_get);
		
		$data=array(
					'engineerOption'=>$engineerOption,
					'servicecenters_search'=>$servicecenters_search,
					'status_select'=>$status_select
		);
		$this->load->view('index_allocation',$data);
		}
		
	function showAllocationList() 
			{
		$this->redir->set_last_index();
	    $this->load->model(array('company/mdl_company','engineers/mdl_engineers','partallocation/mdl_partallocation_details','servicecenters/mdl_servicecenters'));
		$lists =  $this->mdl_partallocation->getallocatedlist_edit();
    	$data = array( 'lists' =>$lists );
		
		$this->load->view('showAllocationList',$data);
			}
			
	function unsignedlist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','parts/mdl_parts','engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html','company/mdl_company','servicecenters/mdl_servicecenters'));
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Stores'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
				
		$servicecenters_search=$this->mdl_html->genericlist( $scentersOptions, "sc_id_search",array('onchange'=>'getengineer($(this).val()); ','class'=>'validate[required] text-input'),'value','text','');
					
		if($this->session->userdata('usergroup_id')==1){
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc(0);
		}else{
			$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($this->session->userdata('sc_id'));
		}
		
				array_unshift($engineerOption,$this->mdl_html->option('','All Engineers'));
		
		$engineerOption = $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'text-input'),'value','text','');
		
		$data=array(
					'engineerOption'=>$engineerOption,
					'servicecenters_search'=>$servicecenters_search);
		
		$this->load->view('partallocation/tab_unsigned',$data);
		
	}
	
function getUnsignedList()
{
		$this->redir->set_last_index();
		$this->load->model(array('company/mdl_company','engineers/mdl_engineers','partallocation/mdl_partallocation_details','servicecenters/mdl_servicecenters'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$lists =  $this->mdl_partallocation->getUnsignedList($page);
   		$config['total_rows'] = $lists['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
    	$data = array( "lists" => $lists,
					  "ajaxaction"=>$ajaxaction,
					  'navigation'=>$navigation,
					  'page'=>$page,
					  'config'=>$config);
		$this->load->view('showunsignedlist',$data);
	
	
}

function savesigned(){
	$this->redir->set_last_index();
	$this->load->model('partallocation/mdl_partallocation_details');
	$alloc_id=$this->input->post('value');
	$alloc_id = chop($alloc_id,",");
	$result=explode(",",$alloc_id);
	
	foreach ($result as $res){
		
		$data['signed']=1;
		$this->mdl_partallocation_details->save($data,$res);
		}
	
	}
	
function create_excel(){
		$this->redir->set_last_index();
		ini_set("memory_limit","256M");
		$this->load->model(array('engineers/mdl_engineers','servicecenters/mdl_servicecenters','partallocation/mdl_partallocation','partallocation/mdl_partallocation_details'));
		$this->load->plugin('to_excel');
		$data = $this->mdl_partallocation->getAllocReportExcel();
		$datas = array();
		$i=1;
		foreach ($data as $row){
			
			$row->sn= $i;
			$i++;
			$datas[]=$row;
			}
		
		$fields = array('S.N','Store Name','Engineer Name','Item Number','Item description','Event','Allocated/Revoked Quantities','Date Time');
		$this->load->helper('download');
		$data = convert_to_table($datas,$fields);
		$name = 'allocation_unsigned_'.date("Y_m_d_H_i_s").'.xls';
		
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'Allocation Unsigned Report',
						  'user_agent'	=> $this->agent->agent_string(),
						  'user_ip'=>$this->input->ip_address(),
						  'download_by'=>$this->session->userdata('user_id'),
						  'download_ts'=>date('Y-m-d H:i:s')
		);
		$this->mdl_downloads->save($download_data);
		force_download($name, $data);	
	
}	

	
	function excel_ready(){
		
		$sc_id = $this->input->post('sc_id');
		$engineer = $this->input->post('engineer');
		
		$this->session->set_userdata('sc_id_ptall',$sc_id);
		$this->session->set_userdata('engineer_ptall',$engineer);
		}
		
		
		function excel_ready1(){
				
				$this->session->set_userdata('ses_engineer',$this->input->post('engineer'));
				$this->session->set_userdata('ses_sc_id',$this->input->post('sc_id'));
				$this->session->set_userdata('ses_company',$this->input->post('company'));
				$this->session->set_userdata('ses_searchtxt',$this->input->post('searchtxt'));
			
			}
		function excelDownload(){
			ini_set("memory_limit","256M");
		$this->load->model(array('servicecenters/mdl_servicecenters','company/mdl_company','partallocation/mdl_partallocation','parts/mdl_parts','engineers/mdl_engineers'));
		$this->load->plugin('to_excel');
		$this->load->helper('calls');
		$list = $this->mdl_partallocation->getallocatedlist_excel();
		
		$data = '';
		if($list->num_rows()==0){
			redirect('partallocation');
		}
		foreach($list->result() as $row){
			//$row->available_quantity = $row->stock_quantity + $row->allocated_quantity;
			$data[]=$row;
			
		}
		
		$fields = array('Engineer Name','Store','Item Number','Item description','Company','Allocated Quantity');
		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		$name = 'partallocation_'.date("Y_m_d_H_i_s").'.xls';
		/*
		 **keep track of downloads
		 */
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'calls',
						  'user_agent'	=> $this->agent->agent_string(),
						  'user_ip'=>$this->input->ip_address(),
						  'download_by'=>$this->session->userdata('user_id'),
						  'download_ts'=>date('Y-m-d H:i:s')
		);
		$this->mdl_downloads->save($download_data);
		/*
		 **ends here
		 */
		force_download($name, $data);
			
			}

}
?>