<?php defined('BASEPATH') or die('Direct access script is not allowed');
class Badparts extends Admin_Controller{
	public function  __construct(){
		parent::__construct();
		$this->load->language("badparts",  $this->mdl_mcb_data->setting('default_language'));
	}
	public function index(){
		 $this->load->model(array('badparts/mdl_badparts','engineers/mdl_engineers','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
            $sc_id = $this->session->userdata('sc_id');
            $badpartsOptions = array();
            array_unshift($badpartsOptions, $this->mdl_html->option( '', 'Select Item'));
            $badparts_select  =  $this->mdl_html->genericlist($badpartsOptions,'part_number',array('class'=>'validate[required]'),'value','text','');


		if($this->session->userdata('usergroup_id')==1){
            $serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptions();
		}else { $serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptionsBySc($sc_id);}
			
			
			
			
            array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
            $servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'sc_id',array('class'=>'validate[required]','onchange'=>'getEngineerBySvc($(this).val());'),'value','text',$this->session->userdata('sc_id'));
            
            $engineerOptions = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
            array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
            $engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer_id',array('class'=>'validate[required]','onchange'=>'getPartsByEngineer($(this).val());'),'value','text','');
			
			if($this->session->userdata('usergroup_id')==1){
			$serviceCentersOptions_search = $this->mdl_servicecenters->getServiceCentersOptions();
			}else { $serviceCentersOptions_search= $this->mdl_servicecenters->getServiceCentersOptionsBySc($sc_id);}
			
            array_unshift($serviceCentersOptions_search, $this->mdl_html->option( '', 'Select Store'));
            $servicecenter_select_search  =  $this->mdl_html->genericlist($serviceCentersOptions_search,'sc_id_search',array('class'=>'validate[required]','onchange'=>'getEngineerBySvc_search($(this).val());'),'value','text',$this->session->userdata('sc_id'));
			
			 $engineerOptions_search = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
            array_unshift($engineerOptions_search, $this->mdl_html->option( '', 'Select Engineer'));
            $engineer_select_search  =  $this->mdl_html->genericlist($engineerOptions_search,'engineer_id_search',array('class'=>'validate[required]'),'value','text','');
            
            $data = array(
                        'servicecenter_select'=>$servicecenter_select,
						'servicecenter_select_search'=>$servicecenter_select_search,
						'engineer_select_search'=>$engineer_select_search,
                        'engineer_select'=>$engineer_select,
                        'badparts_select'=>$badparts_select
                        );
		
		$this->load->view('bad_parts/index',$data);
	}
	public function transfer(){
		$data = array();
		$this->load->view('bad_parts/index',$data);
	}
	
	public function getbadparts(){
		$this->load->model(array('badparts/mdl_badparts','servicecenters/mdl_servicecenters','engineers/mdl_engineers','parts/mdl_parts'));
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$list = $this->mdl_badparts->getbadparts($page);
		$bad_parts = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$data = array(
					  'bad_parts'=>$bad_parts,
					  'navigation'=>$navigation
					  );
		$this->load->view('bad_parts/badpartslist',$data);
	}
	function edit(){
		$this->load->model(array('parts/mdl_bad_parts','stocks/mdl_stocks','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$bad_parts_id = $this->uri->segment(4);
		$this->form_validation->set_rules('bad_parts_id', '', 'required|int');
		if ($this->form_validation->run() == FALSE){
		}else{
			$parts_data['sc_id'] = $this->input->post('sc_id');
			$parts_data['bad_parts_status'] = $this->input->post('bad_parts_status');
			$parts_data['bad_parts_last_mod_by'] = $this->session->userdata('bad_parts_last_mod_by');
			$parts_data['bad_parts_last_mod_ts'] = date('Y-m-d H:i:s');
			if($this->mdl_bad_parts->save($parts_data,$this->input->post('bad_parts_id'))){
				$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				redirect('parts/bad_parts/edit/'.$this->input->post('bad_parts_id'));
			}
			
		}
		$bad_part_details = $this->mdl_bad_parts->getbadpartdetails($bad_parts_id);
		$serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'sc_id',array(),'value','text',$bad_part_details->sc_id);
		
		$partstatuslist=$this->mdl_mcb_data->getStatusOptions('bad_parts_status');
		array_unshift($partstatuslist, $this->mdl_html->option( '0', 'Select Status'));
		$bad_parts_status=$this->mdl_html->genericlist( $partstatuslist, 'bad_parts_status' ,array(),'value','text',$bad_part_details->bad_parts_status);
	   
		
		//echo $this->db->last_query();
		$data = array(
					  'bad_part_details'=>$bad_part_details,
					  'servicecenter_select'=>$servicecenter_select,
					  'bad_parts_status'=>$bad_parts_status
					  );
		$this->load->view('parts/bad_parts/edit',$data);
	}
	function getservicecenters(){
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$sc_id = $this->input->post('sc_id');
		$serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptionsExclude($sc_id);
		array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$servicecenter_select_to  =  $this->mdl_html->genericlist($serviceCentersOptions,'to_sc_id',array(),'value','text',0);
		echo $servicecenter_select_to;
	}
	function getjsonparts(){
		$this->load->model(array('stocks/mdl_stocks','servicecenters/mdl_servicecenters','engineers/mdl_engineers','parts/mdl_bad_parts'));
		$parts = $this->mdl_bad_parts->getbadparts('');
		$json = array();
		echo json_encode($parts);
	}
	function enginer_transfer(){
		$this->load->model(array('parts/mdl_bad_parts','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$servicecenter_select_from  =  $this->mdl_html->genericlist($serviceCentersOptions,'from_sc_id',array('onchange'=>'getServiceCenterSelect($(this).val());'),'value','text',$this->session->userdata('sc_id'));
		
		$serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptionsExclude($this->session->userdata('sc_id'));
		array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$servicecenter_select_to  =  $this->mdl_html->genericlist($serviceCentersOptions,'to_sc_id',array(),'value','text',0);
		$data = array(
					'servicecenter_select_from'=>$servicecenter_select_from,
					'servicecenter_select_to'=>$servicecenter_select_to
					);		
		$this->load->view('parts/engineer_transfer/index',$data);
	}
	
	
	function defectivestock(){
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html','parts/mdl_parts'));
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'validate[required] text-input'),'value','text','');
		$data = array(
					   'servicecenters'=>$servicecenters,
					  
);
		$this->load->view('bad_parts/stock_index',$data);
	}
		
	
	
	function showDefectivestocklist(){
		$this->load->model(array('servicecenters/mdl_servicecenters','badparts/mdl_returnparts','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$stocklist = $this->mdl_returnparts->getStocksList($page);
	
		
        $stockdata = $stocklist['list'];
		$config['total_rows'] = $stocklist['total'];
		
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		
		$data = array(
						 'stocklist'=>$stockdata,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
		
		$this->load->view('bad_parts/bad_stocklist',$data);
	}
	
	function exceldownload(){
	
			$this->session->set_userdata('sc_id_badstock',$this->input->post('sc_id'));
			$this->session->set_userdata('searchtxt_badstock',$this->input->post('searchtxt'));
	}
	
	function create_excel()
	{
		ini_set("memory_limit","256M");
		$this->load->model(array('servicecenters/mdl_servicecenters','badparts/mdl_returnparts','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$this->load->plugin('to_excel');
		$this->load->helper('calls');
		$list = $this->mdl_returnparts->getStocksListdownload();
		$data = '';
		if($list->num_rows()==0){
			redirect('badparts/defectivestocks');
		}
		foreach($list->result() as $row){
			$i=1;
			$row->return_part_id = $i;
			$data[]=$row;
			$i++;
			
		}
		
		$fields = array('S.no','Store','Item Number','Item description','Part Quantity');
		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		$name = 'Defected_parts_'.date("Y_m_d_H_i_s").'.xls';
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
	
	
	
	function badpartentry(){
		
		 $this->load->model(array('badparts/mdl_badparts','engineers/mdl_engineers','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
            $sc_id = $this->session->userdata('sc_id');
            $partsoption = array();
            array_unshift($partsoption, $this->mdl_html->option( '', 'Select Item'));
            $part_select  =  $this->mdl_html->genericlist($partsoption,'part_number',array('class'=>'validate[required]'),'value','text','');

            $serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptions();
            array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
            $servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'sc_id',array('class'=>'validate[required]'),'value','text',$this->session->userdata('sc_id'));
			
			$serviceCentersOptions_search = $this->mdl_servicecenters->getServiceCentersOptions();
            array_unshift($serviceCentersOptions_search, $this->mdl_html->option( '', 'Select Store'));
            $servicecenter_revoke  =  $this->mdl_html->genericlist($serviceCentersOptions_search,'sc_id_revoke',array('class'=>'validate[required]','onchange'=>'getpartsbyscid($(this).val());'),'value','text','');
			
			
			$companyOptions = array();
			array_unshift($companyOptions,$this->mdl_html->option('','Select Company '));
			$company_select= $this->mdl_html->genericlist($companyOptions,'company_select',array('class'=>'validate[required]'),'value','text','');
			
			$badpart_reason =  $this->mdl_mcb_data->getStatusOptions('badpart_reason');
			array_unshift($badpart_reason,$this->mdl_html->option('','Select Reason '));
			$badpart_reasons= $this->mdl_html->genericlist($badpart_reason,'badpart_reason',array('class'=>'validate[required]'),'value','text','');
			
			$data = array(
                        'servicecenter_select'=>$servicecenter_select,
						'servicecenter_revoke'=>$servicecenter_revoke,
                        'part_select'=>$part_select,
						'company_select'=>$company_select,
						'badpart_reasons'=>$badpart_reasons
						
                        );
		$this->load->view('bad_parts/badpartentry_index',$data);
		
		}
		
		
	function getPartsByScId(){
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$sc_id = $this->input->post('sc_id');
		$part = $this->mdl_parts_stocks->getPartOptionsByScId($sc_id);
		array_unshift($part, $this->mdl_html->option( '', 'Select Item'));
         $part_select  =  $this->mdl_html->genericlist($part,'part_number',array('class'=>'validate[required]','onchange'=>'getCompanyByParts($(this).val());'),'value','text','');
		echo $part_select;
		
		}
		
	
	function getCompanyOptionsByPart(){
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','company/mdl_company','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$sc_id = $this->input->post('sc_id');
		$part_number = $this->input->post('part_number');
		$company = $this->mdl_parts_stocks->getCompanyByPart($sc_id,$part_number);
		array_unshift($company, $this->mdl_html->option( '', 'Select Company'));
        $company_select  =  $this->mdl_html->genericlist($company,'company_select',array('class'=>'validate[required]'),'value','text','');
		echo $company_select;
		}
		
		
		
	function revokeGoodPart(){
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','badparts/mdl_returnparts','badparts/mdl_badpart_details'));
		
		$data_rev['sc_id'] = $this->input->post('sc_id');
		$data_rev['company'] = $this->input->post('company');
		$data_rev['part_number'] = $this->input->post('part_number');
		$data_rev['quantity'] = $this->input->post('quantity');
		$available_goodpart = $this->mdl_parts_stocks->getquantity($data_rev['sc_id'],$data_rev['part_number'],$data_rev['company']);
		if ($available_goodpart < $data_rev['quantity']){
			echo 1;
			die();
			
			}
		else{
			$this->mdl_returnparts->goodtobadpart($data_rev);
			$this->mdl_parts_stocks->updatefrombad($data_rev);
			$data_rev['badpart_entry_created_ts'] = date('Y-m-d h:i:s');
			$data_rev['badpart_entry_created_by'] = $this->session->userdata('user_id');
			$data_rev['reason']= $this->input->post('reason');
			$this->mdl_badpart_details->save($data_rev);
			}
		}
		
		
	function badpartdetails(){
			$this->redir->set_last_index();
			$this->load->view('badparts/bad_parts/tab_badpartsdetails');
			
							}
					
	function badpartdetailslist(){
			$this->redir->set_last_index();
			$this->load->model(array('badparts/mdl_badpart_details','company/mdl_company','mcb_data/mdl_mcb_data'));
			//$sc_id = $this->uri->segment('3');
			//$part_number = $this->uri->segment('4');
			$results = $this->mdl_badpart_details->getReturnPartDetails();
			$data=array('results'=>$results);
			$this->load->view('badparts/bad_parts/badpartsdetailslist',$data);
			
			}
			
	
		function returnlist() {
		$this->redir->set_last_index();
		$this->load->model(array('engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
		$sc_id_get=$this->uri->segment('3');
		$eng_id_get=$this->uri->segment('4');
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Stores'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
				
		$servicecenters_search=$this->mdl_html->genericlist( $scentersOptions, "sc_id_search",array('onchange'=>'getengineer($(this).val()); ','class'=>'validate[required] text-input'),'value','text',$sc_id_get);
					
		if($this->session->userdata('usergroup_id')==1){
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($sc_id_get);
		}else{
			$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($this->session->userdata('sc_id'));
		}
		
				array_unshift($engineerOption,$this->mdl_html->option('','All Engineers'));
		
		$engineerOptions = $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'text-input'),'value','text',$eng_id_get);
		
		$data=array(
					'engineerOption'=>$engineerOptions,
					'servicecenters_search'=>$servicecenters_search);
		
		$this->load->view('badparts/bad_parts/tab_returnlist',$data);
		}	
		
		function getengineer(){
		$sc_id = $this->input->post('sc_id');
		$this->load->model(array('engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
		array_unshift($engineerOption, $this->mdl_html->option( '', 'Select Engineer'));
		$engineer_select  =  $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'select-one'),'value','text','');
		echo $engineer_select;
		}
		
	function getretrunlist()
			{
	
		$this->redir->set_last_index();
		$this->load->model(array('badparts/mdl_returnparts_details','parts/mdl_parts','engineers/mdl_engineers','servicecenters/mdl_servicecenters'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$lists =  $this->mdl_returnparts_details->getretrunlist($page);
   		$config['total_rows'] = $lists['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data = array("lists" => $lists,
					  "ajaxaction"=>$ajaxaction,
					  'navigation'=>$navigation,
					  'page'=>$page,
					  'config'=>$config);

		$this->load->view('badparts/bad_parts/showreturnlist',$data);
		}
		
	function create_excel_return_list(){
		$this->redir->set_last_index();
		ini_set("memory_limit","256M");
		$this->load->model(array('engineers/mdl_engineers','servicecenters/mdl_servicecenters','badparts/mdl_returnparts_details','parts/mdl_parts'));
		$this->load->plugin('to_excel');
		$data = $this->mdl_returnparts_details->getReturnReportExcel();
		$datas = array();
		$i=1;
		foreach ($data as $row){
			if($row->signed == 1 ){ $row->signed = "Signed"; } else { $row->signed = "Unsigned" ;}
			$row->return_parts_detail_id= $i;
			$i++;
			$datas[]=$row;
			}
		
		$fields = array('S.N','Store','Engineer Name','Item Number','Item description','Quantities','Date Time','Signed/Unsigned');
		$this->load->helper('download');
		$data = convert_to_table($datas,$fields);
		$name = 'return_list_'.date("Y_m_d_H_i_s").'.xls';
		
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'Bad parts Return Report',
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
		$engineer_id = $this->input->post('engineer_id');
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$this->session->set_userdata('sc_id_rl',$sc_id);
		$this->session->set_userdata('fromdate_rl',$fromdate);
		$this->session->set_userdata('todate_rl',$todate);
		$this->session->set_userdata('engineer_id_rl',$engineer_id);
		}

	function savesigned(){
	$this->redir->set_last_index();
	$this->load->model('badparts/mdl_returnparts_details');
	$return_list=$this->input->post('value');
	if($return_list){
	$return_list = chop($return_list,",");
	$result=explode(",",$return_list);
	
	foreach ($result as $res){
		
		$data['signed']=1;
		$this->mdl_returnparts_details->save($data,$res);
		}
	}
	
	}
	
	function printReturnList() {
		$this->redir->set_last_index();
	    $this->load->model(array('engineers/mdl_engineers','servicecenters/mdl_servicecenters','badparts/mdl_returnparts_details','parts/mdl_parts'));
		$list =  $this->mdl_returnparts_details->getRetunlist_print();
   		$data = array('lists' =>$list);
		$this->load->view('badparts/bad_parts/printreturnlist',$data);
					
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
		
		$this->load->view('badparts/bad_parts/tab_unsigned',$data);
		
	}
	
function getUnsignedList()
{
		$this->redir->set_last_index();
		$this->load->model(array('company/mdl_company','engineers/mdl_engineers','badparts/mdl_returnparts_details','parts/mdl_parts','servicecenters/mdl_servicecenters'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$lists =  $this->mdl_returnparts_details->getUnsignedList($page);
   		$config['total_rows'] = $lists['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
    	$data = array( "lists" => $lists,
					  "ajaxaction"=>$ajaxaction,
					  'navigation'=>$navigation,
					  'page'=>$page,
					  'config'=>$config);
		$this->load->view('badparts/bad_parts/showunsignedlist',$data);
	
	}	
	

function create_excel_bad_unsign(){
		$this->redir->set_last_index();
		ini_set("memory_limit","256M");
		$this->load->model(array('engineers/mdl_engineers','servicecenters/mdl_servicecenters','badparts/mdl_returnparts_details','parts/mdl_parts'));
		$this->load->plugin('to_excel');
		$data = $this->mdl_returnparts_details->getUnsignedDownload();
		$datas = array();
		$i=1;
		foreach ($data as $row){
			$row->sn= $i;
			$i++;
			$datas[]=$row;
			}
		
		$fields = array('S.N','Store','Engineer Name','Item Number','Item description','Quantities','Created Date');
		$this->load->helper('download');
		$data = convert_to_table($datas,$fields);
		$name = 'badparts_unsigned_'.date("Y_m_d_H_i_s").'.xls';
		
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'Bad Parts Unsigned Report',
						  'user_agent'	=> $this->agent->agent_string(),
						  'user_ip'=>$this->input->ip_address(),
						  'download_by'=>$this->session->userdata('user_id'),
						  'download_ts'=>date('Y-m-d H:i:s')
		);
		$this->mdl_downloads->save($download_data);
		force_download($name, $data);	
	
}	

	
	function excel_ready_unsigned_badparts(){
		
		$sc_id = $this->input->post('sc_id');
		$engineer = $this->input->post('engineer');
		
		$this->session->set_userdata('sc_id_bad_unsign',$sc_id);
		$this->session->set_userdata('engineer__bad_unsign',$engineer);
		}

}

?>