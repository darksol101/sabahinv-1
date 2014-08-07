<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Callcenter extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("callcenter",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('mdl_callcenter');
		$this->_post_handler();
		if($this->session->userdata('global_admin')!=1 && $this->uri->segment(2)=='callregistration'){
			$this->session->set_flashdata('custom_error','Under maintenance for registration page');
			//redirect('callcenter/calls');
		}
	}

	function index() {
		$this->calls();
	}
	function calls() {
		$this->redir->set_last_index();
		$this->load->helper('url');
		$this->load->helper('calls');
		$this->load->model(array('productmodel/mdl_productmodel','brands/mdl_brands','cities/mdl_cities','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','mcb_data/mdl_mcb_data','customers/mdl_customers','servicecenters/mdl_servicecenters','utilities/mdl_html','productmodel/mdl_product_serial_number'));

		$page = $this->uri->segment(3);
		$this->load->model(array('mcb_data/mdl_mcb_data'));
		$this->load->library('pagination');
		$searchtext = $this->input->get('searchtxt');
		$config['base_url'] = base_url().'callcenter/calls/';
		$config['uri_segment'] = 3;
		//$config['num_links'] = $this->mdl_mcb_data->get('per_page');
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$pg['start'] = $page;
		$pg['limit'] = $config['per_page'];
		$calls = $this->mdl_callcenter->getCalls($pg);
		$config['total_rows'] = $calls['total'];
		$this->pagination->initialize($config);
		$navigation = $this->pagination->create_links();

		$cstatuslist=$this->mdl_mcb_data->getStatusOptions('callstatus');
		array_unshift($cstatuslist,$this->mdl_html->option( '', 'Select Status'));
		$call_status=$this->mdl_html->genericlist( $cstatuslist, 'cstatuslist' ,array(),'value','text',$this->input->get('cs'));

		$this->load->model('engineers/mdl_engineers');
		$engineerOptions = $this->mdl_engineers->getEngineerOptions();
		array_unshift($engineerOptions, $this->mdl_html->option( '0', '< Not Assigned >'));
		array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
		$engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer',array('class'=>''),'value','text',$this->input->get('eg'));

		$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($scentersOptions,$this->mdl_html->option( '0', '< Not Assigned >'));
		array_unshift($scentersOptions, $this->mdl_html->option( '', 'Select Store'));
		$scenters=$this->mdl_html->genericlist( $scentersOptions, "scenter",array('class'=>'validate[required] text-input'),'value','text',$this->input->get('sc'));

	
		$data = array(
					'calls'=>$calls['list'],
					'navigation'=>$navigation,
					'page'=>$page,
					'call_status'=>$call_status,
					'engineer_select'=>$engineer_select,
					'cstatuslist'=>$call_status,
					'config'=>$config,
					'scenters'=>$scenters
		);
		$this->load->view('tab_calls',$data);
	}
	function create_excel()
	{
		ini_set("memory_limit","256M");
		$this->load->model(array('productmodel/mdl_productmodel','brands/mdl_brands','cities/mdl_cities','engineers/mdl_engineers','servicecenters/mdl_servicecenters','products/mdl_products','mcb_data/mdl_mcb_data','customers/mdl_customers','faultsettings/mdl_defect','faultsettings/mdl_repair','faultsettings/mdl_Symptom','productmodel/mdl_product_serial_number'));
		$this->load->model('users/mdl_users');
		$this->load->plugin('to_excel');
		$this->load->helper('calls');
		$pg['start'] = 0;
		$list = $this->mdl_callcenter->getCalls($pg);
		$result = $list['result'];
		$data = '';
		if(count($result->result())==0){
			redirect('callcenter/calls');
		}
		$call_id =array();
		foreach($result->result() as $row){
			$serials = array();
			$call_id[] = $row->call_id;
			if($row->call_id){
				$this->load->model('productmodel/mdl_product_serial_number');
				$serial_numbers = $this->mdl_product_serial_number->getProductSerialNumbersByCall($row->call_id);
				if(count($serial_numbers)>0){
					foreach($serial_numbers as $numbers){
						$serials[] = $numbers->call_serial_no;
					}
				}
			}
			unset($row->call_id);
			$call_status = $row->call_status ;
			$row->call_status = $this->mdl_mcb_data->getStatusDetails($row->call_status,'callstatus');
			$row->call_at = $this->mdl_mcb_data->getStatusDetails($row->call_at,'call_at');
			$row->call_from = $this->mdl_mcb_data->getStatusDetails($row->call_from,'call_from');
			$row->call_service_type = $this->mdl_mcb_data->getStatusDetails($row->call_service_type,'service_type');
			
			if($row->call_status<3) {
				$row->call_aging = CalculateAgingDurationInDays($row->call_dt,$row->call_tm);}
				else{
					$row->call_aging='';
				}
				$row->call_dt = $row->call_dt." ".$row->call_tm;
				$row->pending_dt = strtotime($row->pending_dt)?$row->pending_dt." ".$row->pending_tm:'';
				$row->closure_dt = strtotime($row->closure_dt)?$row->closure_dt." ".$row->closure_tm:'';
				if($call_status=='4'){
					$row->closure_dt =$row->call_last_mod_ts ;
				}
				$row->call_created_by = ($row->call_created_by)?$this->mdl_users->getUserNameByUserId($row->call_created_by):'';
				$row->call_last_mod_by = ($row->call_last_mod_by)?$this->mdl_users->getUserNameByUserId($row->call_last_mod_by):'';
				$row->call_type = $this->mdl_mcb_data->getStatusDetails($row->call_type,'calltype');
				$row->call_old_serial_no = '';
				if(count($serials)>0){
					$row->call_old_serial_no = implode(",<br/>",array_unique($serials));
				}
				 if ($row->reopened == 1){
					 $row->reopened = 'yes';
					 }
					 else{ $row->reopened = '';}
			 if ($row->happy_status == 1){
					 $row->happy_status = 'yes';
					 }
					 else{ $row->happy_status = '';}
					 
				unset($row->call_tm);
				unset($row->pending_tm);
				unset($row->closure_tm);
				unset($row->call_last_mod_ts);
				unset($serials);
				$data[] = $row;
		}

		$fields = array('Call ID','Reg Date/Time','First Name','Last Name','City','Address','Office Phone','Home Phone','Mobile Number','Brand','Product','Model Number','Engineer','Aging Date/Time','Store',
						'Defect','Symptom','Repair',
						'Complain Remark','Engineer Remark','Details of work done','Pending Reason','Status','Pending Date/Time','Closure Date/Time','Registered By','Call Last Modified By','Serial Number','Old Serial Number','Purchase Date','Call Type','Dealer Name','Call At','Call From','Service Type','Reopened','Happy Call');
		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		$name = 'calls_'.date("Y_m_d_H_i_s").'.xls';
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
	function callregistration(){
		$this->redir->set_last_index();
		$this->load->helper('url');
		$this->load->model(array('products/mdl_products',
								 'productmodel/mdl_productmodel',
								 'brands/mdl_brands',
								 'zones/mdl_zones',
								 'zones/mdl_districts',
								 'cities/mdl_cities',
								 'customers/mdl_customers',
								 'mcb_data/mdl_mcb_data',
								 'utilities/mdl_html',
								 'company/mdl_company'
								 )
						   );

	   $call_id = $this->uri->segment(3);
	   $this->session->set_userdata('call_session_uid',$call_id);
	   $call_details = $this->mdl_callcenter->getCallDetails($call_id);
		$this->session->set_userdata('call_session_id',$call_id);
	   $statuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
	   $warranty_status=$this->mdl_html->genericlist( $statuslist, 'warranty_status' );

	   //select box for brand
	   $brandOptions = $this->mdl_brands->getBrandOptions();
	   array_unshift($brandOptions, $this->mdl_html->option( '', 'Select Brand'));
	   $brand_select = $this->mdl_html->genericlist($brandOptions,'brand_select',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one'),'value','text',$call_details->brand_id);
	   //brand select box ends here

	   //select box for products
	   $productOptions = $this->mdl_products->getProductOptions($call_details->brand_id);
	   array_unshift($productOptions, $this->mdl_html->option( '', 'Select Product'));
	   $product_select = $this->mdl_html->genericlist($productOptions,'product_select',array('class'=>'validate[required] select-one','onchange'=>'getModels(this.value);'),'value','text',$call_details->product_id);
	   //product select box ends here

	   //select box for models
	   $modelOptions = $this->mdl_productmodel->getModelOptionsByProduct($call_details->product_id);
	   array_unshift($modelOptions, $this->mdl_html->option( '', 'Select Model'));
	   $model_select = $this->mdl_html->genericlist($modelOptions,'model_id',array('class'=>'validate[required] select-one','onchange'=>'getServiceCenterByCity(this.value)'),'value','text',$call_details->model_id);

	   //select box for models ends here

	   //select box for zones
	   $zoneOptions = $this->mdl_zones->getZoneOptions();
	   array_unshift($zoneOptions, $this->mdl_html->option( '', 'Select Zone'));
	   $zone_select  =  $this->mdl_html->genericlist($zoneOptions,'zone_select',array('class'=>'validate[required] select-one','onchange'=>'getdistrictbyzone(this.value)'),'value','text',$call_details->zone_id);
	   //zone select box ends here

	   //select box for district
	   $districtOptions = $this->mdl_districts->getDistrictOptions($call_details->zone_id);
	   array_unshift($districtOptions, $this->mdl_html->option( '', 'Select District'));
	   $district_select  =  $this->mdl_html->genericlist($districtOptions,'district_select',array('class'=>'validate[required] select-one','onchange'=>'getcitiesbydistrict(this.value)'),'value','text',$call_details->district_id);
	   //district selectbox ends here

	   $cityOptions = $this->mdl_cities->getCityOptions($call_details->district_id);
	   array_unshift($cityOptions, $this->mdl_html->option( '', 'Select City'));
	   $city_select  =  $this->mdl_html->genericlist($cityOptions,'city_select',array('class'=>'validate[required] select-one','onchange'=>'getServiceCenterByCity(this.value)'),'value','text',$call_details->city_id);


	   $calltypeOptions = $this->mdl_mcb_data->getStatusOptions('calltype');
	   array_unshift($calltypeOptions, $this->mdl_html->option( '', 'Select Call Type'));
	   $calltype_select  =  $this->mdl_html->genericlist($calltypeOptions,'calltype_select',array('class'=>'validate[required] select-one'),'value','text',$call_details->call_type);
	   /*
	    **
	    */
		//for call at
		$callatOptions = $this->mdl_mcb_data->getStatusOptions('call_at');
		array_unshift($callatOptions, $this->mdl_html->option( '', 'Select Call At'));
		$callat_select  =  $this->mdl_html->genericlist($callatOptions,'call_at',array('class'=>'validate[required] select-one'),'value','text',$call_details->call_at);



		//for call from
		$callfromOptions = $this->mdl_mcb_data->getStatusOptions('call_from');
		array_unshift($callfromOptions, $this->mdl_html->option( '', 'Select Call From'));
		$callfrom_select  =  $this->mdl_html->genericlist($callfromOptions,'call_from',array('class'=>'validate[required] select-one'),'value','text',$call_details->call_from);
		
		//for call service type name
		$callservicetypeOptions = $this->mdl_mcb_data->getStatusOptions('service_type');
		array_unshift($callservicetypeOptions, $this->mdl_html->option( '', 'Select Service type'));
		$callservicetype_select  =  $this->mdl_html->genericlist($callservicetypeOptions,'call_service_type',array('class'=>'validate[required] select-one'),'value','text',$call_details->call_service_type);
	   
	   $this->load->model('engineers/mdl_engineers');
	   $engineerOptions = $this->mdl_engineers->getEngineerNamePhoneBySc($call_details->sc_id);
	   array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
	   $engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer_select',array('class'=>'validate[required] select-one'),'value','text',$call_details->engineer_id);

	   $this->load->model('servicecenters/mdl_servicecenters');
	   $serviceCentersOptions = $this->mdl_servicecenters->getServiceCentersOptions();
	   array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Service Centre'));
	   $servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'servicecenter_select',array('onchange'=>'getEngineersBySc(this.value)','class'=>'validate[required] select-one'),'value','text',$call_details->sc_id);

	   $cstatuslist=$this->mdl_mcb_data->getStatusOptions('callstatus');
	   unset($cstatuslist[2]);
	   $call_status=$this->mdl_html->genericlist( $cstatuslist, 'cstatuslist' ,array('onchange'=>'getreasonlist(this.value,'."'".$call_details->call_status."'".');'),'value','text',$call_details->call_status);

	   //for reason for pending
	   $this->load->model('settings/mdl_reasons');
	   $pendingOptions = $this->mdl_reasons->getPendingOptions();
	   array_unshift($pendingOptions, $this->mdl_html->option( '', 'Select Reasong For Pending'));
	   $call_reason_pending_select  =  $this->mdl_html->genericlist($pendingOptions,'call_reason_pending',array(),'value','text',$call_details->call_reason_pending);
	   /**
	    **get existing serial numbers
	    */
	   $this->load->model('productmodel/mdl_product_serial_number');
	   $total_serial_numbers = count($this->mdl_product_serial_number->getProductSerialNumbersByCall($call_id));
	   
	   /*
	    ** get dropdwon of symptom code,defect code and repair code
	    */
	   $symptom_select = '';
	   $defect_select = '';
	   $repair_select = '';
	   if($call_details->call_id>0){
	   	$this->load->model(array('faultsettings/mdl_symptom','faultsettings/mdl_defect','faultsettings/mdl_repair','utilities/mdl_html'));
	   	$sysmptomOptions = $this->mdl_symptom->getSymptomOptionsByProduct($call_details->product_id);
	   	array_unshift($sysmptomOptions, $this->mdl_html->option( '', 'Select Symptom Code'));
	   	$symptom_select  =  $this->mdl_html->genericlist($sysmptomOptions,'symptom_code',array('onchange'=>"getDefectCodeBySymptom(this.value,'');"),'value','text',$call_details->symptom_id);
	   		
	   	$defectOptions = array();
	   	array_unshift($defectOptions, $this->mdl_html->option( '', 'Select Defect Code'));
	   	$defect_select  =  $this->mdl_html->genericlist($defectOptions,'defect_code',array('onchange'=>"getRepairCodeBydefect(this.value,'');"),'value','text','');
	   		
	   	$repairOptions = array();
	   	array_unshift($repairOptions, $this->mdl_html->option( '', 'Select Repair Code'));
	   	$repair_select  =  $this->mdl_html->genericlist($repairOptions,'repair_code',array(),'value','text','');
	   }
		//list of parts used
		$company = $this->mdl_company->getCompanyOptions();
		array_unshift($company, $this->mdl_html->option( '', 'select_company'));
	   	$company  =  $this->mdl_html->genericlist($company,'company',array(),'text','text','');
		
		$used_parts = new stdClass;
		$order = new stdClass;
		$order_parts = new stdClass;
		$defected_parts= new stdClass;
		$requested_parts = new stdClass;
		if($call_details->call_id>0){
			$this->load->model(array('orders/mdl_orders','orders/mdl_order_parts','parts/mdl_parts','parts/mdl_parts_used','company/mdl_company','parts/mdl_parts_defected','orders/mdl_calls_orders'));
			$used_parts = $this->mdl_parts_used->getPartUsedByCall($call_details->call_id);
			$order = $this->mdl_orders->getOrderDetailsByCall($call_details->call_id);
			$order_parts = $this->mdl_order_parts->getcallpart($call_details->call_id);
			$defected_parts = $this->mdl_parts_defected->getDefectedData($call_details->call_id);
			$requested_parts = $this->mdl_calls_orders->getrequestedparts($call_details->call_id);
			
		}
	   $data = array(
					  'statuslist'=>$warranty_status,
					  'brand_select'=>$brand_select,
					  'product_select'=>$product_select,
					  'model_select'=>$model_select,
					  'district_select'=>$district_select,
					  'city_select'=>$city_select,
					  'zone_select'=>$zone_select,
					  'calltype_select'=>$calltype_select,
					  'engineer_select'=>$engineer_select,
					  'servicecenter_select'=>$servicecenter_select,
					  'call_details'=>$call_details,
					  'call_status'=>$call_status,
					  'call_reason_pending_select'=>$call_reason_pending_select,
					  'total_serial_numbers'=>$total_serial_numbers,
					  'symptom_select'=>$symptom_select,
					  'defect_select'=>$defect_select,
					  'repair_select'=>$repair_select,
					  'callat_select'=>$callat_select,
					  'callfrom_select'=>$callfrom_select,
					  'callservicetype_select'=>$callservicetype_select,
	   				  'used_parts'=>$used_parts,
	   				  'order'=>$order,
	   				  'order_parts'=>$order_parts,
					  'company'=>$company,
					  'defected_parts'=>$defected_parts,
					  'requested_parts'=>$requested_parts
					);
	   $this->load->view('tab_callsetup',$data);
	}
	//call re-registration
	function callreregistration(){
		$this->redir->set_last_index();
		$this->load->helper('url');
		$this->load->model(array('products/mdl_products',
								 'productmodel/mdl_productmodel',
								 'brands/mdl_brands',
								 'zones/mdl_zones',
								 'zones/mdl_districts',
								 'cities/mdl_cities',
								 'customers/mdl_customers',
								 'mcb_data/mdl_mcb_data',
								 'utilities/mdl_html'
								 )
						   );

		$call_id = $this->uri->segment(3);
		$call_details = $this->mdl_callcenter->getCallDetails($call_id);
		
		$statuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$warranty_status=$this->mdl_html->genericlist( $statuslist, 'warranty_status' );
		
		//select box for brand
		$brandOptions = $this->mdl_brands->getBrandOptions();
		array_unshift($brandOptions, $this->mdl_html->option( '', 'Select Brand'));
		$brand_select = $this->mdl_html->genericlist($brandOptions,'brand_select',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one'),'value','text',$call_details->brand_id);
		//brand select box ends here
		
		//select box for products
		$productOptions = $this->mdl_products->getProductOptions($call_details->brand_id);
		array_unshift($productOptions, $this->mdl_html->option( '', 'Select Product'));
		$product_select = $this->mdl_html->genericlist($productOptions,'product_select',array('class'=>'validate[required] select-one','onchange'=>'getModels(this.value);'),'value','text',$call_details->product_id);
		//product select box ends here
		
		//select box for models
		$modelOptions = $this->mdl_productmodel->getModelOptionsByProduct($call_details->product_id);
		array_unshift($modelOptions, $this->mdl_html->option( '', 'Select Model'));
		$model_select = $this->mdl_html->genericlist($modelOptions,'model_id',array('class'=>'validate[required] select-one','onchange'=>'getServiceCenterByCity(this.value)'),'value','text',$call_details->model_id);
		
		//select box for models ends here
		
		//select box for zones
		$zoneOptions = $this->mdl_zones->getZoneOptions();
		array_unshift($zoneOptions, $this->mdl_html->option( '', 'Select Zone'));
		$zone_select  =  $this->mdl_html->genericlist($zoneOptions,'zone_select',array('class'=>'validate[required] select-one','onchange'=>'getdistrictbyzone(this.value)'),'value','text',$call_details->zone_id);
		//zone select box ends here
		
		//select box for district
		$districtOptions = $this->mdl_districts->getDistrictOptions($call_details->zone_id);
		array_unshift($districtOptions, $this->mdl_html->option( '', 'Select District'));
		$district_select  =  $this->mdl_html->genericlist($districtOptions,'district_select',array('class'=>'validate[required] select-one','onchange'=>'getcitiesbydistrict(this.value)'),'value','text',$call_details->district_id);
		//district selectbox ends here
		
		$cityOptions = $this->mdl_cities->getCityOptions($call_details->district_id);
		array_unshift($cityOptions, $this->mdl_html->option( '', 'Select City'));
		$city_select  =  $this->mdl_html->genericlist($cityOptions,'city_select',array('class'=>'validate[required] select-one','onchange'=>'getServiceCenterByCity(this.value)'),'value','text',$call_details->city_id);
		
		
		$calltypeOptions = $this->mdl_mcb_data->getStatusOptions('calltype');
		$calltype_select  =  $this->mdl_html->genericlist($calltypeOptions,'calltype_select',array('class'=>'validate[required] select-one'),'value','text',$call_details->call_type);
		/*
		**
		*/
		//for call at
		$callatOptions = $this->mdl_mcb_data->getStatusOptions('call_at');
		array_unshift($callatOptions, $this->mdl_html->option( '0', 'Select Call At'));
		$callat_select  =  $this->mdl_html->genericlist($callatOptions,'call_at',array(),'value','text',$call_details->call_at);
		
		//for call from
		$callfromOptions = $this->mdl_mcb_data->getStatusOptions('call_from');
		array_unshift($callfromOptions, $this->mdl_html->option( '0', 'Select Call From'));
		$callfrom_select  =  $this->mdl_html->genericlist($callfromOptions,'call_from',array(),'value','text',$call_details->call_from);
		
		//for call service type name
		$callservicetypeOptions = $this->mdl_mcb_data->getStatusOptions('service_type');
		array_unshift($callservicetypeOptions, $this->mdl_html->option( '0', 'Select Service type'));
		$callservicetype_select  =  $this->mdl_html->genericlist($callservicetypeOptions,'call_service_type',array(),'value','text',$call_details->call_service_type);
		
		$this->load->model('engineers/mdl_engineers');
		$engineerOptions = $this->mdl_engineers->getEngineerNamePhoneBySc($call_details->sc_id);
		array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
		$engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer_select',array('class'=>'',),'value','text',$call_details->engineer_id);
		
		$call_details->sc_id=0;
		$this->load->model('servicecenters/mdl_servicecenters');
		$serviceCentersOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Service Centre'));
		$servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'servicecenter_select',array('onchange'=>'getEngineersBySc(this.value)'),'value','text',$call_details->sc_id);
		
		$cstatuslist=$this->mdl_mcb_data->getStatusOptions('callstatus');
		unset($cstatuslist[2]);
		$call_status=$this->mdl_html->genericlist( $cstatuslist, 'cstatuslist' ,array('onchange'=>'getreasonlist(this.value,'."'".$call_details->call_status."'".');'),'value','text',$call_details->call_status);
		
		$call_details->call_id=0;
		$call_details->call_dt=date('Y-m-d');
		$details->call_purchase_dt = date("Y-m-d");
		$call_details->call_cust_id=0;
		$call_details->call_uid=0;
		$call_details->call_status=0;
		$call_details->call_cust_pref_dt = date('Y-m-d');
		$details->call_cust_pref_tm=date("H:i",strtotime(time()));
		$data = array(
					  'statuslist'=>$warranty_status,
					  'brand_select'=>$brand_select,
					  'product_select'=>$product_select,
					  'model_select'=>$model_select,
					  'district_select'=>$district_select,
					  'city_select'=>$city_select,
					  'zone_select'=>$zone_select,
					  'calltype_select'=>$calltype_select,
					  'engineer_select'=>$engineer_select,
					  'servicecenter_select'=>$servicecenter_select,
					  'call_details'=>$call_details,
					  'call_status'=>$call_status,
					  'callat_select'=>$callat_select,
					  'callfrom_select'=>$callfrom_select,
					  'callservicetype_select'=>$callservicetype_select
					);
	  
	   $this->load->view('tab_callresetup',$data);
	}
	function getproductsbybrand()
	{
		$this->load->model(array('products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$brandOptions = $this->mdl_products->getProductsByBrand($this->input->post('brand_id'));
		array_unshift($brandOptions,$this->mdl_html->option( '', 'Select Product'));
		$brandlist = $this->mdl_html->genericlist($brandOptions,'product_select',array('class'=>'validate[required] text-input','onchange'=>'getModels(this.value);'),'value','text',$active);
		echo $brandlist;
	}
	function getmodels(){
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$product_id = (int)$this->input->post('product_id');
		$modelOptions = $this->mdl_productmodel->getModelOptionsByProduct($product_id);
		array_unshift($modelOptions, $this->mdl_html->option( '', 'Select Model'));
		$model_select = $this->mdl_html->genericlist($modelOptions,'model_id',array('class'=>'validate[required] select-one','onchange'=>'getServiceCenterByCity(this.value)'),'value','text','');
		echo $model_select;
		die();
	}
	function getmodelsSearch(){
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = $this->input->post('active');
		$product_id = (int)$this->input->post('product_id');
		$modelOptions = $this->mdl_productmodel->getModelOptionsByProduct($product_id);
		array_unshift($modelOptions, $this->mdl_html->option( '', 'Select Model'));
		$model_select = $this->mdl_html->genericlist($modelOptions,'model_id',array('class'=>'validate[required] select-one','onchange'=>'getServiceCenterByCity(this.value)'),'value','text',$active);
		echo $model_select;
		die();
	}
	
	function getmodellist(){
			$this->redir->set_last_index();
			
			$this->load->model(array('products/mdl_products',
								 'productmodel/mdl_productmodel',
								 'brands/mdl_brands',
								'mcb_data/mdl_mcb_data',
								 'utilities/mdl_html',
								 )
						   );
		 $brandOptions = $this->mdl_brands->getBrandOptions();
	   array_unshift($brandOptions, $this->mdl_html->option( '', 'Select Brand'));
	   $brand_select = $this->mdl_html->genericlist($brandOptions,'brand_select_search',array('onchange'=>'getProductBybrandsearch($(this).val());','class'=>'validate[required] select-one'),'value','text');
			
			//print_r();
			
		 $productOptions = $this->mdl_products->getProductOptions();
	   array_unshift($productOptions, $this->mdl_html->option( '', 'Select Product'));
	   $product_select = $this->mdl_html->genericlist($productOptions,'product_select_search',array('class'=>'validate[required] select-one'),'value','text');
	   //product select box ends here

		
		$data = array(
					  'brandOptions'=>$brand_select,
					  'productOptions'=>$product_select
					  );
		
		$this->load->view('callcenter/modelcalllist',$data);
		
		}
		
	function modellisting(){
			$this->redir->set_last_index();
			$this->load->model(array('products/mdl_products','productmodel/mdl_productmodel','mcb_data/mdl_mcb_data','brands/mdl_brands'));
			
		 $ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$model = $this->mdl_productmodel->getmodelproductbrand($page);
		$lists = $model['list'];
		$config['total_rows'] = $model['total'];
		
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		//$model = $this->mdl_productmodel->getmodelproductbrand();
		
			$data = array(
						  "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config,
						  'models'=>$model['list']
						  );
			
			$this->load->view('callcenter/modellist',$data);
			}
	
	
	
	
	
	function getsearchpop()
	{
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$product_id = (int)$this->input->get('product_id');
		$list = $this->mdl_productmodel->getModelsByProduct($product_id);
		$data = array('list'=>$list);
		$this->load->view('searchpop',$data);
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
	function searchcall()
	{
		$this->load->helper('url');
		$searchtxt = $this->input->post('searchtxt');
		$cid = $this->input->post('call_uid');
		$cn = $this->input->post('cust_name');
		$pn = $this->input->post('product_name');
		$sn = $this->input->post('serial_number');
		$ph = $this->input->post('phone');
		$eg = $this->input->post('engineer');
		$sc = $this->input->post('scenter');

		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		//$reopened = $this->input->post('reopened');
		
		$test= $this->input->post();
		$reopened = ($test['reopened']);
		$verified = ($test['verified']);
	
		$array = array();
		if($cid){
			$array[] = 'cid='.$cid;
		}
		if($reopened == 1){
			$array[] = 'reopened='.$reopened;
		}
		if($verified == 1){
			$array[] = 'verified='.$verified;
		}
		if($cn){
			$array[] = 'cn='.$cn;
		}
		if($pn){
			$array[] = 'pn='.$pn;
		}
		if($sn){
			$array[] = 'sn='.$sn;
		}
		if($ph){
			$array[] = 'ph='.$ph;
		}
		if($eg!=''){
			$array[] = 'eg='.$eg;
		}
		if($from_date){
			$array[] = 'from='.$from_date;
		}
		if($to_date){
			$array[] = 'to='.$to_date;
		}
		if($searchtxt){
			$array[] = 'search='.$searchtxt;
		}
		$callstatus = $this->input->post('cstatuslist');
		if(is_array($callstatus)){
			$array[] = 'cs='.implode('_',$callstatus);
		}else if($callstatus!=''){
			$array[] = 'cs='.$callstatus;
		}
		if($sc!=''){
			$array[] = 'sc='.$sc;
		}
		$url		= ( count( $array ) ? '?' . ((count( $array )==1)? implode( '', $array ) :implode( '&', $array )) : '' );
		redirect('callcenter/calls'.$url);
		die();
	}
	function jobcard()
	{
		$this->redir->set_last_index();
		$this->redir->set_last_index();
		$this->load->helper('url');
		$this->load->model(array('products/mdl_products',
								 'productmodel/mdl_productmodel',
								 'brands/mdl_brands',
								 'zones/mdl_zones',
								 'zones/mdl_districts',
								 'cities/mdl_cities',
								 'customers/mdl_customers',
								 'engineers/mdl_engineers',
								 'servicecenters/mdl_servicecenters',
								 'mcb_data/mdl_mcb_data',
								 'utilities/mdl_html'
								 )
						   );

						   $call_id = $this->input->get('call_id');
						   $preview_details = $this->mdl_callcenter->getCallPreviewDetails($call_id);

						   $data = array(
					  'preview_details'=>$preview_details
						   );
						   $this->load->view('jobcard',$data);
	}
	function changecallstatus(){
		$call_id = $this->input->post('call_id');
		$call_status = $this->input->post('call_status');
		$call_print_jobcard = $this->input->post('call_print_jobcard');
		$pending_dt = date("Y-m-d");
		$pending_tm = date("H:i");
		/**
		 'call_status'=>$call_print_jobcard for changing statu but not included in this
		 since printjob card can be done at any stage of call.
		 */
		$params = array(
						'call_print_jobcard'=>$call_print_jobcard,
						'pending_dt'=>$pending_dt,
						'pending_tm'=>$pending_tm
		);
		$this->mdl_callcenter->save($params,$call_id);
		//$this->session->set_flashdata('success_save','Job card Printed successfully');
		echo true;
	}
	function getcentersbycity()
	{
		$this->load->model(array('servicecenters/mdl_servicecenters', 'cities/mdl_cities' , 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$serviceCenterOptions = $this->mdl_servicecenters->getcentersbycity($this->input->post('city_id'));
		if(count($serviceCenterOptions)>0){
			$sc_id = $serviceCenterOptions[0]->value;
		}else{
			$sc_id='';
		}
		echo $sc_id;
		die();
		array_unshift($serviceCenterOptions,$this->mdl_html->option( '', 'Select Store'));
		$sclist = $this->mdl_html->genericlist($serviceCenterOptions,'servicecenter_select',array(),'value','text',$active);
		echo $sclist;
	}
	function getcitiesbydistrict()
	{
		$this->redir->set_last_index();
		$this->load->model(array('cities/mdl_cities','utilities/mdl_html'));
		$district_id = $this->input->post('district_id');
		$active = $this->input->post('active');
		$cityOptions = $this->mdl_cities->getCityOptions($district_id);
		array_unshift($cityOptions, $this->mdl_html->option( '', 'Select City'));
		$city_select  =  $this->mdl_html->genericlist($cityOptions,'city_select',array('class'=>'validate[required] select-one','onchange'=>'getServiceCenterByCity(this.value)'),'value','text',$active);
		echo $city_select;
	}
	function getengineersbysc()
	{
		$this->redir->set_last_index();
		$this->load->model(array('engineers/mdl_engineers','utilities/mdl_html'));
		$engineerOptions = $this->mdl_engineers->getEngineerNamePhoneBySc($this->input->post('sc_id'));
		array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
		$engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer_select',array('class'=>''),'value','text','');
		echo $engineer_select;
	}
	function getdefectcodebysymptom()
	{
		$this->redir->set_last_index();
		$this->load->model(array('faultsettings/mdl_defect','utilities/mdl_html'));
		$symptom_id = $this->input->post('symptom_id');
		$active = $this->input->post('active');

		$defectOptions = $this->mdl_defect->getDefectOptionsBySymptom($symptom_id);
		array_unshift($defectOptions, $this->mdl_html->option( '', 'Select Defect Code'));
		$defect_select  =  $this->mdl_html->genericlist($defectOptions,'defect_code',array('class'=>'','onchange'=>"getRepairCodeBydefect(this.value,'');"),'value','text',$active);
		echo $defect_select;
		die();
	}
	function getsymptomsbyproduct()
	{
		$active = $this->input->post('active');
		$this->load->model(array('faultsettings/mdl_symptom','faultsettings/mdl_defect','faultsettings/mdl_repair','utilities/mdl_html'));
		$sysmptomOptions = $this->mdl_symptom->getSymptomOptionsByProduct($this->input->post('product_id'));
		array_unshift($sysmptomOptions, $this->mdl_html->option( '', 'Select Symptom Code'));
		$symptom_select  =  $this->mdl_html->genericlist($sysmptomOptions,'symptom_code',array('onchange'=>"getDefectCodeBySymptom(this.value,'');"),'value','text',$active);
		echo $symptom_select;
		die();
	}
	function getrepaircodebydefect()
	{
		$this->redir->set_last_index();
		$this->load->model(array('faultsettings/mdl_repair','faultsettings/mdl_defect','utilities/mdl_html'));
		$defect_id = $this->input->post('defect_id');
		$active = $this->input->post('active');

		$repairOptions = $this->mdl_repair->getRepairOptionsByDefect($defect_id);
		array_unshift($repairOptions, $this->mdl_html->option( '', 'Select Repair Code'));
		$defect_select  =  $this->mdl_html->genericlist($repairOptions,'repair_code',array('class'=>''),'value','text',$active);
		echo $defect_select;
		die();
	}
	function getreasonlist()
	{
		$call_status = $this->input->post('call_status');
		$active = $this->input->post('active');
		/*
		 ** get drowdown of  reason for pending if call status =1
		 */
		if($call_status==1){
			$this->load->model(array('settings/mdl_reasons','utilities/mdl_html'));
			$pendingOptions = $this->mdl_reasons->getPendingOptions();
			array_unshift($pendingOptions, $this->mdl_html->option( '', 'Select Reasong For Pending'));
			$call_reason_pending_select  =  $this->mdl_html->genericlist($pendingOptions,'call_reason_pending',array('onchange' =>'showpendingdiv(this.value);','class'=>'validate[required] select-one'),'value','text',$active);
			echo $call_reason_pending_select;
			die();
		}
		/*
		 ** get dropdown of reason for partpending
		 */
		if($call_status==2){
			$this->load->model(array('settings/mdl_partpending','utilities/mdl_html'));
			$partpendingOptions = $this->mdl_partpending->getPartpendingOptions();
			array_unshift($partpendingOptions, $this->mdl_html->option( '', 'Select Reasong For Part Pending'));
			$call_reason_partpending_select  =  $this->mdl_html->genericlist($partpendingOptions,'call_reason_pending',array('class'=>'validate[required] select-one'),'value','text',$active);
			echo $call_reason_partpending_select;
			die();
		}
		/*
		 ** get dropdown of reason for closure
		 */
		if($call_status==3){
			$this->load->model(array('settings/mdl_closure','utilities/mdl_html'));
			$closureOptions = $this->mdl_closure->getClosureOptions();
			array_unshift($closureOptions, $this->mdl_html->option( '', 'Select Reasong For Closure'));
			$call_reason_closure_select  =  $this->mdl_html->genericlist($closureOptions,'call_reason_pending',array('class'=>'validate[required] select-one'),'value','text',$active);
			echo $call_reason_closure_select;
			die();
		}
		/*
		 ** get dropdown of reason for cancellation
		 */
		if($call_status==4){
			$this->load->model(array('settings/mdl_cancellation','utilities/mdl_html'));
			$cancellationOptions = $this->mdl_cancellation->getCancellationOptions();
			array_unshift($cancellationOptions, $this->mdl_html->option( '', 'Select Reasong For Cancellation'));
			$call_reason_cancellation_select  =  $this->mdl_html->genericlist($cancellationOptions,'call_reason_pending',array('class'=>'validate[required] select-one'),'value','text',$active);
			echo $call_reason_cancellation_select;
		}
		die();
	}
	function _post_handler(){
		if($this->input->post('btn_save')){
			$msg = '';
			$call_id  = $this->input->post('hdncallid');
			$call_cust_id  = $this->input->post('hdncallcust_id');
				
			//variables for customer info
			$cust_first_name = $this->input->post('cust_first_name');
			// Added later
			$filed_two = $this->input->post('filed_two');
			
			$cust_last_name = $this->input->post('cust_last_name');
			$cust_address = $this->input->post('cust_address');
			$cust_landmark = $this->input->post('cust_landmark');
			$zone_id = $this->input->post('zone_select');
			$district_id = $this->input->post('district_select');
			$city_id = $this->input->post('city_select');
			$register_by = $this->input->post('register_by');
			$cust_phone_home = $this->input->post('cust_phone_home');
			$cust_phone_office = $this->input->post('cust_phone_office');
			$cust_phone_mobile = $this->input->post('cust_phone_mobile');
			$data_customer = array(
								  'cust_first_name'=>$cust_first_name,
								  // Added later
								 // 'filed_two' => $filed_two,
								  'cust_last_name'=>$cust_last_name,
								  'cust_address'=>$cust_address,
								  'cust_landmark'=>$cust_landmark,
								  'cust_phone_home'=>$cust_phone_home,
								  'cust_phone_office'=>$cust_phone_office,
								  'cust_phone_mobile'=>$cust_phone_mobile,
								  'zone_id'=>$zone_id,
								  'district_id'=>$district_id,
								  'city_id'=>$city_id
			);
			$this->load->model(array('customers/mdl_customers','productmodel/mdl_product_serial_number','company/mdl_company'));
			$check_serial_number = $this->mdl_callcenter->checkopencallbyserial_number($call_id,$this->input->post('model_id'), $this->input->post('product_serial_number'));
			$this->session->set_flashdata('call_same_serialnumber','');
			if($check_serial_number->cnt>0 && $this->input->post('cstatuslist')<4){
				$this->session->set_flashdata('save_status',false);
				$this->session->set_flashdata('call_save','');
				$this->session->set_flashdata('call_id',$check_serial_number->call_id);
				$this->session->set_flashdata('call_uid',$check_serial_number->call_uid);
				$this->session->set_flashdata('call_same_serialnumber',$check_serial_number->call_serial_no);
				redirect('callcenter/callregistration/'.$call_id);
				die();
			}
			if((int)$call_cust_id==0){
				$this->db->trans_start();
				$data_customer['cust_created_by']=$this->session->userdata('user_id');
				$data_customer['cust_created_ts']=date("Y-m-d H:i:s");
				$this->mdl_customers->save($data_customer);
				$call_cust_id = $this->db->insert_id();
			}else{
				$data_customer['cust_last_modified_by']=$this->session->userdata('user_id');
				$data_customer['cust_last_modified_ts']=date("Y-m-d H:i:s");
				$this->mdl_customers->save($data_customer,$call_cust_id);
			}
				
			//variables for product info
			$brand_id = $this->input->post('brand_select');
			$product_id = $this->input->post('product_select');
			$product_model_number = $this->input->post('product_model_number');
			$product_purchase_date = $this->input->post('product_purchase_date');
			$product_dealer_name = $this->input->post('product_dealer_name');
			$product_warranty_status = $this->input->post('warranty_status');
			$call_from = $this->input->post('call_from');
			$call_service_type = $this->input->post('call_service_type');
				
			//variables for call info
			//$call_id = $this->input->post('call_id');
			$call_type = $this->input->post('calltype_select');
			$call_cust_pref_dt = date("Y-m-d",date_to_timestamp($this->input->post('call_cust_pref_dt')));
			$call_cust_pref_tm = $this->input->post('call_cust_pref_tm');
			$engineer_id = $this->input->post('engineer_select');
			$sc_id = ($this->input->post('servicecenter_select')=='')?0:$this->input->post('servicecenter_select');
			$call_nocall_tm = $this->input->post('call_nocall_tm');
			$model_id = (int)$this->input->post('model_id');
			$call_service_desc = str_replace("\r\n",'',$this->input->post('service_desc'));
				
			$call_serial_no = $this->input->post('product_serial_number');
			$call_dealer_name = $this->input->post('product_dealer_name');
			$call_at = $this->input->post('call_at');
				
			//visit details
			if($this->input->post('call_visit_dt')){
				$call_visit_dt = date("Y-m-d",date_to_timestamp($this->input->post('call_visit_dt')));
			}else{
				$call_visit_dt='0000-00-00';
			}//visit details
			if($this->input->post('call_delivery_dt')){
				$call_delivery_dt = date("Y-m-d",date_to_timestamp($this->input->post('call_delivery_dt')));
			}else{
				$call_delivery_dt='0000-00-00';
			}
				
			//deleivery details
				
			$call_status = $this->input->post('cstatuslist');
			$call_visit_tm_in = $this->input->post('call_visit_tm_in');
			$call_visit_tm_out = $this->input->post('call_visit_tm_out');
			$call_engineer_remark = preg_replace("/\n|\r/",'',$this->input->post('call_engineer_remark'));
			$call_reason_pending = $this->input->post('call_reason_pending');
			$call_detail_wrk_done = preg_replace("/\n|\r/",'',$this->input->post('call_detail_wrk_done'));
				
			$data_call = array(
							'call_type'=>$call_type,
							'call_cust_id'=>$call_cust_id,
							'call_cust_pref_dt'=>$call_cust_pref_dt,
							'call_service_desc'=>$call_service_desc,
							'engineer_id'=>(int)$engineer_id,
							'sc_id'=>$sc_id,
							'call_nocall_tm'=>$call_nocall_tm,
							'brand_id'=>$brand_id,
							'model_id'=>$model_id,
							'call_prod_detail_id'=>$product_id,
							'call_service_desc'=>$call_service_desc,
							'call_serial_no'=>$call_serial_no,
							'call_dealer_name'=>$call_dealer_name,
							'call_status'=>$call_status,
							'call_at'=>$call_at,
							'call_from'=>$call_from,
							'call_service_type'=>$call_service_type
			);
			if($call_cust_pref_tm){
				$data_call['call_cust_pref_tm']=$call_cust_pref_tm;
			}
				
			if($product_purchase_date){
				$data_call['call_purchase_dt']=date("Y-m-d",strtotime(str_replace("/","-",$product_purchase_date)));
			}else{
				$data_call['call_purchase_dt']='0000-00-00'; // for null date
			}
				
			if((int)$call_id==0){
				$data_call['call_print_jobcard'] =0;
				$data_call['call_uid'] =$this->mdl_callcenter->generateCallIdByZone($zone_id);
				$data_call['call_dt']=date("Y-m-d");
				$data_call['call_tm']=date("H:i");
				$data_call['call_created_by']=$this->session->userdata('user_id');
				$data_call['call_created_ts']=date("Y-m-d H:i:s");
				
				if($this->mdl_callcenter->save($data_call)){
					$call_id =  $this->db->insert_id();
					$this->session->set_flashdata('save_status',true);
					$this->session->set_flashdata('is_new',true);
					$this->session->set_flashdata('call_save',$this->lang->line('this_item_has_been_registered'));
				}else{
					$this->session->set_flashdata('save_status',false);
					$this->session->set_flashdata('call_save',$this->lang->line('this_item_not_saved'));
				}
				$this->db->trans_complete();
			}else{
				$data_call['call_last_mod_by']=$this->session->userdata('user_id');
				$data_call['call_last_mod_ts']=date("Y-m-d H:i:s");
				$data_call['call_visit_dt']=$call_visit_dt;
				$data_call['call_visit_tm_in']=$call_visit_tm_in;
				$data_call['call_visit_tm_out']=$call_visit_tm_out;
				$data_call['call_engineer_remark']=$call_engineer_remark;
				$data_call['call_reason_pending']=$call_reason_pending;
				$data_call['call_detail_wrk_done']=$call_detail_wrk_done;
				$data_call['repair_id'] = ($this->input->post('repair_code')=='')?'0':$this->input->post('repair_code');
				$data_call['symptom_id'] = ($this->input->post('symptom_code')=='')?'0':$this->input->post('symptom_code');
				$data_call['defect_id'] = ($this->input->post('defect_code')=='')?'0':$this->input->post('defect_code');
				//set parameters for date and time if call status is choosen to pending
				if($this->input->post('cstatuslist')==1){
					$data_call['pending_dt'] = date("Y-m-d");
					$data_call['pending_tm'] = date("H:i");
					$data_call['closure_dt'] = '0000-00-00';
					$data_call['closure_tm'] = '00:00:00';
				}
				/***************parts_used_stock starts*****************************/
				$this->load->model(array('stocks/mdl_stocks','stocks/mdl_parts_stocks','parts/mdl_parts_used','orders/mdl_orders','orders/mdl_order_parts','company/mdl_company','partallocation/mdl_partallocation','partallocation/mdl_partallocation_details'));
				$used_parts_id_arr = $this->input->post('used_parts_id');
				$used_parts_numbers = $this->input->post('part_number');
				$used_parts_quantity = $this->input->post('part_quantity');
				$used_parts_company = $this->input->post('used_company');
				$hdncall_status = $this->input->post('hdncall_status');
				/*echo '<pre>';
				print_r($this->input->post());
				die();*/
				$j = 0;
				if(is_array($used_parts_numbers)){
					foreach($used_parts_numbers as $used_part_number){
						if($used_part_number && $used_parts_quantity >=1){
							$company_id = $this->mdl_company->getcompanyid($used_parts_company[$j]);
							$used_parts = array(
												'part_number'=>$used_part_number,
												'part_quantity'=>$used_parts_quantity[$j],
												'company_id'=>$company_id,
												'call_id'=>$call_id
												);
							$stockdata['part_number'] = $used_part_number;
							$stockdata['stock_quantity_in'] = 0;
							$stockdata['stock_quantity_out'] = $used_parts_quantity[$j];
							$stockdata['sc_id'] = $sc_id;
							$stockdata['company_id'] =$company_id;
							$stockdata['engineer_id'] =$this->input->post('engineer_select');
							
							
							$check_parts_stock = $this->mdl_partallocation->checkEngineerParts($sc_id,$used_part_number,$company_id,$engineer_id);
							if($used_parts_id_arr[$j]==0){
								$stockdata['stock_dt'] = date('Y-m-d');
								$stockdata['stock_tm'] = date('H:i:s');
								$used_parts['parts_used_created_by'] = $this->session->userdata('user_id');
								$used_parts['parts_used_created_ts'] = date('Y-m-d H:i:s');
							//	print_r($used_parts_quantity[$j]);
							//	print_r($check_parts_stock->stock_quantity);
							
				//die();
								if($check_parts_stock->stock_quantity< $used_parts_quantity[$j]){									
									$msg = $this->lang->line('not_enough_stock');
								}else{
									$this->mdl_parts_used->save($used_parts);
									$used_parts_id = $this->db->insert_id();
									$this->mdl_parts_stocks->updatecallused($stockdata);
									$this->mdl_stocks->stockoutUpdatecall($stockdata,'stockout_used',$used_parts_id);
									
									$this->mdl_partallocation_details->usedparts($stockdata,$engineer_id);
									$this->mdl_partallocation->updateUsedPart($stockdata,$engineer_id);
								}
							}
						}
						$j++;
					}
				}
				
				/***************parts_used_stock ends here*****************************/
				
				/***************create orders for pending parts************************/
				$this->load->model('orders/mdl_calls_orders');
				if($call_status<3 && $call_reason_pending='Part Pending'){
					$order_part_number_arr = $this->input->post('order_part_number');
					$order_part_quantity_arr = $this->input->post('order_part_quantity');
					$order_part_id_arr = $this->input->post('order_part_id');
					$order_id_arr = $this->input->post('parts_order_id');
					$calls_orders_id = $this->input->post('calls_orders_id');
					
					if(is_array($order_part_number_arr)){
						$order_data= array();
						$k=0;
						foreach ($order_part_number_arr as $order_part_number){
							//echo $k;
							if (empty($order_id_arr[$k])){
							if($order_part_number){
								$this->load->model(array('orders/mdl_orders','orders/mdl_order_parts','company/mdl_company'));
								$order_data[$k]->engineer_id = $engineer_id;
								$order_data[$k]->sc_id = $sc_id;
								$order_data[$k]->order_part_id = $order_part_id_arr[$k];
								$order_data[$k]->call_id = $call_id;
								$order_data[$k]->part_number = $order_part_number;
								$order_data[$k]->part_quantity = $order_part_quantity_arr[$k];
								
							}
							if($calls_orders_id[$k]){
								
								
								$call_data['call_id']=$order_data[$k]->call_id ;
								$call_data['engineer_id']= $order_data[$k]->engineer_id;
								$call_data['part_number']=$order_data[$k]->part_number;
								$call_data['part_quantity']=$order_data[$k]->part_quantity;
								$call_data['sc_id']= $order_data[$k]->sc_id;
								
								$this->mdl_calls_orders->save($call_data,$calls_orders_id[$k]);
								
								}
								else{
									$call_data['call_id']=$order_data[$k]->call_id ;
								$call_data['engineer_id']= $order_data[$k]->engineer_id;
								$call_data['part_number']=$order_data[$k]->part_number;
								$call_data['part_quantity']=$order_data[$k]->part_quantity;
								$call_data['sc_id']= $order_data[$k]->sc_id;
								
								$this->mdl_calls_orders->save($call_data);
									
									//$this->mdl_calls_orders->savecallorder($order_data);
									}
							}
							$k++;
							}
						
					}
				}
				//die();
				//$this->mdl_calls_orders->savecallorder($order_data);
			
				/*************create orders for pending parts ends here****************/
				/****************Defected parts ***************************************************/
				
				$this->load->model(array('company/mdl_company','parts/mdl_parts_defected'));
				$defected_parts_id_arr = $this->input->post('defected_parts_id');
				$defected_parts_numbers = $this->input->post('defected_part_number');
				$defected_parts_quantity = $this->input->post('defected_part_quantity');
				$defected_company = $this->input->post('defected_company');
				$serial = $this->input->post('serial');
				//echo '<pre>';
			//print_r($defected_company);

				//die();
				$j = 0;
				if(is_array($defected_parts_numbers)){
					
					foreach($defected_parts_numbers as $defected_part_number){
						
						if($defected_part_number){
							
							 if ($defected_parts_id_arr[$j]==0){
								// die();
							$company_id = $this->mdl_company->getcompanyid($defected_company[$j]);
							$defected_parts = array(
												'part_number'=>$defected_part_number,
												'part_quantity'=>$defected_parts_quantity[$j],
												'company_id'=>$company_id,
												'call_id'=>$call_id,
												'parts_defect_created_by'=> $this->session->userdata('user_id'),
												'parts_defect_created_ts'=>date('Y-m-d H:i:s'),
												'part_serial_no'=>$serial[$j]
												);
							
							$this->mdl_parts_defected->save($defected_parts);
							 }
							
						}
						$j++;
					}
				}
				
				
				
				/********************Defected parts ends here***********************************************/
				
				
				
				//set parameters for date and time if call status is choosen to closed
				if($this->input->post('cstatuslist')==3){
					//check if call has any pending order parts
					$check_call_parts = $this->mdl_orders->checkPendingOrderByCall($call_id);
					if($check_call_parts>0){
						$this->session->set_flashdata('save_status',true);
						$this->session->set_flashdata('call_save',$this->lang->line('not_saved_order_parts_are_pending'));
						redirect('callcenter/callregistration/'.$call_id);
					}
					$data_call['closure_dt'] = date("Y-m-d");
					$data_call['closure_tm'] = date("H:i");
					$data_call['call_delivery_dt'] = $call_delivery_dt;
					/*
					 **save list of serial numbers during closure of call
					 ** call_status =3
					 */
					$new_call_serial_no = $this->input->post('product_serial_number_new');
					$row=$this->mdl_callcenter->get_by_id($call_id);
					$old_call_serial_no = $row->call_serial_no;
					if($new_call_serial_no && $old_call_serial_no){
						$data_call['call_serial_no'] = $new_call_serial_no;
						$data_call_serial = array('call_id'=>$call_id,
												  'call_serial_no'=>$row->call_serial_no,
												  'call_serial_created_ts'=>date('Y-m-d H:i:s'),
												  'call_serial_created_by'=>$this->session->userdata('user_id')
						);
						$this->mdl_product_serial_number->save($data_call_serial);
					}
					/*
					 **save call_serial number during closure end here
					 */
				}else{
					$data_call['call_delivery_dt'] = '0000-00-00';
				}
				if($this->mdl_callcenter->save($data_call,$call_id)){
					$this->session->set_flashdata('save_status',true);
					$this->session->set_flashdata('call_save',$this->lang->line('this_item_has_been_saved').$msg);
				}else{
					$this->session->set_flashdata('save_status',false);
					$this->session->set_flashdata('call_save',$this->lang->line('this_item_not_saved'));
				}
			}
			//$this->session->flashdata('success_save','sdf')
			redirect('callcenter/callregistration/'.$call_id);
		}
	}
	
	
	
	/********************************Function For managing Happy Call******************************************/
	
	public function happycall ($callid){
		
		$this->load->models(array('callcenter/mdl_call_happy','callcenter/mdl_call_happy_question','callcenter/mdl_calls_happy_answer','utilities/mdl_html','callcenter/mdl_call_happy_record'));
		
		 $call_details = $this->mdl_callcenter->getCalledAt($callid);
		
		$callatOptions = $this->mdl_mcb_data->getStatusOptions('call_at');
		array_unshift($callatOptions, $this->mdl_html->option( '', 'Select Call At'));
		$callat_select  =  $this->mdl_html->genericlist($callatOptions,'call_at',array('class'=>'validate[required] select-one'),'value','text',$call_details->call_at);
		
		
		
		
		$questions= $this->mdl_call_happy_question->getQuestions();
		$call_uid = $this->mdl_callcenter->getUidbyid($callid);
		$record = $this->mdl_call_happy_record->getHappyCallRecord($callid);
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
		$this->form_validation->set_rules('answer[]');
		if ($this->form_validation->set_rules('answer[]')){
			
			}
		if ($this->form_validation->run() == FALSE){
		$data = array(
					'questions'=>$questions['list'],
					'callid'=>$callid,
					'call_uid'=>$call_uid,
					'records'=>$record,
					'callat_select'=>$callat_select
			);
		
		$this->load->view('tab_happycall',$data);
		}else{
		$ques = array();
		$ans = array();
		$ques= $this->input->post('question');
		$ans= $this->input->post('answer');
		$data['call_id']= $callid;
		
		for($i=0; $i< $questions['total']; $i++)
		{
			$data['question_id']= $ques[$i];
			$data['answer']= $ans[$i];
			
			$happy_call_id= $this->mdl_call_happy->getid($ques[$i],$callid);
		     if ($happy_call_id >0)
			 		{
					 $data["call_happy_modified_ts"]=date("Y-m-d H-i-s");
			 		 $data["call_happy_modified_by"]=$this->session->userdata('user_id');
					 $this->mdl_call_happy->save($data,$happy_call_id);
				  }
			 else
			 	{if (!empty($ans[$i])){
			 		$data["call_happy_created_ts"]=date("Y-m-d H-i-s");
					$data["call_happy_created_by"]=$this->session->userdata('user_id');
			 		$this->mdl_call_happy->save($data); 
					}
					}
			     }
		
				 // Update happy call status on calls table
				 $caldata['happy_status']=1;
				$caldata['call_at'] = $this->input->post('call_at');
				$this->mdl_callcenter->save($caldata,$callid);
				//$this->mdl_call_happy_record->saverecord($callid);
			$this->session->set_flashdata('happy_call_save', 'Happy Call Verified');
			redirect('callcenter/callregistration/'.$callid);
		}
	
	}
	public function reopen($callid){
		$caldata['call_status']=0;
		$caldata['reopened']=1;
		$caldata['happy_status']=0;
		$this->mdl_callcenter->save($caldata,$callid);
		redirect ('callcenter/callregistration/'.$callid);
		} 
		
		
		
		
	function savehappycalled(){
		
		$this->load->view('called');
		
		
		}
		/**************************************Defected parts******************************/
	function addDefectedPart(){
		$this->load->model('parts/mdl_parts_defected');
		$data_defect_parts = array( 'part_number' => $this->input->post('part_defected_no'),
										'call_id' => $this->session->userdata('call_session_id'), 
										'part_desc' => $this->input->post('part_defected_desc'),
										'part_quantity' => $this->input->post('part_defected_quantity'),
										'part_serial_no' => $this->input->post('sn'),
										'parts_defect_created_by' => $this->session->userdata('user_id'),
										'parts_defect_created_ts' => date("Y-m-d H:i:s"),
										'parts_defect_last_mod_by' => $this->session->userdata('user_id'), 
										'parts_defect_last_mod_ts' =>  date("Y-m-d H:i:s")); //have to change
			$this->load->model('parts/mdl_parts_defected');
			$this->mdl_parts_defected->save($data_defect_parts);
		}
	function checkpart(){
		$part_quantity= $this->input->post('part_defected_quantity');
		$part_number= $this->input->post('part_defected_no');
		$this->load->model(array('parts/mdl_parts'));
		$partlist= $this->mdl_parts->getpart();
		if ( (strpos( $part_quantity, "." ) == false ) && is_numeric($part_quantity)==true && $part_quantity > 0) {
			foreach ($partlist as $part){
			     if ($part->part_number == $part_number){
			         echo 1;
			         die();
					}
				} 
			echo 3; die();
			
			}else {
				foreach ($partlist as $part){
			if ($part->part_number == $part_number){
			echo 2;
			die();
				}
			} 
			echo 4; die();}			
	    }
		
	function checkDefectedPart(){
		$part_quantity= $this->input->post('part_defected_quantity');
		$part_number= $this->input->post('part_defected_no');
		$this->load->model(array('parts/mdl_parts_defected'));
		$partlist= $this->mdl_parts_defected->getDefectedData($this->session->userdata('call_session_id'));
			foreach ($partlist as $part){
			     if ($part->part_number == $part_number){
			         echo 1;
			         die();
					}
				} 		
	    }	
		function removeDefectpart(){
		$this->redir->set_last_index();
		$this->load->model(array('parts/mdl_parts_defected'));
		$defect_part_no = $this->input->post('part_defected_no');
		if($this->mdl_parts_defected->delete(array('part_number'=>$defect_part_no, 'call_id' => $this->session->userdata('call_session_id')  ))){
			$error = array('type'=>'delete','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}}
		/*******************************************Defected parts ends*****************************************************/
		
		
		
		function getproductsbybrandsearch()
	{
		$this->load->model(array('products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		
		$brandOptions = $this->mdl_products->getProductsByBrand($this->input->post('brand_id'));
		array_unshift($brandOptions,$this->mdl_html->option( '', 'Select Product'));
		$brandlist = $this->mdl_html->genericlist($brandOptions,'product_select_search',array('class'=>'validate[required] text-input'),'value','text','');
		echo $brandlist;
	}
	
	
	function happycall_done(){
			$call_id = $this->input->post('uid');
			$data = array(
						  'call_id' => $call_id
						  );
			$this->load->view('happyremark',$data);
		}
		
	function savecalldatess(){
		$this->load->model(array('callcenter/mdl_call_happy_record'));
		$this->mdl_call_happy_record->saverecord();
		
		}
}
?>